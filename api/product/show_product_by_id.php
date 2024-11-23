<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET");

require_once $_SERVER['DOCUMENT_ROOT'] . '/tamphuc/config/database.php';

// Kiểm tra quyền truy cập
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

// Lấy dữ liệu từ query string
$session_token = $_GET['session_token'] ?? '';
$product_id = $_GET['product_id'] ?? 0;

if (empty($product_id)) {
    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
    exit;
}

$roleCheck = checkUserRole($session_token, 'write');
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}

// Lấy sản phẩm theo ID
$stmt = $pdo->prepare("SELECT p.id, p.product_name, p.rules, p.notes, p.multiple_pricing, c.category_name 
                       FROM products p
                       JOIN categories c ON p.category_id = c.id
                       WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
    exit;
}

// Lấy giá của sản phẩm
$stmt = $pdo->prepare("SELECT quantity, price, note FROM product_pricing WHERE product_id = ?");
$stmt->execute([$product['id']]);
$product['pricing'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hiển thị kết quả
echo json_encode(['success' => true, 'product' => $product]);
?>
