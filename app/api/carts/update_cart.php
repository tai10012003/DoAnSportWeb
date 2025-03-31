<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]); // Xóa sản phẩm nếu số lượng <= 0
    } else {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }

    // Tính lại tổng tiền giỏ hàng
    $cart_count = 0;
    $subtotal = 0;

    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
        $subtotal += $item['price'] * $item['quantity'];
    }

    // Phí vận chuyển (Miễn phí nếu >= 500,000₫)
    $shipping = $subtotal >= 500000 ? 0 : ($subtotal > 0 ? 30000 : 0);
    $total = $subtotal + $shipping;

    echo json_encode([
        'success' => true,
        'cart_count' => $cart_count,
        'cart_total' => $subtotal,
        'shipping' => $shipping,
        'total' => $total,
        'item_total' => $subtotal, // Tổng tiền từng sản phẩm
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
exit;