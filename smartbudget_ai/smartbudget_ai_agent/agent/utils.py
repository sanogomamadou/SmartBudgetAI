# agent/utils.py
import os
import mysql.connector
from dotenv import load_dotenv
import requests

load_dotenv()

def get_db_connection():
    conn = mysql.connector.connect(
        host=os.getenv("DB_HOST"),
        user=os.getenv("DB_USER"),
        password=os.getenv("DB_PASSWORD"),
        database=os.getenv("DB_NAME"),
        port=int(os.getenv("DB_PORT"))
    )
    return conn

def send_email(to_email: str, subject: str, text: str):
    api_key = os.getenv("MAIL_API_KEY")
    domain = os.getenv("MAIL_DOMAIN")
    sender = os.getenv("MAIL_SENDER")
    
    response = requests.post(
        f"https://api.mailgun.net/v3/{domain}/messages",
        auth=("api", api_key),
        data={
            "from": f"SmartBudget AI <{sender}>",
            "to": [to_email],
            "subject": subject,
            "text": text
        }
    )
    return response.status_code == 200
