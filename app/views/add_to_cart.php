<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'includes/db_connect.php';
    
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($product_id > 0) {
        $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $quantity
                ];
            }
            
            $total_items = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total_items += $item['quantity'];
            }
            
            $stmt->close();
            $conn->close();
            
            echo json_encode([
                'success' => true,
                'message' => 'Đã thêm vào giỏ hàng',
                'cart_count' => $total_items
            ]);
            exit;
        }
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra'
    ]);
}
?>
<script src="js/main.js"></script>