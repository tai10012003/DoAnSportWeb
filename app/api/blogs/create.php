<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/BaiViet.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    // Check user authentication
    session_start();
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized access');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $baiViet = new BaiViet($db);

    // Validate required fields
    if (empty($_POST['tieu_de']) || empty($_POST['slug']) || empty($_POST['noi_dung'])) {
        throw new Exception('Vui lòng điền đầy đủ thông tin bắt buộc');
    }

    // Handle file upload if exists
    $hinh_anh = null;
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../../public/uploads/blogs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = pathinfo($_FILES['hinh_anh']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileExtension;
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadFile)) {
            $hinh_anh = $fileName;
        }
    }

    // Set blog post data
    $baiViet->tieu_de = $_POST['tieu_de'];
    $baiViet->slug = $_POST['slug'];
    $baiViet->mo_ta_ngan = $_POST['mo_ta_ngan'] ?? '';
    $baiViet->noi_dung = $_POST['noi_dung'];
    $baiViet->hinh_anh = $hinh_anh;
    $baiViet->user_id = $_SESSION['user_id'];
    $baiViet->trang_thai = $_POST['trang_thai'] ?? 1;
    $baiViet->meta_title = $_POST['meta_title'] ?? '';
    $baiViet->meta_description = $_POST['meta_description'] ?? '';

    if ($baiViet->create()) {
        echo json_encode([
            'success' => true,
            'message' => 'Thêm bài viết thành công'
        ]);
    } else {
        throw new Exception('Không thể thêm bài viết');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
