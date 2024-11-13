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
$customer_name = $data['customer_name'] ?? '';
$phone = $data['phone'] ?? '';
$email = $data['email'] ?? '';
$birth_year = $data['birth_year'] ?? '';
$gender = $data['gender'] ?? '';
$note = $data['note'] ?? '';
$delivery_address = $data['delivery_address'] ?? '';
$company_name = $data['company_name'] ?? '';
$tax_code = $data['tax_code'] ?? '';
$company_email = $data['company_email'] ?? '';
$city = $data['city'] ?? '';
$district = $data['district'] ?? '';
$ward = $data['ward'] ?? '';
$address = $data['address'] ?? '';

$feature = "write";

$roleCheck = checkUserRole($session_token, $feature);
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}
$sql = "INSERT INTO customers (customer_name, phone, email, birth_year, gender, note, delivery_address, company_name, tax_code, company_email, city, district, ward, address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$customer_name, $phone, $email, $birth_year, $gender, $note, $delivery_address, $company_name, $tax_code, $company_email, $city, $district, $ward, $address]);

echo json_encode(['success' => true, 'message' => 'Khách hàng đã được thêm thành công']);
?>
