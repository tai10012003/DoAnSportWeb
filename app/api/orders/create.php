<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DonHang.php';









try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $donHang = new DonHang($db);

    // Validate bắt buộc
    if (empty($_POST['user_id']) || empty($_POST['tong_tien']) || empty($_POST['dia_chi'])) {
        throw new Exception('Vui lòng nhập đầy đủ thông tin bắt buộc (user_id, tổng tiền, địa chỉ)');
    }

    // Set dữ liệu
    $donHang->user_id = intval($_POST['user_id']);
    $donHang->tong_tien = floatval($_POST['tong_tien']);
    $donHang->phi_van_chuyen = isset($_POST['phi_van_chuyen']) ? floatval($_POST['phi_van_chuyen']) : 0;
    $donHang->trang_thai = isset($_POST['trang_thai']) ? trim($_POST['trang_thai']) : 'pending';
    $donHang->ghi_chu = $_POST['ghi_chu'] ?? '';
    $donHang->payment_method = $_POST['payment_method'] ?? 'COD';
    $donHang->dia_chi = trim($_POST['dia_chi']);

    if ($donHang->create()) {
        echo json_encode([
            'success' => true,
            'message' => 'Tạo đơn hàng thành công',
            'order_code' => $donHang->ma_don_hang
        ]);
    } else {
        throw new Exception('Không thể tạo đơn hàng');
    }

} catch (Exception $e) {
    error_log("Error in order creation: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}