<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

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

// Lấy dữ liệu từ body
$data = json_decode(file_get_contents('php://input'), true);
$session_token = $data['session_token'] ?? '';
$roleCheck = checkUserRole($session_token, 'write');
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST': // Thêm sản phẩm
        $category_id = $data['category_id'] ?? 0;
        $product_name = $data['product_name'] ?? '';
        $rules = $data['rules'] ?? '';
        $notes = $data['notes'] ?? '';
        $multiple_pricing = $data['nhieuquycach'] ?? false;
        $pricing = $data['pricing'] ?? []; // Array của số lượng, giá tiền, ghi chú

        if (empty($category_id) || empty($product_name)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        // Thêm sản phẩm
        $stmt = $pdo->prepare("INSERT INTO products (category_id, product_name, rules, notes, multiple_pricing) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$category_id, $product_name, $rules, $notes, $multiple_pricing]);
        $product_id = $pdo->lastInsertId();

        // Nếu nhiều quy cách, thêm vào `product_pricing`
        if ($multiple_pricing && !empty($pricing)) {
            $stmt = $pdo->prepare("INSERT INTO product_pricing (product_id, quantity, price, note) VALUES (?, ?, ?, ?)");
            foreach ($pricing as $price) {
                $stmt->execute([$product_id, $price['quantity'], $price['price'], $price['note'] ?? '']);
            }
        }

        echo json_encode(['success' => true, 'message' => 'Sản phẩm được thêm thành công']);
        break;

    case 'PUT': // Sửa sản phẩm
        $id = $data['id'] ?? 0;
        $category_id = $data['category_id'] ?? 0;
        $product_name = $data['product_name'] ?? '';
        $rules = $data['rules'] ?? '';
        $notes = $data['notes'] ?? '';
        $multiple_pricing = $data['nhieuquycach'] ?? false;
        $pricing = $data['pricing'] ?? [];

        if (empty($id) || empty($category_id) || empty($product_name)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        // Cập nhật sản phẩm
        $stmt = $pdo->prepare("UPDATE products SET category_id = ?, product_name = ?, rules = ?, notes = ?, multiple_pricing = ? WHERE id = ?");
        $stmt->execute([$category_id, $product_name, $rules, $notes, $multiple_pricing, $id]);

        // Nếu nhiều quy cách, xóa và thêm mới trong `product_pricing`
        if ($multiple_pricing) {
            $stmt = $pdo->prepare("DELETE FROM product_pricing WHERE product_id = ?");
            $stmt->execute([$id]);

            $stmt = $pdo->prepare("INSERT INTO product_pricing (product_id, quantity, price, note) VALUES (?, ?, ?, ?)");
            foreach ($pricing as $price) {
                $stmt->execute([$id, $price['quantity'], $price['price'], $price['note'] ?? '']);
            }
        }

        echo json_encode(['success' => true, 'message' => 'Sản phẩm được cập nhật thành công']);
        break;

    case 'DELETE': // Xóa sản phẩm
        $id = $data['id'] ?? 0;

        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Sản phẩm được xóa thành công']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
