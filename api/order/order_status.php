<?php
// CREATE TABLE order_status (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     status_name VARCHAR(255) NOT NULL,
//     description TEXT,
//     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
//     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// );


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
    case 'POST': // Thêm trạng thái
        $status_name = $data['status_name'] ?? '';
        $description = $data['description'] ?? '';
        
        if (empty($status_name)) {
            echo json_encode(['success' => false, 'message' => 'Tên trạng thái không được để trống']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO order_status (status_name, description) VALUES (?, ?)");
        $stmt->execute([$status_name, $description]);
        echo json_encode(['success' => true, 'message' => 'Trạng thái được thêm thành công']);
        break;

    case 'PUT': // Sửa trạng thái
        $id = $data['id'] ?? 0;
        $status_name = $data['status_name'] ?? '';
        $description = $data['description'] ?? '';
        
        if (empty($id) || empty($status_name)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE order_status SET status_name = ?, description = ? WHERE id = ?");
        $stmt->execute([$status_name, $description, $id]);
        echo json_encode(['success' => true, 'message' => 'Trạng thái được cập nhật thành công']);
        break;

    case 'DELETE': // Xóa trạng thái
        $id = $data['id'] ?? 0;
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM order_status WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Trạng thái được xóa thành công']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
