<?php
require_once __DIR__ . '/../../config/database.php';

header("Content-Type: application/json; charset=UTF-8");

$inputData = file_get_contents('php://input');
$result = json_decode($inputData, true);

try {
    if ($result['resultCode'] == 0) {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Cập nhật trạng thái đơn hàng
        $sql = "UPDATE don_hang SET 
                trang_thai = 'paid',
                ma_giao_dich = :trans_id,
                cap_nhat = NOW()
                WHERE id = :order_id";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':trans_id' => $result['transId'],
            ':order_id' => $result['orderId']
        ]);
        
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Thanh toán thất bại: ' . $result['message']);
    }
} catch (Exception $e) {
    error_log("MoMo IPN Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
