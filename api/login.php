<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Username and password are required']);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit;
}

// Tạo session token
$session_token = bin2hex(random_bytes(32));
$stmt = $pdo->prepare('UPDATE users SET session_token = ? WHERE id = ?');
$stmt->execute([$session_token, $user['id']]);

echo json_encode([
    'success' => true,
    'session_token' => $session_token,
    'permissions' => json_decode($user['permissions']),
    'user_id' => $user['id']
]);
