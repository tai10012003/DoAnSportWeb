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

    if (!isset($_GET['id'])) {
        throw new Exception('Missing post ID');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $baiViet = new BaiViet($db);

    // Get existing post to check if exists and get image filename
    $existingPost = $baiViet->getPost($_GET['id']);
    if (!$existingPost) {
        throw new Exception('Không tìm thấy bài viết');
    }

    // Delete associated image if exists
    if ($existingPost['hinh_anh']) {
        $imagePath = __DIR__ . '/../../../public/uploads/blogs/' . $existingPost['hinh_anh'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Set ID for deletion
    $baiViet->id = $_GET['id'];

    if ($baiViet->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa bài viết thành công'
        ]);
    } else {
        throw new Exception('Không thể xóa bài viết');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
