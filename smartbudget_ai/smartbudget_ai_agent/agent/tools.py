# agent/tools.py
from langchain.tools import tool
from agent.utils import get_db_connection
from datetime import datetime, timedelta
import calendar


@tool
def analyze_expenses(input_str: str) -> str:
    """Analyse les dépenses de l'utilisateur entre deux dates. 
    L'input doit être au format: 'user_id|start_date|end_date'
    Exemple: '1|2023-01-01|2023-12-31'"""
    try:
        # Parse the input string
        parts = input_str.split('|')
        if len(parts) != 3:
            return "Format d'entrée invalide. Utilisez: 'user_id|start_date|end_date'"
        
        user_id = int(parts[0])
        start_date = parts[1]
        end_date = parts[2]

        conn = get_db_connection()
        cursor = conn.cursor()
        query = """
            SELECT categorie, SUM(montant)
            FROM transactions
            WHERE user_id = %s AND type = 'Dépense' AND date BETWEEN %s AND %s
            GROUP BY categorie
        """
        cursor.execute(query, (user_id, start_date, end_date))
        results = cursor.fetchall()
        cursor.close()
        conn.close()

        if not results:
            return "Aucune dépense trouvée pour cette période."
        
        response = f"""Dépenses de l'utilisateur #{user_id} entre {start_date} et {end_date} :\n"""
        for categorie, total in results:
            response += f"- {categorie} : {total:.2f} MAD\n"
        return response
    except Exception as e:
        return f"Erreur lors de l'analyse des dépenses : {str(e)}"
    

@tool
def setBudget(input_str: str) -> str:
    """Permet de mettre à jour ou définir un budget prévisionnel pour un mois donné.
    L'input doit être au format: 'user_id|categorie|budget_montant'
    Exemple: '1|Alimentation|5000.00'"""
    try:
        # Parse l'entrée
        parts = input_str.split('|')
        if len(parts) != 3:
            return "Format d'entrée invalide. Utilisez: 'user_id|categorie|budget_montant'"

        user_id = int(parts[0])
        categorie = parts[1]
        budget_montant = float(parts[2])

        # Connexion à la base de données
        conn = get_db_connection()
        cursor = conn.cursor()

        # Obtenir le mois actuel
        current_month = datetime.now().strftime('%Y-%m-01')  # Format DATE SQL

        # Solution optimisée avec ON DUPLICATE KEY UPDATE
        query = """
            INSERT INTO budgets (user_id, category, amount, month)
            VALUES (%s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE amount = VALUES(amount)
        """
        cursor.execute(query, (user_id, categorie, budget_montant, current_month))
        conn.commit()
        
        cursor.close()
        conn.close()
        return f"Budget '{categorie}' mis à jour à {budget_montant:.2f} MAD pour {current_month[:7]}"
        
    except Exception as e:
        return f"Erreur lors de la mise à jour du budget : {str(e)}"
    
@tool
def estimerEconomies(input_str: str) -> str:
    """Estime les économies possibles. Deux modes:
    1. 'user_id|categorie|reduction_montant' → économie fixe
    2. 'user_id|categorie|target_montant|TARGET' → économie pour atteindre un budget cible
    Exemples: 
    - '1|Alimentation|200' → réduit de 200 MAD
    - '1|Alimentation|200|TARGET' → réduit à 200 MAD"""
    try:
        parts = input_str.split('|')
        if len(parts) not in [3,4]:
            return "Format invalide. Utilisez : 'user_id|categorie|montant' ou 'user_id|categorie|target|TARGET'"

        user_id = int(parts[0])
        categorie = parts[1]
        mode_target = len(parts) == 4 and parts[3] == "TARGET"

        conn = get_db_connection()
        cursor = conn.cursor()

        # Requête pour obtenir la dépense mensuelle maximale
        query = """
            SELECT MAX(montant_total) FROM (
                SELECT SUM(montant) AS montant_total
                FROM transactions
                WHERE user_id = %s AND categorie = %s
                GROUP BY DATE_FORMAT(date, '%Y-%m')
            ) AS monthly_totals;
        """
        cursor.execute(query, (user_id, categorie))
        result = cursor.fetchone()
        max_depense = float(result[0]) if result and result[0] else 0

        if mode_target:
            target = float(parts[2])
            if target >= max_depense:
                return f"Votre cible ({target:.2f} MAD) est supérieure à votre dépense maximale actuelle ({max_depense:.2f} MAD)."
            reduction = max_depense - target
        else:
            reduction = float(parts[2])
            if reduction > max_depense:
                return f"Impossible de réduire de {reduction:.2f} MAD (dépense max: {max_depense:.2f} MAD)"

        economie_annuelle = reduction * 12
        
        return (
            f"En réduisant vos dépenses {f'à {target:.2f}' if mode_target else f'de {reduction:.2f}'} MAD/mois "
            f"dans '{categorie}', vous économiseriez **{economie_annuelle:.2f} MAD**/an. 💰"
        )

    except Exception as e:
        return f"Erreur de calcul: {str(e)}"
    
@tool
def ajouterTransaction(input_str: str) -> str:
    """Ajoute une transaction (dépense ou revenu) pour un utilisateur.
    Format : 'user_id|type|categorie|montant'
    Exemple : '1|Dépense|Alimentation|250.00'"""
    try:
        parts = input_str.split('|')
        if len(parts) != 4:
            return "Format invalide. Utilisez : 'user_id|type|categorie|montant'"

        user_id = int(parts[0])
        type_trans = parts[1].capitalize()
        categorie = parts[2]
        montant = float(parts[3])

        if type_trans not in ['Dépense', 'Revenu']:
            return "Le type doit être soit 'Dépense' soit 'Revenu'."

        conn = get_db_connection()
        cursor = conn.cursor()

        date_now = datetime.now().strftime('%Y-%m-%d')

        query = """
            INSERT INTO transactions (user_id, type, categorie, montant, date)
            VALUES (%s, %s, %s, %s, %s)
        """
        cursor.execute(query, (user_id, type_trans, categorie, montant, date_now))
        conn.commit()
        cursor.close()
        conn.close()

        return f"{type_trans} de {montant:.2f} MAD ajoutée dans la catégorie '{categorie}' pour l'utilisateur #{user_id} le {date_now}."

    except Exception as e:
        return f"Erreur lors de l'ajout de la transaction : {str(e)}"



@tool
def conseillerBudget(input_str: str) -> str:
    """Analyse les dépenses des 3 derniers mois pour un étudiant, les compare à des moyennes et donne des conseils de gestion.
    Input : user_id"""
    try:
        user_id = int(input_str)

        conn = get_db_connection()
        cursor = conn.cursor()

        # Calcul des 3 derniers mois
        today = datetime.today()
        months = []
        for i in range(3):
            month = today.month - i
            year = today.year
            if month <= 0:
                month += 12
                year -= 1
            first_day = f"{year}-{month:02d}-01"
            last_day = f"{year}-{month:02d}-{calendar.monthrange(year, month)[1]}"
            months.append((first_day, last_day))

        full_report = ""

        # Normes fictives de budget étudiant
        normes = {
            'Alimentation': 500,
            'Transport': 300,
            'Loisirs': 300,
            'Abonnement': 100,
        }

        # Analyse de chaque mois
        for start_date, end_date in reversed(months):
            cursor.execute("""
                SELECT categorie, SUM(montant)
                FROM transactions
                WHERE user_id = %s AND type = 'Dépense' AND date BETWEEN %s AND %s
                GROUP BY categorie
            """, (user_id, start_date, end_date))
            results = cursor.fetchall()

            report = f"\n📆 **Période : {start_date} au {end_date}**\n"
            total = 0

            for cat, montant in results:
                total += montant
                norme = normes.get(cat, 0)
                ecart = montant - norme
                tendance = "⬆️ au-dessus" if ecart > 0 else "⬇️ en-dessous"
                report += f"- {cat} : {montant:.2f} MAD ({tendance} de {abs(ecart):.2f} MAD vs norme de {norme} MAD)\n"

            report += f"**Total dépenses** : {total:.2f} MAD\n"
            full_report += report

        cursor.close()
        conn.close()

        prompt = f"""Tu es un expert en gestion financière pour étudiants.
Voici le détail des dépenses d’un étudiant sur les 3 derniers mois par catégorie, avec comparaison aux normes :

{full_report}

Donne-lui des conseils précis et bienveillants pour améliorer sa gestion financière."""

        from agent.langchain_agent import llm
        conseils = llm.invoke(prompt)

        return f"{full_report}\n💡 **Conseils de gestion générés par l'IA** :\n{conseils}"
        

    except Exception as e:
        return f"Erreur dans conseillerBudget : {str(e)}"
    

@tool
def analyseFinanciereMensuelle(input_str: str) -> str:
    """Donne un aperçu financier pour le mois en cours basé sur les transactions et les récurrents.
    Input : user_id"""
    try:
        user_id = int(input_str)
        from datetime import date
        import calendar

        conn = get_db_connection()
        cursor = conn.cursor()

        today = date.today()
        first_day = today.replace(day=1)
        last_day = today.replace(day=calendar.monthrange(today.year, today.month)[1])
        jours_restants = (last_day - today).days

        ### 1. Calcul du solde actuel ###
        cursor.execute("""
            SELECT type, SUM(montant)
            FROM transactions
            WHERE user_id = %s AND date BETWEEN %s AND %s
            GROUP BY type
        """, (user_id, first_day, today))
        rows = cursor.fetchall()
        solde_actuel = 0.0
        for type_, montant in rows:
            montant = float(montant or 0)
            if type_ == "Revenu":
                solde_actuel += montant
            elif type_ == "Dépense":
                solde_actuel -= montant

        ### 2. Ajouter les récurrents à venir ###
        cursor.execute("""
            SELECT type, montant, date_prochaine
            FROM transactions_recurrentes
            WHERE user_id = %s AND frequence = 'mensuel'
        """, (user_id,))
        rows = cursor.fetchall()
        for type_, montant, date_prochaine in rows:
            montant = float(montant or 0)
            if today < date_prochaine <= last_day:
                if type_ == "Revenu":
                    solde_actuel += montant
                elif type_ == "Dépense":
                    solde_actuel -= montant

        solde_fin_mois = solde_actuel

        ### 3. Alerte financière ###
        moyenne_journaliere = solde_actuel / max(today.day, 1)
        prevision_mensuelle = moyenne_journaliere * calendar.monthrange(today.year, today.month)[1]
        seuil_alerte = 0.2 * prevision_mensuelle

        if solde_fin_mois < seuil_alerte:
            niveau_alerte = "🔴 Risque élevé"
        elif solde_fin_mois < 0.5 * prevision_mensuelle:
            niveau_alerte = "🟠 Situation modérée"
        else:
            niveau_alerte = "🟢 Situation stable"

        cursor.close()
        conn.close()

        return (
            f"📊 **Bilan financier du mois en cours** :\n"
            f"- Solde actuel : {solde_actuel:.2f} MAD\n"
            f"- Solde estimé en fin de mois : {solde_fin_mois:.2f} MAD\n"
            f"- Jours restants : {jours_restants} jours\n"
            f"- Niveau d'alerte : {niveau_alerte}"
        )

    except Exception as e:
        return f"Erreur dans analyseFinanciereMensuelle : {str(e)}"
