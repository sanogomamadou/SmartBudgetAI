# agent/langchain_agent.py
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain.agents import initialize_agent, AgentType
from agent.tools import analyze_expenses
import os

llm = ChatGoogleGenerativeAI(
    model="gemini-2.0-flash",
    temperature=1.0,
    google_api_key=os.getenv("GOOGLE_API_KEY")
)

tools = [analyze_expenses]

agent_executor = initialize_agent(
    tools,
    llm,
    agent=AgentType.ZERO_SHOT_REACT_DESCRIPTION,
    verbose=True
)

def run_agent(query: str, user_id: int) -> str:
    print(f"Utilisateur ID au niveau de langchain: {user_id}")
    return agent_executor.run({"input": query, "user_id": user_id})
