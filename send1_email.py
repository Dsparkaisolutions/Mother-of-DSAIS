import sys
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from email.mime.base import MIMEBase
from email import encoders
import os

# Email configuration
sender_email = "datasparkaisolutions@gmail.com"
receiver_email = "hradmin@dsparkai.com"
password = "ixgcutbmhwrhvgsl"

# Check if enough arguments are provided
if len(sys.argv) < 7:
    print("Error: Insufficient arguments provided")
    sys.exit(1)

# Fetch arguments from command line (passed by PHP script)
yourname = sys.argv[1]
youremail = sys.argv[2]
mobilenumber = sys.argv[3]
dob = sys.argv[4]
message_body = sys.argv[5]
link = sys.argv[6]

# Path to the uploads folder
uploads_folder = 'uploads'

# Find the most recent file in the uploads folder
try:
    files = [os.path.join(uploads_folder, f) for f in os.listdir(uploads_folder) if os.path.isfile(os.path.join(uploads_folder, f))]
    resume_path = max(files, key=os.path.getctime)
except ValueError:
    print("Error: No files found in the uploads folder")
    sys.exit(1)

# Email content
subject = "New Application Received"
body = f"""
Name: {yourname}
Email: {youremail}
Mobile Number: {mobilenumber}
Date of Birth: {dob}
Message: {message_body}
Link: {link}
"""

message = MIMEMultipart()
message["From"] = sender_email
message["To"] = receiver_email
message["Subject"] = subject

# Attach message body
message.attach(MIMEText(body, "plain"))

# Attach resume file
try:
    with open(resume_path, "rb") as attachment:
        part = MIMEBase("application", "octet-stream")
        part.set_payload(attachment.read())
    encoders.encode_base64(part)
    part.add_header(
        "Content-Disposition",
        f"attachment; filename= {os.path.basename(resume_path)}",
    )
    message.attach(part)
except IOError:
    print(f"Error: Unable to attach file {resume_path}")
    sys.exit(1)

# Send email
try:
    server = smtplib.SMTP("smtp.gmail.com", 587)
    server.starttls()
    server.login(sender_email, password)
    server.sendmail(sender_email, receiver_email, message.as_string())
    server.quit()
    print("Email sent successfully.")
except Exception as e:
    print(f"Error: {e}")
    sys.exit(1)
