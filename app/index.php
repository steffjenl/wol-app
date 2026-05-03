<?php

require_once __DIR__ . '/auth.php';
require_auth();

$csrfToken = csrf_token();

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WoL Manager &mdash; Dashboard</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="dashboard-body">

<nav class="navbar">
    <div class="navbar-brand">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="12" fill="#0d6efd" opacity=".2"/>
            <path fill="#0d6efd" d="M8 6h2v5H8V6Zm6 0h2v5h-2V6ZM4 13h16v1.5a6 6 0 0 1-6 6H10a6 6 0 0 1-6-6V13Z"/>
        </svg>
        <span>WoL Manager</span>
    </div>
    <a href="/logout.php" class="navbar-logout">Uitloggen</a>
</nav>

<main class="main-content">

    <div class="device-card">
        <div class="device-header">
            <div class="device-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24">
                    <rect width="24" height="15" x="0" y="2" rx="2" fill="#1e293b" stroke="#334155" stroke-width="1.5"/>
                    <rect width="18" height="11" x="3" y="4" rx="1" fill="#0f172a"/>
                    <rect width="8" height="1.5" x="8" y="18" rx=".75" fill="#334155"/>
                    <rect width="14" height="1" x="5" y="19.5" rx=".5" fill="#1e293b" stroke="#334155" stroke-width=".5"/>
                </svg>
            </div>
            <div class="device-info">
                <h2 class="device-name">Windows 11 PC</h2>
                <p class="device-meta">
                    <span class="device-detail"><?= htmlspecialchars(TARGET_IP) ?></span>
                    <span class="device-sep">&bull;</span>
                    <span class="device-detail"><?= htmlspecialchars(TARGET_MAC) ?></span>
                </p>
            </div>
            <div class="status-badge" id="statusBadge">
                <span class="status-dot" id="statusDot"></span>
                <span id="statusText">Controleren&hellip;</span>
            </div>
        </div>

        <div class="device-actions">
            <button class="btn-wake" id="wakeBtn" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 14.93V16a1 1 0 0 0-2 0v.93A8 8 0 0 1 4.07 12H5a1 1 0 0 0 0-2h-.93A8 8 0 0 1 11 4.07V5a1 1 0 0 0 2 0v-.93A8 8 0 0 1 19.93 11H19a1 1 0 0 0 0 2h.93A8 8 0 0 1 13 16.93Z"/>
                </svg>
                Wake Computer
            </button>

            <a class="btn-rdp" id="rdpBtn" href="rdp://<?= htmlspecialchars(TARGET_IP) ?>" style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M20 3H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h6v2H8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2h-2v-2h6a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2Zm0 13H4V5h16v11Z"/>
                    <path fill="currentColor" d="M10.5 13.5 9 12l3.5-3.5L16 12l-1.5 1.5-1-1V15h-2v-2.5l-1 1Z"/>
                </svg>
                Verbinden via RDP
            </a>
        </div>

        <div class="last-action" id="lastAction"></div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-label">Poort RDP</div>
            <div class="info-value">3389</div>
        </div>
        <div class="info-card">
            <div class="info-label">Broadcast</div>
            <div class="info-value"><?= htmlspecialchars(BROADCAST_IP) ?></div>
        </div>
        <div class="info-card">
            <div class="info-label">Status interval</div>
            <div class="info-value">10 sec</div>
        </div>
    </div>

</main>

<div class="toast-container" id="toastContainer"></div>

<script>
    window.CSRF_TOKEN = <?= json_encode($csrfToken) ?>;
</script>
<script src="/assets/js/app.js"></script>

</body>
</html>
