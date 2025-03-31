<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Kiểm tra nếu có dữ liệu POST từ form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    // Kết nối database
    $db = new Database();
    $conn = $db->getConnection();

    // Truy vấn lấy thông tin sản phẩm
    $sql = "SELECT * FROM san_pham WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Kiểm tra giỏ hàng đã tồn tại chưa
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Thêm sản phẩm vào giỏ hàng
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['ten_sp'],
                'image' => $product['hinh_anh'],
                'price' => $product['gia_khuyen_mai'] > 0 ? $product['gia_khuyen_mai'] : $product['gia'],
                'quantity' => $quantity
            ];
        }
    }

    // Chuyển hướng đến trang giỏ hàng
    header("Location: /WebbandoTT/app/views/cart.php");
    exit();
} else {
    echo "Phương thức không hợp lệ!";
}
?>

<script src="js/main.js"></script>