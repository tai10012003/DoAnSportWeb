<?php
session_start();
header('Content-Type: application/json');

try {
    // Xóa toàn bộ giỏ hàng
    $_SESSION['cart'] = array();
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã xóa tất cả sản phẩm khỏi giỏ hàng'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
