<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;

/**
 * Automatically notifies admin and reminds clients via email when n8n Hosting services are expired, cancelled, or terminated.
 * Author:zweilo
 * copyright 2026 zweilo.com
 */
function n8nnotifier_create_email_templates()
{
    $emailTemplates = [
        'client' => [
            'name'    => 'n8n Hosting Renewal Reminder',
            'type'    => 'general',
            'subject' => '⚠️ Action Required: Please Renew Your n8n Hosting',
            'message' => "Dear {\$client_name},<br><br>
We noticed that your n8n Hosting service (Service ID: {\$serviceid}) has not been renewed and is currently suspended/terminated.<br><br>
Please renew it as soon as possible to continue using our service.<br><br>
Best Regards,<br>Support Team",
        ],
        'admin' => [
            'name'    => 'n8n Hosting Not Renewed',
            'type'    => 'admin',
            'subject' => '❌ n8n Hosting Not Renewed - {\$client_name}',
            'message' => "Hello Admin,<br><br>
The following client has not renewed their n8n Hosting:<br><br>
<strong>Client:</strong> {\$client_name}<br>
<strong>Email:</strong> {\$client_email}<br>
<strong>Service ID:</strong> {\$serviceid}<br>
<strong>Next Due Date:</strong> {\$duedate}<br><br>
Please review their account.<br><br>
Thanks.",
        ],
    ];

    foreach ($emailTemplates as $template) {
        $exists = Capsule::table('tblemailtemplates')->where('name', $template['name'])->first();
        if (!$exists) {
            Capsule::table('tblemailtemplates')->insert([
                'type'    => $template['type'],
                'name'    => $template['name'],
                'subject' => $template['subject'],
                'message' => $template['message'],
                'custom'  => '1',
                'language' => '',
            ]);
        }
    }
}

/**
 * Automatically notifies admin and reminds clients via email when n8n Hosting services are expired, cancelled, or terminated.
 * Author:zweilo
 * copyright 2026 zweilo.cm
 */
function n8nnotifier_config()
{
    return [
        "name"        => "n8n Notifier",
        "description" => "Automatically notifies admin and reminds clients via email when n8n Hosting services are expired, cancelled, or terminated.
        <br><br>
        For custom module or hook development, feel free to contact us on 
        <a href='https://wa.me/8801570212139' target='_blank' style='text-decoration: none; font-weight: bold; color: #25D366;'>
            <img src='https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg' alt='WhatsApp' style='height: 16px; vertical-align: middle;'> WhatsApp
        </a>.",
        "author" => '<a href="https://zweilo.com/" target="_blank"><img src="https://zweilo.com/wp-content/uploads/2026/01/Untitled-design.png" alt="zweilo" style="max-height: 20px;"></a>',
        "version"     => "1.0",
        "fields"      => [
            "adminEmail" => [
                "FriendlyName" => "Admin Email",
                "Type"         => "text",
                "Size"         => "50",
                "Default"      => "",
                "Description"  => "Admin notification email",
            ],
            "adminUser" => [
                "FriendlyName" => "Admin Username",
                "Type"         => "text",
                "Size"         => "30",
                "Default"      => "admin",
                "Description"  => "A valid WHMCS admin username for LocalAPI.",
            ],
            "productIds" => [
                "FriendlyName" => "Product IDs",
                "Type"         => "text",
                "Size"         => "50",
                "Default"      => "1,2,3",
                "Description"  => "Comma-separated Product IDs (e.g., 1,2,3).",
            ],
        ],
    ];
}


function n8nnotifier_output($vars)
{
    n8nnotifier_create_email_templates();

    $productIds = array_map('trim', explode(',', $vars['productIds']));

    if (isset($_GET['action']) && $_GET['action'] == 'sendMail' && isset($_GET['clientId']) && is_numeric($_GET['clientId'])) {
        $clientId = (int)$_GET['clientId'];
        $client = Capsule::table('tblclients')->where('id', $clientId)->first();
        $adminUser = $vars['adminUser'] ?? null;

        if ($client) {
            $result = localAPI('SendEmail', [
                'id' => $client->id,
                'messagename' => 'n8n Hosting Renewal Reminder'
            ], $adminUser);

            header('Content-Type: application/json');
            echo json_encode(['success' => $result['result'] == 'success', 'message' => $result['message'] ?? '']);
            exit();
        }
    }

    $expiredServices = Capsule::table('tblhosting')
        ->whereIn('packageid', $productIds)
        ->whereIn('domainstatus', ['Terminated', 'Cancelled'])
        ->where('nextduedate', '<', date('Y-m-d'))
        ->join('tblclients', 'tblhosting.userid', '=', 'tblclients.id')
        ->select('tblhosting.*', 'tblclients.firstname', 'tblclients.lastname', 'tblclients.email')
        ->get();

    echo "
    <style>
        .n8n-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .n8n-header {
            background-color: white;
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
            text-align: center;
        }
        .n8n-alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .n8n-alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .n8n-alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .n8n-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .n8n-table th, .n8n-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            text-align: left;
        }
        .n8n-table th {
            background-color: #e9ecef;
            font-weight: 600;
        }
        .n8n-table tr:hover {
            background-color: #f1f1f1;
        }
        .n8n-table .action-link {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }
        .n8n-table .action-link:hover {
            text-decoration: underline;
        }
        .n8n-table .send-mail-link {
            color: #dc3545;
            cursor: pointer;
        }
    </style>

    <div class='n8n-container'>
        <div class='n8n-header'>
            <h1>🚨 Expired n8n Clients</h1>
        </div>
        <div id='status-message-container'></div>
    ";

    if ($expiredServices->isEmpty()) {
        echo "<p style='text-align:center;'><strong>No expired clients found.</strong></p>";
        return;
    }

    echo "
        <table class='n8n-table'>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Service ID</th>
                    <th>Status</th>
                    <th>Next Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    ";

    foreach ($expiredServices as $service) {
        echo "
                <tr data-client-id='{$service->userid}'>
                    <td>{$service->firstname} {$service->lastname}</td>
                    <td>{$service->email}</td>
                    <td>{$service->id}</td>
                    <td>{$service->domainstatus}</td>
                    <td>{$service->nextduedate}</td>
                    <td>
                        <a href='clientssummary.php?userid={$service->userid}' target='_blank' class='action-link'>View Profile</a> | 
                        <span class='action-link send-mail-link' data-client-id='{$service->userid}'>Send Mail to Client</span>
                    </td>
                </tr>
        ";
    }

    echo "
            </tbody>
        </table>
    </div>

    <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
    <script>
        $(document).ready(function() {
            $('.send-mail-link').on('click', function(e) {
                e.preventDefault();
                const clientId = $(this).data('client-id');
                const row = $(this).closest('tr');
                const originalText = $(this).text();
                const sendLink = $(this);

                // Disable link and show loading state
                sendLink.text('Sending...').prop('disabled', true).addClass('disabled');

                $.ajax({
                    url: 'addonmodules.php?module=n8nnotifier&action=sendMail&clientId=' + clientId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        const statusContainer = $('#status-message-container');
                        let messageHtml = '';

                        if (response.success) {
                            messageHtml = '<div class=\"n8n-alert n8n-alert-success\">✅ Renewal reminder sent successfully.</div>';
                        } else {
                            messageHtml = '<div class=\"n8n-alert n8n-alert-danger\">❌ Failed to send email: ' + response.message + '</div>';
                        }

                        statusContainer.html(messageHtml);
                        sendLink.text(originalText).prop('disabled', false).removeClass('disabled');
                    },
                    error: function(xhr, status, error) {
                        const statusContainer = $('#status-message-container');
                        statusContainer.html('<div class=\"n8n-alert n8n-alert-danger\">❌ An error occurred: ' + error + '</div>');
                        sendLink.text(originalText).prop('disabled', false).removeClass('disabled');
                    }
                });
            });
        });
    </script>
    ";
}

/**
 * Automatically notifies admin and reminds clients via email when n8n Hosting services are expired, cancelled, or terminated.
 * Author:Hasan Mahmud
 * copyright 2025 Tup Hoster Ltd
 */
add_hook('DailyCronJob', 1, function($vars) {
    n8nnotifier_create_email_templates();

    $settings = Capsule::table('tbladdonmodules')->where('module', 'n8nnotifier')->pluck('value', 'setting');

    $adminEmail = $settings['adminEmail'] ?? "admin@example.com";
    $adminUser  = $settings['adminUser'] ?? null;
    $productIds = array_map('trim', explode(',', $settings['productIds'] ?? "1"));

    $expiredServices = Capsule::table('tblhosting')
        ->whereIn('packageid', $productIds)
        ->whereIn('domainstatus', ['Terminated', 'Cancelled'])
        ->where('nextduedate', '<', date('Y-m-d'))
        ->get();

    foreach ($expiredServices as $service) {
        $client = Capsule::table('tblclients')->where('id', $service->userid)->first();

        // Check if a To-Do list entry for this client already exists
        $existing = Capsule::table('tbltodolist')
            ->where('title', 'like', "%{$client->firstname}% {$client->lastname} n8n not renewed%")
            ->first();

        if (!$existing) {
           
            $result = localAPI('SendAdminEmail', [
                'messagename' => 'n8n Hosting Not Renewed',
                'mergefields' => [
                    'client_name'  => $client->firstname . ' ' . $client->lastname,
                    'client_email' => $client->email,
                    'serviceid'    => $service->id,
                    'duedate'      => $service->nextduedate,
                ],
            ], $adminUser);

            
            if ($result['result'] != 'success') {
                $mailMessage = "Client: {$client->firstname} {$client->lastname}\nEmail: {$client->email}\nService ID: {$service->id}\nDue: {$service->nextduedate}";
                mail($adminEmail, "❌ n8n Hosting Not Renewed", $mailMessage);
            }

           
            Capsule::table('tbltodolist')->insert([
                'date'        => date('Y-m-d'),
                'title'       => "{$client->firstname} {$client->lastname} n8n not renewed",
                'description' => "Check if you need to stop or delete their n8n container for service ID {$service->id}.",
                'status'      => 'Pending',
                'duedate'     => date('Y-m-d', strtotime('+1 day')),
            ]);
        }
    }
});