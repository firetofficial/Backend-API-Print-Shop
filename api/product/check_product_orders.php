<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/tamphuc/config/database.php';

// Function to check user role
function checkUserRole($session_token, $feature) {
    global $pdo;
    if (empty($session_token) || empty($feature)) {
        return ['success' => false, 'message' => 'Missing required data'];
    }
    $stmt = $pdo->prepare("SELECT permissions FROM users WHERE session_token = ?");
    $stmt->execute([$session_token]);
    $user = $stmt->fetch();
    if (!$user) return ['success' => false, 'message' => 'Invalid session token'];
    $permissions = json_decode($user['permissions'], true);
    return isset($permissions[$feature]) && $permissions[$feature] ? 
           ['success' => true, 'access_granted' => true] : 
           ['success' => false, 'message' => 'Access denied'];
}

// Get data from request body
$data = json_decode(file_get_contents('php://input'), true);
$session_token = $data['session_token'] ?? '';
$product_name = $data['product_name'] ?? '';

if (empty($product_name)) {
    echo json_encode(['success' => false, 'message' => 'Product name is required']);
    exit;
}

// Check user permission
$roleCheck = checkUserRole($session_token, 'manage_products');
if (!$roleCheck['success'] || !$roleCheck['access_granted']) {
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

try {
    // Check if the product name exists in the products table
    $stmt = $pdo->prepare("SELECT id FROM products WHERE LOWER(product_name) = LOWER(?)");
    $stmt->execute([$product_name]);
    $product = $stmt->fetch();

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'No product found with this name']);
        exit;
    }

    $product_id = $product['id'];

    // Search orders containing the product ID in product_details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE JSON_CONTAINS(product_details, ?)");
    $stmt->execute([json_encode(['product_code' => $product_id])]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($orders)) {
        echo json_encode(['success' => false, 'message' => 'No orders found for this product']);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Orders found for the product',
            'orders' => $orders
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error', 'error' => $e->getMessage()]);
}
?>
