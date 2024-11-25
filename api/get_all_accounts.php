<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

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

try {
    $stmt = $pdo->query('SELECT id, username, permissions, created_at FROM users');
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $accounts
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
