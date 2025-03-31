<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DanhMuc.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $danhMuc = new DanhMuc($db);

    // Get existing category first
    $existingCategory = $danhMuc->getCategory($_POST['id']);
    if (!$existingCategory) {
        throw new Exception('Không tìm thấy danh mục');
    }

    // Special handling for danh_muc_cha_id
    $danhMucChaId = !empty($_POST['danh_muc_cha_id']) ? 
                    filter_var($_POST['danh_muc_cha_id'], FILTER_SANITIZE_NUMBER_INT) : 
                    null;

    $data = [
        'id' => filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT),
        'ma_danh_muc' => filter_var($_POST['ma_danh_muc'], FILTER_SANITIZE_STRING),
        'ten_danh_muc' => filter_var($_POST['ten_danh_muc'], FILTER_SANITIZE_STRING),
        'mo_ta' => filter_var($_POST['mo_ta'] ?? '', FILTER_SANITIZE_STRING),
        'danh_muc_cha_id' => $danhMucChaId,
        'thu_tu' => filter_var($_POST['thu_tu'], FILTER_SANITIZE_NUMBER_INT),
        'trang_thai' => filter_var($_POST['trang_thai'], FILTER_SANITIZE_NUMBER_INT),
        'hinh_anh' => $existingCategory['hinh_anh']
    ];

    // Handle file upload if exists
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === 0) {
        $uploadDir = __DIR__ . '/../../../public/uploads/categories/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['hinh_anh']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadFile)) {
            // Delete old image
            if ($existingCategory['hinh_anh']) {
                $oldImagePath = $uploadDir . $existingCategory['hinh_anh'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $data['hinh_anh'] = $fileName;
        }
    }

    // Update category
    foreach ($data as $key => $value) {
        $danhMuc->$key = $value;
    }

    if ($danhMuc->update()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật danh mục thành công'
        ]);
    } else {
        throw new Exception('Không thể cập nhật danh mục');
    }

} catch (Exception $e) {
    error_log("Error in category update: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
