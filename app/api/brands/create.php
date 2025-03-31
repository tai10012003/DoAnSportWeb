<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/ThuongHieu.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $thuongHieu = new ThuongHieu($db);

    // Validate required fields
    if (empty($_POST['ma_thuong_hieu']) || empty($_POST['ten_thuong_hieu'])) {
        throw new Exception('Vui lòng điền đầy đủ thông tin bắt buộc');
    }

    // Handle logo upload
    $fileName = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $uploadDir = __DIR__ . '/../../../public/uploads/brands/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['logo']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['logo']['tmp_name'], $uploadFile)) {
            throw new Exception('Không thể tải lên logo');
        }
    }

    // Set brand data
    $thuongHieu->ma_thuong_hieu = trim($_POST['ma_thuong_hieu']);
    $thuongHieu->ten_thuong_hieu = trim($_POST['ten_thuong_hieu']);
    $thuongHieu->mo_ta = trim($_POST['mo_ta'] ?? '');
    $thuongHieu->website = trim($_POST['website'] ?? '');
    $thuongHieu->logo = $fileName;
    $thuongHieu->thu_tu = isset($_POST['thu_tu']) ? intval($_POST['thu_tu']) : 0;
    $thuongHieu->trang_thai = isset($_POST['trang_thai']) ? intval($_POST['trang_thai']) : 1;

    if ($thuongHieu->create()) {
        echo json_encode([
            'success' => true,
            'message' => 'Thêm thương hiệu thành công'
        ]);
    } else {
        // Delete uploaded file if brand creation fails
        if ($fileName && file_exists($uploadDir . $fileName)) {
            unlink($uploadDir . $fileName);
        }
        throw new Exception('Không thể thêm thương hiệu');
    }

} catch (Exception $e) {
    error_log("Error in brand creation: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
