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
    if (!$user) return ['success' => false, 'message' => 'Token không hợp lệ'];
    $permissions = json_decode($user['permissions'], true);
    return isset($permissions[$feature]) && $permissions[$feature] ? 
           ['success' => true, 'access_granted' => true] : 
           ['success' => false, 'message' => 'Không có quyền truy cập'];
}

$data = json_decode(file_get_contents('php://input'), true);
$session_token = $data['session_token'] ?? '';
$printer_id = $data['printer_id'] ?? '';

$roleCheck = checkUserRole($session_token, 'delete');
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}

$sql = "DELETE FROM printing_companies WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$printer_id]);

echo json_encode(['success' => true, 'message' => 'Nhà in đã được xóa']);
?>
