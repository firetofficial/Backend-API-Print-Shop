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
$feature = "read";
$roleCheck = checkUserRole($session_token, $feature);
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}
$sql = "SELECT * FROM customers";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$customers = $stmt->fetchAll();
echo json_encode(['success' => true, 'data' => $customers]);
?>
