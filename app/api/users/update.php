<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/NguoiDung.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $database = Database::getInstance();
    $db = $database->getConnection();
    $nguoiDung = new NguoiDung($db);

    // Get existing user
    $existingUser = $nguoiDung->getUser($_POST['id']);
    if (!$existingUser) {
        throw new Exception('Không tìm thấy người dùng');
    }

    // Check username uniqueness if changed
    if ($_POST['username'] !== $existingUser['username'] && $nguoiDung->usernameExists($_POST['username'])) {
        throw new Exception('Tên đăng nhập đã tồn tại');
    }

    // Check email uniqueness if changed
    if ($_POST['email'] !== $existingUser['email'] && $nguoiDung->emailExists($_POST['email'])) {
        throw new Exception('Email đã tồn tại');
    }

    // Set user data
    $nguoiDung->id = $_POST['id'];
    $nguoiDung->username = trim($_POST['username']);
    $nguoiDung->email = trim($_POST['email']);
    $nguoiDung->ho_ten = trim($_POST['ho_ten']);
    $nguoiDung->so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
    $nguoiDung->dia_chi = trim($_POST['dia_chi'] ?? '');
    $nguoiDung->role = $_POST['role'];
    $nguoiDung->trang_thai = intval($_POST['trang_thai']);

    // Only set password if provided
    if (!empty($_POST['password'])) {
        $nguoiDung->password = $_POST['password'];
    }

    if ($nguoiDung->update()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật người dùng thành công'
        ]);
    } else {
        throw new Exception('Không thể cập nhật người dùng');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
