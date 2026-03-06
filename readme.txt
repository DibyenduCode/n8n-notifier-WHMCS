📢 n8n Notifier WHMCS Addon

A WHMCS addon module that helps administrators get notified and remind
clients when n8n Hosting services are not renewed
(Cancelled/Terminated/Expired).
This ensures admins stay informed and clients receive reminders to take
action, reducing accidental service loss.

-----------------------------------------------------------------------

🚀 Features

-   🔔 Admin Notifications
    -   Get instant email alerts when a client’s n8n Hosting service is
        cancelled or terminated.
-   📧 Client Reminders
    -   Sends automated emails to clients reminding them to renew their
        n8n Hosting service before suspension/termination.
-   ⚙️ Customizable Templates
    -   Email templates for both admin and client are fully editable in
        WHMCS.
-   📅 Cronjob Based
    -   Runs automatically with WHMCS Daily Cron to ensure timely checks
        and notifications.
-   🛠️ Easy Configuration
    -   Simple settings: just set your n8n Product ID, Admin Email, and
        you’re good to go.

------------------------------------------------------------------------

📂 Installation

1.  Upload zip file in your WHMCS root folder
2.Extract the zip in your root folder
3.  Activate in WHMCS
    -   Login to WHMCS Admin Panel → Setup → Addon Modules
    -   Find n8n Notifier and click Activate
4.  Configure Settings
    -   Set:
        -   Admin Email (who will receive notifications when daily cron run)
        -   n8n Hosting Product ID(s){The id of the products which you want to connect with this addon}
        -  A Valid Admin username(Must because without it smtp will not working) 

------------------------------------------------------------------------

⚡ Usage

-   Once activated, the addon will automatically check hosting services
    daily.
-   If any n8n Hosting service is found Cancelled/Terminated, the
    module:
    -   Sends an email to Admin
    -   Optionally admin can send a Reminder Email to Client
Note:The email will send from LocalApi of WHMCS.It means email will send by WHMCS default smtp system which you set in your WHMCS 
------------------------------------------------------------------------

📑 Email Templates

The addon comes with two email templates:

1.  Admin Notification Template
    -   Subject: n8n Hosting Service Alert
    -   Body: Contains client details, service ID,
        cancellation/termination info
2.  Client Reminder Template
    -   Subject: Reminder: Renew Your n8n Hosting
    -   Body: Friendly reminder of renewal

➡️ You can edit these templates from module/addons/n8nnotifier/n8nnotifier.php

------------------------------------------------------------------------

⚙️ Configuration Options

-   Admin Email → Where alerts will be sent
-   n8n Product ID → WHMCS Product ID for your n8n Hosting
-   Enable Client Reminder → ON/OFF toggle

------------------------------------------------------------------------

📌 Requirements

-   WHMCS v8.0+
-   PHP 7.4+
-   Active Cronjob configured in WHMCS

------------------------------------------------------------------------

🛠️ Development Notes

-   Hooks used:
    -   DailyCronJob → runs checks automatically
-   Uses WHMCS LocalAPI to send emails
-   Database: reads from tblhosting to detect service status

------------------------------------------------------------------------

🧑‍💻 Example Workflow

1.  Client fails to pay invoice → service enters Suspended/Terminated/Cancel state
2.  WHMCS Cron runs → n8n Notifier detects status
3.  Module:
    -   Sends Admin Email (service details + client info)
    -   Sends Client Reminder from n8nnotifier configuration

------------------------------------------------------------------------

📬 Support

For issues or feature requests:
- Call us:+8801570212139
- WhatsApp:https://wa.me/8801570212139


Thank You
Tup Hoster Ltd