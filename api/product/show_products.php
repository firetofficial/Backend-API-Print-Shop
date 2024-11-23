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
$roleCheck = checkUserRole($session_token, 'write');
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}

// Lấy toàn bộ sản phẩm
$stmt = $pdo->prepare("SELECT p.id, p.product_name, p.rules, p.notes, p.multiple_pricing, c.category_name 
                       FROM products p
                       JOIN categories c ON p.category_id = c.id");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy giá của từng sản phẩm
foreach ($products as &$product) {
    $stmt = $pdo->prepare("SELECT quantity, price, note FROM product_pricing WHERE product_id = ?");
    $stmt->execute([$product['id']]);
    $product['pricing'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hiển thị kết quả
echo json_encode(['success' => true, 'products' => $products]);
?>
