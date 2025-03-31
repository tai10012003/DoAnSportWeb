<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DanhMuc.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $danhMuc = new DanhMuc($db);

    // Validate required fields
    if (empty($_POST['ma_danh_muc']) || empty($_POST['ten_danh_muc'])) {
        throw new Exception('Vui lòng điền đầy đủ thông tin bắt buộc');
    }

    // Handle file upload
    $fileName = null;
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === 0) {
        $uploadDir = __DIR__ . '/../../../public/uploads/categories/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['hinh_anh']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadFile)) {
            throw new Exception('Không thể tải lên hình ảnh');
        }
    }

    // Set category data
    $danhMuc->ma_danh_muc = trim($_POST['ma_danh_muc']);
    $danhMuc->ten_danh_muc = trim($_POST['ten_danh_muc']);
    $danhMuc->mo_ta = trim($_POST['mo_ta'] ?? '');
    $danhMuc->hinh_anh = $fileName;
    $danhMuc->danh_muc_cha_id = !empty($_POST['danh_muc_cha_id']) ? intval($_POST['danh_muc_cha_id']) : null;
    $danhMuc->thu_tu = isset($_POST['thu_tu']) ? intval($_POST['thu_tu']) : 0;
    $danhMuc->trang_thai = isset($_POST['trang_thai']) ? intval($_POST['trang_thai']) : 1;

    if ($danhMuc->create()) {
        echo json_encode([
            'success' => true,
            'message' => 'Thêm danh mục thành công'
        ]);
    } else {
        // Delete uploaded file if category creation fails
        if ($fileName && file_exists($uploadDir . $fileName)) {
            unlink($uploadDir . $fileName);
        }
        throw new Exception('Không thể thêm danh mục');
    }

} catch (Exception $e) {
    error_log("Error in category creation: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
