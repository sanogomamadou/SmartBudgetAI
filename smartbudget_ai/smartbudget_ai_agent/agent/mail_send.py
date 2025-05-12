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
        smtp.login("mamadousanogo352@gmail.com", "oppe nkay injd lvyu")  # ⚠️ pas ton mot de passe normal !
        smtp.send_message(email)

