<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DonHang.php';

try {
    $database = Database::getInstance();
    $db = $database->getConnection();
    $donHang = new DonHang($db);

    $userId = $_GET['user_id'] ?? '';
    $status = $_GET['status'] ?? '';

    if (!empty($userId)) {
    } elseif (!empty($status)) {
        $orders = $donHang->getOrdersByStatus($status);
    } else {
        $orders = $donHang->getAllOrders();
    }

    echo json_encode([
        'success' => true,
        'orders' => $orders
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>