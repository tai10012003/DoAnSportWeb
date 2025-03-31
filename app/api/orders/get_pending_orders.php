<?php
require_once __DIR__ . '/../../config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Cập nhật truy vấn SQL để lấy các đơn hàng có trạng thái khác 'complete' và 'cancel'
    $sql = "SELECT don_hang.id, don_hang.tong_tien, don_hang.trang_thai, users.ho_ten
            FROM don_hang
            JOIN users ON don_hang.user_id = users.id
            WHERE don_hang.trang_thai NOT IN ('completed', 'cancelled')
            ORDER BY don_hang.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $orders]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>