<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tamphuc/config/database.php';

function checkUserRole($session_token, $feature) {
    global $pdo;

    if (empty($session_token) || empty($feature)) {
        return ['success' => false, 'message' => 'Thiếu dữ liệu yêu cầu'];
    }
    $stmt = $pdo->prepare("SELECT permissions FROM users WHERE session_token = ?");
    $stmt->execute([$session_token]);
    $user = $stmt->fetch();
    if (!$user) {
        return ['success' => false, 'message' => 'Token không hợp lệ'];
    }
    $permissions = json_decode($user['permissions'], true);
    if (isset($permissions[$feature]) && $permissions[$feature] === true) {
        return ['success' => true, 'access_granted' => true];
    }
    return ['success' => false, 'message' => 'Không có quyền truy cập'];
}
$data = json_decode(file_get_contents('php://input'), true);
$session_token = $data['session_token'] ?? '';
$customer_id = $data['customer_id'] ?? '';
$feature = "delete";
$roleCheck = checkUserRole($session_token, $feature);
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}

if (empty($customer_id)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu customer_id']);
    exit;
}

$sql = "DELETE FROM customers WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$customer_id]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'message' => 'Khách hàng đã được xóa thành công']);
} else {
    echo json_encode(['success' => false, 'message' => 'Khách hàng không tồn tại']);
}
?>
