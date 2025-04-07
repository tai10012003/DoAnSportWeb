<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/SanPham.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $sanPham = new SanPham($db);

    // Get existing product first
    $existingProduct = $sanPham->getProduct($_POST['id']);
    if (!$existingProduct) {
        throw new Exception('Không tìm thấy sản phẩm');
    }

    $data = [
        'id' => filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT),
        'ma_sp' => filter_var($_POST['ma_sp'], FILTER_SANITIZE_STRING),
        'ten_sp' => filter_var($_POST['ten_sp'], FILTER_SANITIZE_STRING),
        'mo_ta' => filter_var($_POST['mo_ta'] ?? '', FILTER_SANITIZE_STRING),
        'mo_ta_chi_tiet' => filter_var($_POST['mo_ta_chi_tiet'] ?? '', FILTER_SANITIZE_STRING),
        'gia' => filter_var($_POST['gia'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'gia_khuyen_mai' => !empty($_POST['gia_khuyen_mai']) ? filter_var($_POST['gia_khuyen_mai'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : null,
        'so_luong' => filter_var($_POST['so_luong'], FILTER_SANITIZE_NUMBER_INT),
        'danh_muc_id' => filter_var($_POST['danh_muc_id'], FILTER_SANITIZE_NUMBER_INT),
        'thuong_hieu_id' => filter_var($_POST['thuong_hieu_id'], FILTER_SANITIZE_NUMBER_INT),
        'tinh_trang' => isset($_POST['tinh_trang']) ? 1 : 0,
        'noi_bat' => isset($_POST['noi_bat']) ? 1 : 0,
        'hinh_anh' => $existingProduct['hinh_anh'], // Giữ lại ảnh cũ
        'kich_thuoc' => filter_var($_POST['kich_thuoc'], FILTER_SANITIZE_STRING),
        'mau_sac' => filter_var($_POST['mau_sac'], FILTER_SANITIZE_STRING),
        'chat_lieu' => filter_var($_POST['chat_lieu'], FILTER_SANITIZE_STRING),
        'xuat_xu' => filter_var($_POST['xuat_xu'], FILTER_SANITIZE_STRING),
        'bao_hanh' => filter_var($_POST['bao_hanh'], FILTER_SANITIZE_STRING)
    ];

    // Chỉ xử lý ảnh mới nếu có file được tải lên
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === 0) {
        $uploadDir = __DIR__ . '/../../../public/uploads/products/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['hinh_anh']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadFile)) {
            // Xóa ảnh cũ nếu có
            if ($existingProduct['hinh_anh']) {
                $oldImagePath = $uploadDir . $existingProduct['hinh_anh'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $data['hinh_anh'] = $fileName;
        }
    }

    // Update product
    foreach ($data as $key => $value) {
        $sanPham->$key = $value;
    }

    if ($sanPham->update()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật sản phẩm thành công'
        ]);
    } else {
        throw new Exception('Không thể cập nhật sản phẩm');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
