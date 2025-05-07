# agent/tools.py
from langchain.tools import tool
from agent.utils import get_db_connection
from datetime import datetime

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