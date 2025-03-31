<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/BaiViet.php';

try {
    // Log request data
    error_log("Update request received: " . print_r($_POST, true));
    
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
    if (empty($_POST['id']) || empty($_POST['tieu_de']) || empty($_POST['slug'])) {
        throw new Exception('Vui lòng điền đầy đủ thông tin bắt buộc');
    }

    // Get existing post
    $existingPost = $baiViet->getPost($_POST['id']);
    if (!$existingPost) {
        throw new Exception('Không tìm thấy bài viết');
    }

    // Handle file upload if exists
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../../public/uploads/blogs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Delete old image if exists
        if ($existingPost['hinh_anh'] && file_exists($uploadDir . $existingPost['hinh_anh'])) {
            unlink($uploadDir . $existingPost['hinh_anh']);
        }

        $fileExtension = pathinfo($_FILES['hinh_anh']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileExtension;
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadFile)) {
            $baiViet->hinh_anh = $fileName;
        }
    } else {
        $baiViet->hinh_anh = $existingPost['hinh_anh'];
    }

    // Set blog post data
    $baiViet->id = $_POST['id'];
    $baiViet->tieu_de = trim($_POST['tieu_de']);
    $baiViet->slug = trim($_POST['slug']);
    $baiViet->mo_ta_ngan = trim($_POST['mo_ta_ngan'] ?? '');
    $baiViet->noi_dung = $_POST['noi_dung'];
    $baiViet->trang_thai = intval($_POST['trang_thai']);
    $baiViet->meta_title = trim($_POST['meta_title'] ?? '');
    $baiViet->meta_description = trim($_POST['meta_description'] ?? '');

    if ($baiViet->update()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật bài viết thành công'
        ]);
    } else {
        throw new Exception('Không thể cập nhật bài viết');
    }

} catch (Exception $e) {
    error_log("Error updating post: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
