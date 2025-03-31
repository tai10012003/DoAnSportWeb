<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (isset($_GET['id'])) {
    $db = new Database();
    $conn = $db->getConnection();
    
    $order_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Kiểm tra đơn hàng có thuộc về user không
        $check_query = "SELECT id FROM don_hang WHERE id = :order_id AND user_id = :user_id";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bindParam(':order_id', $order_id);
        $check_stmt->bindParam(':user_id', $user_id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            // Lấy chi tiết đơn hàng
            $query = "SELECT ct.*, sp.ten_sp, sp.hinh_anh, sp.gia, sp.id as san_pham_id,
                      o.ma_don_hang, o.tong_tien, o.created_at  
                      FROM chi_tiet_don_hang ct 
                      JOIN san_pham sp ON ct.san_pham_id = sp.id
                      JOIN don_hang o ON ct.don_hang_id = o.id 
                      WHERE ct.don_hang_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'details' => $details
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Missing order ID'
    ]);
}
