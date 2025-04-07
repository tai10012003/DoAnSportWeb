<?php
session_start();
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['product_id']) || !isset($data['quantity'])) {
        throw new Exception('Missing required parameters');
    }

    $product_id = $data['product_id'];
    $quantity = intval($data['quantity']);

    if ($quantity < 1 || $quantity > 10) {
        throw new Exception('Invalid quantity');
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        
        // Tính toán lại tổng tiền
        $itemTotal = $_SESSION['cart'][$product_id]['price'] * $quantity;
        $subtotal = 0;
        $cartCount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $cartCount += $item['quantity'];
        }
        $shipping = $subtotal >= 500000 ? 0 : 30000;
        $total = $subtotal + $shipping;

        echo json_encode([
            'success' => true,
            'itemTotal' => number_format($itemTotal) . '₫',
            'subtotal' => number_format($subtotal) . '₫',
            'total' => number_format($total) . '₫',
            'cartCount' => $cartCount
        ]);
    } else {
        throw new Exception('Product not found in cart');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
