# agent/langchain_agent.py
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain.agents import initialize_agent, AgentType
from agent.tools import analyze_expenses,setBudget, estimerEconomies,ajouterTransaction,conseillerBudget,analyseFinanciereMensuelle
import os
from langchain.prompts import MessagesPlaceholder
from langchain_core.messages import SystemMessage

system_message = SystemMessage(
    content="""Tu es Smart Budget AI, un assistant financier amical et encourageant qui parle francais. 
    - Utilise un ton chaleureux et positif
    - Ajoute des emojis pertinents
    - Félicite l'utilisateur pour les bonnes actions
    - Sois concis mais sympathique
    -Evite de retourner l'ID de l'utilisateur dans ta réponse finale"""
)

llm = ChatGoogleGenerativeAI(
    model="gemini-2.0-flash",
    temperature=1.0,
    google_api_key=os.getenv("GOOGLE_API_KEY")
)

tools = [analyze_expenses,setBudget,estimerEconomies,ajouterTransaction,conseillerBudget,analyseFinanciereMensuelle]

agent_executor = initialize_agent(
    tools,
    llm,
    agent=AgentType.ZERO_SHOT_REACT_DESCRIPTION,
    verbose=True,
    agent_kwargs={
        'system_message': system_message,
        'prefix': """Commence toujours par une formule d'accueil comme "Super!" ou "Parfait!" 
        et termine par une note positive."""
    },
    handle_parsing_errors=True
)

def run_agent(query: str, user_id: int) -> str:
    print(f"Utilisateur ID au niveau de langchain: {user_id}")
    return agent_executor.run({"input": query, "user_id": user_id})


def generate_smart_alert(alert_data: dict) -> tuple[str, str]:
    """Version robuste de la génération d'alertes"""
    try:
        prompt = f"""
        Tu es un assistant financier intelligent. Génère pour {alert_data.get('user_name', '')} une alerte email pour un dépassement de budget avec ces détails:
        - Catégorie: {alert_data.get('category', '')}
        - Dépensé: {alert_data.get('spent', 0)} MAD
        - Budget: {alert_data.get('planned', 0)} MAD
        - Mois: {alert_data.get('month', '')}

        Ton message doit:
        1. Commencer par une phrase personnalisée avec le prénom
        2. Expliquer le dépassement simplement
        3. Donner 1 conseil personnalisé (ex: comparer avec les mois précédents)
        4. Proposer une action concrète
        5. Ton style: amical mais professionnel, avec emoji pertinents
        
        Format requis:
        Sujet: [sujet court]
        Message: [message personnalisé]
        """
        
        # Version sécurisée avec fallback
        response = llm.invoke(prompt)
        content = response.content
        
        # Fallback si le format n'est pas respecté
        if "Sujet:" not in content or "Message:" not in content:
            subject = f"🚨 Alerte budget {alert_data.get('category', '')}"
            message = f"Bonjour {alert_data.get('user_name', '')},\n\nVous avez dépassé votre budget ({alert_data.get('spent', 0)} MAD au lieu de {alert_data.get('planned', 0)} MAD)."
            return subject, message
        
        # Extraction sécurisée
        try:
            subject = content.split("Sujet:")[1].split("\n")[0].strip()
            message = content.split("Message:")[1].strip()
            return subject, message
        except:
            # Fallback minimal si l'extraction échoue
            return "Alerte budget", content

    except Exception as e:
        # Fallback en cas d'erreur totale
        return (
            f"Alerte budget {alert_data.get('category', '')}",
            f"Dépassement détecté: {alert_data.get('spent', 0)} MAD / {alert_data.get('planned', 0)} MAD"
        )
