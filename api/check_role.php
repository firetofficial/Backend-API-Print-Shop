<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$session_token = $data['session_token'] ?? '';
$requested_feature = $data['feature'] ?? '';

if (empty($session_token) || empty($requested_feature)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu yêu cầu']);
    exit;
}

$stmt = $pdo->prepare("SELECT permissions FROM users WHERE session_token = ?");
$stmt->execute([$session_token]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Token không hợp lệ']);
    exit;
}

$permissions = json_decode($user['permissions'], true);

if (isset($permissions['all']) && $permissions['all'] === true) {
    echo json_encode(['success' => true, 'access_granted' => true]);
    exit;
}

if (isset($permissions[$requested_feature]) && $permissions[$requested_feature] === true) {
    echo json_encode(['success' => true, 'access_granted' => true]);
} else {
    echo json_encode(['success' => false, 'access_granted' => false, 'message' => 'Không có quyền truy cập']);
}
?>
