<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/SanPham.php';

try {
    $database = Database::getInstance();
    $db = $database->getConnection();
    $sanPham = new SanPham($db);

    $search = $_GET['search'] ?? '';
    $categoryId = $_GET['category'] ?? '';
    $brandId = $_GET['brand'] ?? '';

    $products = $sanPham->searchProducts($search, $categoryId, $brandId);

    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
