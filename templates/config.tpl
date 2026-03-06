{* modules/addons/n8nnotifier/templates/admin.tpl *}

<style>
  
    body {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        background-color: #f0f2f5;
        color: #333;
        line-height: 1.6;
        padding: 2rem;
        transition: background-color 0.3s ease;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease;
    }

   
    .header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .header h2 {
        font-size: 2.5rem;
        color: #e74c3c;
        margin: 0;
        font-weight: 700;
        letter-spacing: -1px;
    }

    .header-icon {
        font-size: 2rem;
        color: #e74c3c;
    }

 
    .alert {
        padding: 1.2rem;
        margin-bottom: 2rem;
        border-radius: 8px;
        border: 1px solid transparent;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-success .alert-icon {
        color: #28a745;
    }

    .alert-info {
        background-color: #cce5ff;
        border-color: #b8daff;
        color: #004085;
    }

    .alert-info .alert-icon {
        color: #007bff;
    }

    .alert-icon {
        font-size: 1.5rem;
    }


    .no-clients {
        text-align: center;
        padding: 4rem 2rem;
        background-color: #fdfdfd;
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        color: #777;
    }

    .no-clients h3 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        color: #555;
    }

    .no-clients p {
        margin-top: 0;
        font-size: 1.1rem;
    }


    .data-table-container {
        overflow-x: auto;
        margin-top: 2rem;
    }

    .client-card-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .client-card {
        background: #fafafa;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .client-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        padding: 0.5rem;
        border-bottom: 1px solid #eee;
    }

    .card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        color: #34495e;
    }

    .card-header .toggle-icon {
        font-size: 1.5rem;
        color: #7f8c8d;
        transition: transform 0.3s ease;
    }

    .card-content {
        display: none;
        padding-top: 1rem;
        animation: fadeIn 0.5s ease-in-out;
    }

    .card-content.show {
        display: block;
    }

    .card-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        padding: 1rem 0;
    }

    .detail-item {
        background-color: #f1f3f5;
        padding: 10px;
        border-radius: 6px;
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-weight: 600;
        color: #555;
        font-size: 0.9rem;
        margin-bottom: 4px;
    }

    .detail-value {
        font-weight: 400;
        color: #333;
        font-size: 1rem;
    }

 
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        text-align: center;
        cursor: pointer;
        border: none;
    }

    .btn-primary {
        background-color: #3498db;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-danger {
        background-color: #e74c3c;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

  
    @media (max-width: 768px) {
        body {
            padding: 1rem;
        }

        .container {
            padding: 1.5rem;
        }

        .header h2 {
            font-size: 2rem;
        }

        .card-details {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .btn {
            width: 100%;
        }
    }

  
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Global Utility Classes */
    .mb-2 { margin-bottom: 1rem; }
    .mb-3 { margin-bottom: 1.5rem; }
</style>

<div class="container">
    <div class="header">
        <h2 class="header-icon">🚨</h2>
        <h2>Expired n8n Clients</h2>
    </div>

    {if $message}
        <div class="alert alert-success">
            <span class="alert-icon">✅</span>
            <p>{$message}</p>
        </div>
    {/if}

    {if !$expiredServices}
        <div class="no-clients">
            <h3>No Expired Clients Found</h3>
            <p>All clients with n8n services are currently active. Great job!</p>
        </div>
    {else}
        <div class="client-card-list">
            {foreach from=$expiredServices item=service}
                <div class="client-card">
                    <div class="card-header" onclick="toggleCard(this)">
                        <h4>{$service.client_firstname} {$service.client_lastname}</h4>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="card-content">
                        <div class="card-details">
                            <div class="detail-item">
                                <span class="detail-label">Client Name</span>
                                <span class="detail-value">{$service.client_firstname} {$service.client_lastname}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email Address</span>
                                <span class="detail-value">{$service.client_email}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Service ID</span>
                                <span class="detail-value">{$service.id}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status</span>
                                <span class="detail-value">{$service.domainstatus}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Next Due Date</span>
                                <span class="detail-value">{$service.nextduedate}</span>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <a href="clientssummary.php?userid={$service.userid}" target="_blank" class="btn btn-primary">View Profile</a>
                            <a href="addonmodules.php?module=n8nnotifier&sendmail={$service.userid}" class="btn btn-danger">Send Mail</a>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    {/if}
</div>

<script>
    function toggleCard(header) {
        const content = header.nextElementSibling;
        const toggleIcon = header.querySelector('.toggle-icon');
        
        
        const isVisible = content.classList.contains('show');

        
        document.querySelectorAll('.card-content.show').forEach(openContent => {
            if (openContent !== content) {
                openContent.classList.remove('show');
                openContent.previousElementSibling.querySelector('.toggle-icon').textContent = '+';
            }
        });

        if (isVisible) {
            content.classList.remove('show');
            toggleIcon.textContent = '+';
        } else {
            content.classList.add('show');
            toggleIcon.textContent = '−';
        }
    }
</script>