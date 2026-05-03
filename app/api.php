<?php

require_once __DIR__ . '/auth.php';

header('Content-Type: application/json');

if (!is_authenticated()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? ($_POST['action'] ?? '');
$token  = $input['csrf_token'] ?? ($_POST['csrf_token'] ?? '');

if (!verify_csrf($token)) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

match ($action) {
    'status' => handleStatus(),
    'wake'   => handleWake(),
    default  => badRequest(),
};

function handleStatus(): void {
    $online = isOnline();
    echo json_encode(['online' => $online]);
}

function handleWake(): void {
    $packet = buildMagicPacket(TARGET_MAC);
    $success = sendWol($packet);
    echo json_encode(['success' => $success]);
}

function isOnline(): bool {
    $conn = @fsockopen(TARGET_IP, 3389, $errno, $errstr, 1);
    if ($conn) {
        fclose($conn);
        return true;
    }
    return false;
}

function buildMagicPacket(string $mac): string {
    $mac   = str_replace([':', '-', '.'], '', strtolower($mac));
    $bytes = pack('H*', $mac);
    return str_repeat("\xFF", 6) . str_repeat($bytes, 16);
}

function sendWol(string $packet): bool {
    $sock = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    if ($sock === false) {
        return false;
    }
    socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1);
    $result = @socket_sendto($sock, $packet, strlen($packet), 0, BROADCAST_IP, 9);
    socket_close($sock);
    return $result !== false;
}

function badRequest(): void {
    http_response_code(400);
    echo json_encode(['error' => 'Unknown action']);
}
