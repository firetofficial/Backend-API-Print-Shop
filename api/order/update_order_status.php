<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tamphuc/config/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$session_token = $data['session_token'] ?? '';
$order_id = $data['order_id'] ?? '';
$order_status = $data['order_status'] ?? '';

function checkUserRole($session_token, $feature) {
    global $pdo;
    if (empty($session_token) || empty($feature)) {
        return ['success' => false, 'message' => 'Thiếu dữ liệu yêu cầu'];
    }
    $stmt = $pdo->prepare("SELECT permissions FROM users WHERE session_token = ?");
    $stmt->execute([$session_token]);
    $user = $stmt->fetch();
    if (!$user) return ['success' => false, 'message' => 'Token không hợp lệ'];
    $permissions = json_decode($user['permissions'], true);
    return isset($permissions[$feature]) && $permissions[$feature] ? 
           ['success' => true, 'access_granted' => true] : 
           ['success' => false, 'message' => 'Không có quyền truy cập'];
}

$roleCheck = checkUserRole($session_token, 'write');
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}

// Cập nhật trạng thái đơn hàng
$sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_status, $order_id]);

echo json_encode(['success' => true, 'message' => 'Trạng thái đơn hàng đã được cập nhật']);
?>