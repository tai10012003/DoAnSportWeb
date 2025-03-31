<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DonHang.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (empty($_POST['id'])) {
        throw new Exception('Thiếu ID đơn hàng');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $donHang = new DonHang($db);

    $existingOrder = $donHang->getOrderById($_POST['id']);
    if (!$existingOrder) {
        throw new Exception('Không tìm thấy đơn hàng');
    }

    // Gán dữ liệu đã lọc vào model
    $donHang->id = intval($_POST['id']);
    $donHang->ma_don_hang = $_POST['ma_don_hang'] ?? $existingOrder['ma_don_hang'];
    $donHang->user_id = intval($_POST['user_id'] ?? $existingOrder['user_id']);
    $donHang->tong_tien = floatval($_POST['tong_tien'] ?? $existingOrder['tong_tien']);
    $donHang->phi_van_chuyen = floatval($_POST['phi_van_chuyen'] ?? $existingOrder['phi_van_chuyen']);
    $donHang->trang_thai = $_POST['trang_thai'] ?? $existingOrder['trang_thai'];
    $donHang->ghi_chu = $_POST['ghi_chu'] ?? $existingOrder['ghi_chu'];
    $donHang->payment_method = $_POST['payment_method'] ?? $existingOrder['payment_method'];
    $donHang->dia_chi = $_POST['dia_chi'] ?? $existingOrder['dia_chi'];

    if ($donHang->update()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật đơn hàng thành công'
        ]);
    } else {
        throw new Exception('Không thể cập nhật đơn hàng');
    }
} catch (Exception $e) {
    error_log("Error in order update: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}