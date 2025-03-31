<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/ThuongHieu.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $thuongHieu = new ThuongHieu($db);

    // Get existing brand first
    $existingBrand = $thuongHieu->getBrand($_POST['id']);
    if (!$existingBrand) {
        throw new Exception('Không tìm thấy thương hiệu');
    }

    $data = [
        'id' => filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT),
        'ma_thuong_hieu' => filter_var($_POST['ma_thuong_hieu'], FILTER_SANITIZE_STRING),
        'ten_thuong_hieu' => filter_var($_POST['ten_thuong_hieu'], FILTER_SANITIZE_STRING),
        'mo_ta' => filter_var($_POST['mo_ta'] ?? '', FILTER_SANITIZE_STRING),
        'website' => filter_var($_POST['website'] ?? '', FILTER_SANITIZE_URL),
        'thu_tu' => filter_var($_POST['thu_tu'], FILTER_SANITIZE_NUMBER_INT),
        'trang_thai' => filter_var($_POST['trang_thai'], FILTER_SANITIZE_NUMBER_INT),
        'logo' => $existingBrand['logo']
    ];

    // Handle logo upload if exists
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $uploadDir = __DIR__ . '/../../../public/uploads/brands/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['logo']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadFile)) {
            // Delete old logo
            if ($existingBrand['logo']) {
                $oldImagePath = $uploadDir . $existingBrand['logo'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $data['logo'] = $fileName;
        }
    }

    // Update brand
    foreach ($data as $key => $value) {
        $thuongHieu->$key = $value;
    }

    if ($thuongHieu->update()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật thương hiệu thành công'
        ]);
    } else {
        throw new Exception('Không thể cập nhật thương hiệu');
    }

} catch (Exception $e) {
    error_log("Error in brand update: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
