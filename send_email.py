#!/usr/bin/env python3
#git added comment1
import sys
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

# Email configuration
sender_email = "datasparkaisolutions@gmail.com"  # Update with your email address
receiver_email = "hradmin@dsparkai.com"  # Update with recipient's email address
password = "ixgcutbmhwrhvgsl"  # Update with your email password

# Fetch arguments from command-line (provided by PHP)
yourname = sys.argv[1]
youremail = sys.argv[2]
mobilenumber = sys.argv[3]
message_body = sys.argv[4]

# Email content
subject = "New Enquiry Received"
body = f"""
Name: {yourname}
Email: {youremail}
Mobile Number: {mobilenumber}
Message:
{message_body}
"""

message = MIMEMultipart()
message["From"] = sender_email
message["To"] = receiver_email
message["Subject"] = subject

message.attach(MIMEText(body, "plain"))

# Send email
try:
    server = smtplib.SMTP("smtp.gmail.com", 587)  # SMTP server details for Gmail
    server.starttls()
    server.login(sender_email, password)
    server.sendmail(sender_email, receiver_email, message.as_string())
    server.quit()
except Exception as e:
    # Optionally handle errors, though no specific error message is printed in this example
    pass


