<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/ThuongHieu.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($_GET['id'])) {
        throw new Exception('Missing brand ID');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $thuongHieu = new ThuongHieu($db);

    // Get existing brand to check if it exists and get logo path
    $existingBrand = $thuongHieu->getBrand($_GET['id']);
    if (!$existingBrand) {
        throw new Exception('Không tìm thấy thương hiệu');
    }

    // Set ID for deletion
    $thuongHieu->id = $_GET['id'];

    // Delete the brand
    if ($thuongHieu->delete()) {
        // If deletion successful, delete the logo file if exists
        if (!empty($existingBrand['logo'])) {
            $logoPath = __DIR__ . '/../../../public/uploads/brands/' . $existingBrand['logo'];
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa thương hiệu thành công'
        ]);
    } else {
        throw new Exception('Không thể xóa thương hiệu');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
