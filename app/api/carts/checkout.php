<?php
ob_start();
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DonHang.php';
require_once __DIR__ . '/../../models/ChiTietDonHang.php';
require_once __DIR__ . '/../../helpers/MailHelper.php';

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
    
    // Get user email
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userEmail = $stmt->fetchColumn();
    
    if (!$userEmail) {
        throw new Exception('Không tìm thấy email người dùng');
    }
    
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
        $orderItems = [];

        // Xử lý thanh toán MoMo nếu được chọn
        if ($payment_method === 'momo') {
            require_once __DIR__ . '/../../helpers/MomoHelper.php';
            $momo = new MomoHelper();
            
            $orderInfo = "Thanh toan don hang #" . $don_hang_id;
            $momoResponse = $momo->createPayment(
                $don_hang_id,
                $total,
                $orderInfo
            );

            if ($momoResponse['resultCode'] == 0) {
                echo json_encode([
                    'success' => true,
                    'payment_type' => 'momo',
                    'payment_url' => $momoResponse['payUrl']
                ]);
                exit;
            } else {
                throw new Exception('Lỗi tạo thanh toán MoMo: ' . $momoResponse['message']);
            }
        }

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

            // Collect order items for email
            $orderItems[] = [
                'ten_sp' => $item['name'],
                'so_luong' => $item['quantity'],
                'gia' => $item['price']
            ];
        }

        // Send confirmation email
        try {
            if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
                throw new Exception('PHPMailer không được cài đặt đúng cách');
            }
            
            $mailer = new MailHelper();
            $mailSent = $mailer->sendOrderConfirmation(
                $userEmail,
                [
                    'receiver_name' => $_POST['receiver_name'],
                    'receiver_phone' => $_POST['receiver_phone'],
                    'receiver_address' => $_POST['receiver_address']
                ],
                $orderItems,
                $total,
                $_POST['payment_method'],
                date('YmdHi') . $don_hang_id
            );
        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            $mailSent = false;
        }

        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Xóa giỏ hàng sau khi đặt hàng thành công
        unset($_SESSION['cart']);

        echo json_encode([
            'success' => true,
            'message' => 'Đặt hàng thành công!' . ($mailSent ? ' Thông báo đặt hàng đã gửi Email của bạn.' : ' (Không thể gửi email xác nhận)'),
            'order_id' => $don_hang_id
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Clear all output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    echo json_encode(['success' => false, 'message' => 'Lỗi khi đặt hàng']);
    
} catch (Exception $e) {
    error_log("Checkout Error: " . $e->getMessage());
    while (ob_get_level()) {
        ob_end_clean();
    }
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
ob_end_flush();

?>