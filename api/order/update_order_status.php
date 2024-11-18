<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tamphuc/config/database.php';

$data = json_decode(file_get_contents('php://input'), true);

// Lấy dữ liệu từ request
$session_token = $data['session_token'] ?? '';
$order_id = $data['order_id'] ?? '';
$order_status = $data['order_status'] ?? '';
$printing_company_id = $data['printing_company_id'] ?? '';

// Kiểm tra quyền người dùng
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

// Kiểm tra dữ liệu đầu vào
if (empty($order_id) || empty($order_status) || empty($printing_company_id)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu yêu cầu']);
    exit;
}

// Cập nhật trạng thái đơn hàng và id nhà in
$sql = "UPDATE orders SET order_status = ?, printing_company_id = ? WHERE order_id = ?";
$stmt = $pdo->prepare($sql);
$updateResult = $stmt->execute([$order_status, $printing_company_id, $order_id]);

if ($updateResult) {
    echo json_encode(['success' => true, 'message' => 'Trạng thái đơn hàng và nhà in đã được cập nhật']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật đơn hàng']);
}
?>
