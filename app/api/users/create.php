<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/NguoiDung.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $nguoiDung = new NguoiDung($db);

    // Validate required fields
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['ho_ten'])) {
        throw new Exception('Vui lòng điền đầy đủ thông tin bắt buộc');
    }

    // Check if username already exists
    if ($nguoiDung->usernameExists($_POST['username'])) {
        throw new Exception('Tên đăng nhập đã tồn tại');
    }

    // Check if email already exists
    if ($nguoiDung->emailExists($_POST['email'])) {
        throw new Exception('Email đã tồn tại');
    }

    // Set user data
    $nguoiDung->username = trim($_POST['username']);
    $nguoiDung->password = $_POST['password'];
    $nguoiDung->email = trim($_POST['email']);
    $nguoiDung->ho_ten = trim($_POST['ho_ten']);
    $nguoiDung->so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
    $nguoiDung->dia_chi = trim($_POST['dia_chi'] ?? '');
    $nguoiDung->role = $_POST['role'] ?? 'user';
    $nguoiDung->trang_thai = isset($_POST['trang_thai']) ? intval($_POST['trang_thai']) : 1;

    if ($nguoiDung->create()) {
        echo json_encode([
            'success' => true,
            'message' => 'Thêm người dùng thành công'
        ]);
    } else {
        throw new Exception('Không thể thêm người dùng');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
