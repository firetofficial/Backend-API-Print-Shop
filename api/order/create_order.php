<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tamphuc/config/database.php';

// Kiểm tra quyền truy cập của người dùng
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

// Nhận dữ liệu JSON từ request
$data = json_decode(file_get_contents('php://input'), true);

// Lấy dữ liệu từ body
$session_token = $data['session_token'] ?? '';
$customer_id = $data['customer_id'] ?? '';
$recipient_name = $data['recipient_name'] ?? '';
$recipient_phone = $data['recipient_phone'] ?? '';
$delivery_address = $data['delivery_address'] ?? '';
$order_date = date('Y-m-d H:i:s');  // Ngày tạo đơn hàng
$order_status = $data['order_status'] ?? 1;  // Mặc định là "Đang báo giá"
$notes = $data['notes'] ?? '';
$product_details = json_encode($data['product_details'] ?? []);
$processing_employee_id = $data['processing_employee_id'] ?? null;
$design_confirm_employee_id = $data['design_confirm_employee_id'] ?? null;
$estimated_delivery_date = $data['estimated_delivery_date'] ?? null;

// Kiểm tra quyền
$roleCheck = checkUserRole($session_token, 'write');
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}

// Kiểm tra dữ liệu bắt buộc
if (empty($customer_id) || empty($recipient_name) || empty($recipient_phone) || empty($delivery_address) 
    || empty($processing_employee_id) || empty($design_confirm_employee_id) || empty($estimated_delivery_date)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
    exit;
}

// Lưu đơn hàng vào cơ sở dữ liệu
$sql = "INSERT INTO orders (
            customer_id, 
            recipient_name, 
            recipient_phone, 
            delivery_address, 
            order_date, 
            order_status, 
            notes, 
            product_details, 
            processing_employee_id, 
            design_confirm_employee_id, 
            estimated_delivery_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        $customer_id, 
        $recipient_name, 
        $recipient_phone, 
        $delivery_address, 
        $order_date, 
        $order_status, 
        $notes, 
        $product_details, 
        $processing_employee_id, 
        $design_confirm_employee_id, 
        $estimated_delivery_date
    ]);
    echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được tạo thành công']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi tạo đơn hàng', 'error' => $e->getMessage()]);
}
?>
