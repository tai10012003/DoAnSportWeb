<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($_FILES['upload'])) {
        throw new Exception('No file uploaded');
    }

    $uploadDir = __DIR__ . '/../../../public/uploads/blogs/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $file = $_FILES['upload'];
    $fileName = uniqid() . '_' . basename($file['name']);
    $uploadPath = $uploadDir . $fileName;

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Invalid file type');
    }

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo json_encode([
            'uploaded' => 1,
            'fileName' => $fileName,
            'url' => '/WebbandoTT/public/uploads/blogs/' . $fileName
        ]);
    } else {
        throw new Exception('Could not upload file');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'uploaded' => 0,
        'error' => ['message' => $e->getMessage()]
    ]);
}
