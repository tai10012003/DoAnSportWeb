<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/SanPham.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        throw new Exception('Method not allowed');
    }

    $id = $_POST['id'] ?? $_GET['id'] ?? null;
    if (!$id) {
        throw new Exception('ID sản phẩm không được cung cấp');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $sanPham = new SanPham($db);

    // Lấy thông tin sản phẩm để xóa ảnh
    $product = $sanPham->getProduct($id);
    
    if ($product) {
        // Xóa file ảnh nếu tồn tại
        if ($product['hinh_anh']) {
            $imagePath = __DIR__ . '/../../../public/uploads/products/' . $product['hinh_anh'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Xóa sản phẩm từ database
        $sanPham->id = $id;
        if ($sanPham->delete()) {
            echo json_encode([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);
        } else {
            throw new Exception('Không thể xóa sản phẩm');
        }
    } else {
        throw new Exception('Không tìm thấy sản phẩm');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}



