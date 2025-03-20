<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        // Calculate new totals
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

echo json_encode([
    'success' => false,
    'message' => 'Có lỗi xảy ra'
]);
