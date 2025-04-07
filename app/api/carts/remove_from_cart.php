<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]); // Xóa sản phẩm khỏi giỏ hàng

        // Tính lại tổng số sản phẩm và tổng tiền trong giỏ hàng
        $cart_count = 0;
        $cart_total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cart_count += $item['quantity'];
            $cart_total += $item['price'] * $item['quantity'];
        }

        echo json_encode([
            'success' => true,
            'cart_count' => $cart_count,
            'cart_total' => $cart_total,
            'message' => 'Đã xóa sản phẩm'
        ]);
        exit;
    }
}

// Trả về lỗi nếu không thể xóa sản phẩm
echo json_encode([
    'success' => false,
    'message' => 'Có lỗi xảy ra'
]);
?>