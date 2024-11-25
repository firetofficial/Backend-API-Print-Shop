<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tamphuc/config/database.php';

// Lấy dữ liệu từ request
$data = json_decode(file_get_contents('php://input'), true);
$session_token = $data['session_token'] ?? '';
$page = isset($data['page']) && is_numeric($data['page']) ? (int)$data['page'] : 1; // Trang mặc định là 1
$limit = isset($data['limit']) && is_numeric($data['limit']) ? (int)$data['limit'] : 10; // Số bản ghi mặc định là 10

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

// Kiểm tra quyền 'read'
$roleCheck = checkUserRole($session_token, 'read');
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập']);
    exit;
}

// Tính toán offset và giới hạn
$offset = ($page - 1) * $limit;

// Truy vấn tổng số đơn hàng để tính tổng số trang
$sql_total = "SELECT COUNT(*) AS total FROM orders";
$stmt_total = $pdo->query($sql_total);
$total_orders = $stmt_total->fetch()['total'];
$total_pages = ceil($total_orders / $limit);

// Lấy danh sách đơn hàng theo phân trang
$sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kết quả trả về
if ($orders) {
    echo json_encode([
        'success' => true,
        'data' => $orders,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_orders' => $total_orders
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không có đơn hàng nào']);
}
?>
