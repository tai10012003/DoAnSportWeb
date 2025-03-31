<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này']);
    exit;
}

if (!isset($_POST['order_id']) || !isset($_POST['current_status'])) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $orderId = $_POST['order_id'];
    $currentStatus = $_POST['current_status'];

    // Xác định trạng thái mới dựa trên trạng thái hiện tại
    $newStatus = '';
    switch ($currentStatus) {
        case 'pending':
            $newStatus = 'processing';
            break;
        case 'processing':
            $newStatus = 'shipped';
            break;
        case 'shipped':
            $newStatus = 'completed';
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
            exit;
    }

    // Cập nhật trạng thái đơn hàng
    $updateStatusSql = "UPDATE don_hang SET trang_thai = :new_status WHERE id = :order_id";
    $stmt = $conn->prepare($updateStatusSql);
    $stmt->bindParam(':new_status', $newStatus);
    $stmt->bindParam(':order_id', $orderId);
    $stmt->execute();

    // Nếu trạng thái là 'complete', loại bỏ đơn hàng khỏi bảng
    if ($newStatus === 'completed') {
        $deleteOrderSql = "DELETE FROM don_hang WHERE id = :order_id";
        $stmt = $conn->prepare($deleteOrderSql);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
    }

    echo json_encode(['success' => true, 'message' => 'Trạng thái đơn hàng đã được cập nhật']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>