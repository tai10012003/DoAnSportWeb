<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DonHang.php';
require_once __DIR__ . '/../../models/ChiTietDonHang.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thanh toán']);
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Giỏ hàng của bạn đang trống']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    $donHang = new DonHang($conn);
    $chiTietDonHang = new ChiTietDonHang($conn);

    // Tính tổng tiền
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $shipping = $subtotal >= 500000 ? 0 : 30000;
    $total = $subtotal + $shipping;

    // Tạo đơn hàng
    $donHang->user_id = $_SESSION['user_id'];
    $donHang->tong_tien = $total;
    $donHang->phi_van_chuyen = $shipping;
    $donHang->trang_thai = 'pending';
    $donHang->ghi_chu = $_POST['order_note'] ?? ''; // Nhận ghi chú
    $donHang->dia_chi = $_POST['receiver_address'] ?? ''; // Nhận địa chỉ
    $payment_method = $_POST['payment_method'] ?? 'cod'; // Mặc định là COD
    $donHang->payment_method = $payment_method;
    
    if ($donHang->create()) {
        // Lấy ID đơn hàng vừa tạo
        $don_hang_id = $conn->lastInsertId();

        // Lưu chi tiết đơn hàng
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $chiTietDonHang->don_hang_id = $don_hang_id;
            $chiTietDonHang->san_pham_id = $product_id;
            $chiTietDonHang->so_luong = $item['quantity'];
            $chiTietDonHang->gia = $item['price'];
            $chiTietDonHang->create();

            // Update product stock in the san_pham table
            $update_stock_sql = "UPDATE san_pham SET so_luong = so_luong - :quantity WHERE id = :product_id";
            $stmt = $conn->prepare($update_stock_sql);
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Xóa giỏ hàng sau khi đặt hàng thành công
        unset($_SESSION['cart']);

        echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi đặt hàng']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}

?>