<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/NguoiDung.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($_GET['id'])) {
        throw new Exception('Missing user ID');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $nguoiDung = new NguoiDung($db);

    // Get existing user to check if exists
    $existingUser = $nguoiDung->getUser($_GET['id']);
    if (!$existingUser) {
        throw new Exception('Không tìm thấy người dùng');
    }

    // Prevent deleting your own account
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']) {
        throw new Exception('Không thể xóa tài khoản đang đăng nhập');
    }

    // Set ID for deletion
    $nguoiDung->id = $_GET['id'];

    if ($nguoiDung->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa người dùng thành công'
        ]);
    } else {
        throw new Exception('Không thể xóa người dùng');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
