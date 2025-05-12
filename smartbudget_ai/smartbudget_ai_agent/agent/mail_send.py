import smtplib
from email.message import EmailMessage

def envoyer_email_gmail(destinataire, sujet, contenu):
    email = EmailMessage()
    email.set_content(contenu)
    email['Subject'] = sujet
    email['From'] = "mamadousanogo352@gmail.com"  # Remplace avec ton adresse Gmail
    email['To'] = destinataire

    # Connexion SMTP sécurisée
    with smtplib.SMTP_SSL('smtp.gmail.com', 465) as smtp:
        smtp.login("mamadousanogo352@gmail.com", "XXXXXXXXXX")  # remplace par ta propre clé GMAIL !
        smtp.send_message(email)

