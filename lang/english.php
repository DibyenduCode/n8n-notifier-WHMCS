<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

$_ADDONLANG = [
   
    'title'             => "n8n Notifier",
    'description'       => "Notify admin and remind clients if n8n Hosting is not renewed (Cancelled/Terminated).",

    'expired_clients'   => "Expired n8n Clients",
    'no_expired'        => "No expired clients found.",
    'client'            => "Client",
    'email'             => "Email",
    'serviceid'         => "Service ID",
    'status'            => "Status",
    'duedate'           => "Next Due Date",
    'actions'           => "Actions",
    'view_profile'      => "View Profile",
    'send_mail'         => "Send Mail",

    'mail_sent'         => "✅ Renewal reminder sent to :email",
    'mail_failed'       => "❌ Failed to send email: :error",
];
