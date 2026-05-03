<?php

require_once __DIR__ . '/auth.php';

if (is_authenticated()) {
    header('Location: /');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (attempt_login($username, $password)) {
        header('Location: /');
        exit;
    }

    $error = 'Ongeldige gebruikersnaam of wachtwoord.';
    sleep(1);
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WoL Manager &mdash; Login</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="login-body">

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="12" fill="#0d6efd" opacity=".15"/>
                <path fill="#0d6efd" d="M8 6h2v5H8V6Zm6 0h2v5h-2V6ZM4 13h16v1.5a6 6 0 0 1-6 6H10a6 6 0 0 1-6-6V13Z"/>
            </svg>
        </div>
        <h1 class="login-title">WoL Manager</h1>
        <p class="login-subtitle">Wake-on-LAN &amp; Remote Desktop</p>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/login.php" autocomplete="off">
            <div class="form-group">
                <label for="username">Gebruikersnaam</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-input"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    autofocus
                    required
                >
            </div>
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    required
                >
            </div>
            <button type="submit" class="btn-primary btn-full">Inloggen</button>
        </form>
    </div>
</div>

</body>
</html>
