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
    case 'POST': // Thêm danh mục
        $category_name = $data['category_name'] ?? '';
        $description = $data['description'] ?? '';
        
        if (empty($category_name)) {
            echo json_encode(['success' => false, 'message' => 'Tên danh mục không được để trống']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
        $stmt->execute([$category_name, $description]);
        echo json_encode(['success' => true, 'message' => 'Danh mục được thêm thành công']);
        break;

    case 'PUT': // Sửa danh mục
        $id = $data['id'] ?? 0;
        $category_name = $data['category_name'] ?? '';
        $description = $data['description'] ?? '';
        
        if (empty($id) || empty($category_name)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE categories SET category_name = ?, description = ? WHERE id = ?");
        $stmt->execute([$category_name, $description, $id]);
        echo json_encode(['success' => true, 'message' => 'Danh mục được cập nhật thành công']);
        break;

    case 'DELETE': // Xóa danh mục
        $id = $data['id'] ?? 0;
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Danh mục được xóa thành công']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
