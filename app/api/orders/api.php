<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DonHang.php';

try {
    $database = Database::getInstance();
    $db = $database->getConnection();
    $donHang = new DonHang($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'user_id' => $_POST['user_id'],
            'tong_tien' => $_POST['tong_tien'],
            'phi_van_chuyen' => $_POST['phi_van_chuyen'] ?? 0,
            'ghi_chu' => $_POST['ghi_chu'] ?? '',
            'trang_thai' => 'Chờ xác nhận'
        ];

        foreach ($donHang as $property => $value) {
            if (isset($data[$property])) {
                $donHang->$property = $data[$property];
            }
        }

        if ($donHang->create()) {
            echo json_encode([
                'success' => true,
                'message' => 'Đơn hàng đã được tạo thành công'
            ]);
        } else {
            throw new Exception('Không thể tạo đơn hàng');
        }
    } else {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>