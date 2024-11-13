<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Kiểm tra session token
$headers = getallheaders();
$session_token = $headers['Authorization'] ?? '';

if (empty($session_token)) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization required']);
    exit;
}

// Kiểm tra quyền admin
$stmt = $pdo->prepare('SELECT * FROM users WHERE session_token = ?');
$stmt->execute([$session_token]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin || !json_decode($admin['permissions'])->all) {
    http_response_code(403);
    echo json_encode(['error' => 'Admin privileges required']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';
$permissions = $data['permissions'] ?? [];

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Username and password are required']);
    exit;
}

try {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, password, permissions) VALUES (?, ?, ?)');
    $stmt->execute([$username, $hashed_password, json_encode($permissions)]);
    
    echo json_encode([
        'success' => true,
        'user_id' => $pdo->lastInsertId(),
        'message' => 'User registered successfully'
    ]);
} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode(['error' => 'Username already exists']);
}
