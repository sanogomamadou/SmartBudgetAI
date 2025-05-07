# main.py
from fastapi import FastAPI, Request
from fastapi.middleware.cors import CORSMiddleware
from agent.langchain_agent import run_agent
from pydantic import BaseModel
import uvicorn
import os

app = FastAPI()

# Autoriser le frontend PHP à accéder à l'API
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # En prod : restreindre
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class AgentRequest(BaseModel):
    user_id: int
    query: str

@app.post("/ask-agent")
async def ask_agent(data: AgentRequest):
    response = run_agent(data.query, data.user_id)
    print(f"Utilisateur ID au niveau de main : {data.user_id}")
    return {"response": response}

if __name__ == "__main__":
    uvicorn.run("main:app", host="127.0.0.1", port=8000, reload=True)
