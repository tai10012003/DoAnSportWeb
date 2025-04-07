<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/SanPham.php';

try {
    $database = Database::getInstance();
    $db = $database->getConnection();
    $sanPham = new SanPham($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'ma_sp' => $_POST['ma_sp'],
            'ten_sp' => $_POST['ten_sp'],
            'mo_ta' => $_POST['mo_ta'],
            'mo_ta_chi_tiet' => $_POST['mo_ta_chi_tiet'],
            'kich_thuoc' => $_POST['kich_thuoc'],
            'mau_sac' => $_POST['mau_sac'],
            'chat_lieu' => $_POST['chat_lieu'],
            'xuat_xu' => $_POST['xuat_xu'],
            'bao_hanh' => $_POST['bao_hanh'],
            'gia' => $_POST['gia'],
            'gia_khuyen_mai' => $_POST['gia_khuyen_mai'] ?: null,
            'so_luong' => $_POST['so_luong'],
            'danh_muc_id' => $_POST['danh_muc_id'],
            'thuong_hieu_id' => $_POST['thuong_hieu_id'],
            'tinh_trang' => isset($_POST['tinh_trang']) ? 1 : 0,
            'noi_bat' => isset($_POST['noi_bat']) ? 1 : 0
        ];

        // Handle file upload
        if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === 0) {
            $uploadDir = __DIR__ . '/../../../public/uploads/products/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['hinh_anh']['name']);
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadFile)) {
                $data['hinh_anh'] = $fileName;
            } else {
                throw new Exception('Failed to upload image');
            }
        }

        // Assign all properties from data array to model
        foreach ($data as $property => $value) {
            $sanPham->$property = $value;
        }

        if ($sanPham->create()) {
            echo json_encode([
                'success' => true,
                'message' => 'Thêm sản phẩm thành công'
            ]);
        } else {
            throw new Exception('Failed to create product');
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
