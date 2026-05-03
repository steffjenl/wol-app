<?php

require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_authenticated(): bool {
    if (empty($_SESSION['user']) || empty($_SESSION['last_activity'])) {
        return false;
    }
    if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        session_destroy();
        return false;
    }
    $_SESSION['last_activity'] = time();
    return true;
}

function require_auth(): void {
    if (!is_authenticated()) {
        header('Location: /login.php');
        exit;
    }
}

function attempt_login(string $username, string $password): bool {
    if ($username !== APP_USERNAME) {
        return false;
    }
    if (!password_verify($password, APP_PASSWORD_HASH)) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['user'] = $username;
    $_SESSION['last_activity'] = time();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return true;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
