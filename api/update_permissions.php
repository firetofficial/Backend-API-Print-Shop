<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? '';
$permissions = $data['permissions'] ?? [];

if (empty($user_id) || empty($permissions)) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID and permissions are required']);
    exit;
}

try {
    $stmt = $pdo->prepare('UPDATE users SET permissions = ? WHERE id = ?');
    $stmt->execute([json_encode($permissions), $user_id]);
    
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Permissions updated successfully'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}