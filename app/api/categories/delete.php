<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/DanhMuc.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($_GET['id'])) {
        throw new Exception('Missing category ID');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $danhMuc = new DanhMuc($db);

    // Get existing category to check if it exists and get image path
    $existingCategory = $danhMuc->getCategory($_GET['id']);
    if (!$existingCategory) {
        throw new Exception('Không tìm thấy danh mục');
    }

    // Set ID for deletion
    $danhMuc->id = $_GET['id'];

    // Delete the category
    if ($danhMuc->delete()) {
        // If deletion successful, delete the image file if exists
        if (!empty($existingCategory['hinh_anh'])) {
            $imagePath = __DIR__ . '/../../../public/uploads/categories/' . $existingCategory['hinh_anh'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa danh mục thành công'
        ]);
    } else {
        throw new Exception('Không thể xóa danh mục');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
