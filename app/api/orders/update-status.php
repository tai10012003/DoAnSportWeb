<?php
header('Content-Type: application/json');
error_reporting(0);

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($data['id']) || !isset($data['trang_thai'])) {
        throw new Exception('Missing required parameters');
    }

    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../models/DonHang.php';

    $database = Database::getInstance();
    $db = $database->getConnection();
    $donHang = new DonHang($db);

    $orderId = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($data['trang_thai'], FILTER_SANITIZE_STRING);

    $success = $donHang->updateStatus($orderId, $status);

    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Cập nhật trạng thái thành công' : 'Không thể cập nhật trạng thái'
    ]);

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
