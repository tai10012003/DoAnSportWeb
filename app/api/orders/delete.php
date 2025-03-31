<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DonHang.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($_GET['id'])) {
        throw new Exception('Thiếu ID đơn hàng');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $donHang = new DonHang($db);

    // Kiểm tra đơn hàng có tồn tại không
    $order = $donHang->getOrderById($_GET['id']);
    if (!$order) {
        throw new Exception('Không tìm thấy đơn hàng');
    }

    $donHang->id = $_GET['id'];

    if ($donHang->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Xóa đơn hàng thành công'
        ]);
    } else {
        throw new Exception('Không thể xóa đơn hàng');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}