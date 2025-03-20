<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/SanPham.php';
require_once __DIR__ . '/../config/database.php';

class ProductController extends BaseController {
    private $sanPhamModel;

    public function __construct() {
        parent::__construct();
        $this->sanPhamModel = new SanPham($this->db);
    }

    public function index($page = 1) {
        try {
            $limit = 10;
            $products = $this->sanPhamModel->getAllProducts($page, $limit);
            $totalProducts = $this->sanPhamModel->getTotalProducts();
            $totalPages = ceil($totalProducts / $limit);
            
            $categories = $this->sanPhamModel->getAllCategories();
            $brands = $this->sanPhamModel->getAllBrands();

            return [
                'products' => $products,
                'categories' => $categories,
                'brands' => $brands,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalProducts' => $totalProducts
            ];
        } catch (Exception $e) {
            error_log("Error in ProductController::index: " . $e->getMessage());
            return [
                'products' => [],
                'categories' => [],
                'brands' => [],
                'currentPage' => 1,
                'totalPages' => 0,
                'totalProducts' => 0
            ];
        }
    }

    public function getFeaturedProducts($limit = 8) {
        try {
            return $this->sanPhamModel->getFeaturedProducts($limit) ?? [];
        } catch (Exception $e) {
            error_log("Error in ProductController::getFeaturedProducts: " . $e->getMessage());
            return [];
        }
    }

    public function getAllProducts($page = 1, $perPage = 12) {
        try {
            $products = $this->sanPhamModel->getAllProducts($page, $perPage);
            $totalProducts = $this->sanPhamModel->getTotalProducts();
            $totalPages = ceil($totalProducts / $perPage);
            
            return [
                'products' => $products,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'perPage' => $perPage
            ];
        } catch (Exception $e) {
            error_log("Error in ProductController::getAllProducts: " . $e->getMessage());
            return [
                'products' => [],
                'currentPage' => 1,
                'totalPages' => 0,
                'perPage' => $perPage
            ];
        }
    }

    public function getProductForEdit($id) {
        try {
            $product = $this->sanPhamModel->getProduct($id);
            $categories = $this->sanPhamModel->getAllCategories();
            $brands = $this->sanPhamModel->getAllBrands();
            
            return [
                'product' => $product,
                'categories' => $categories,
                'brands' => $brands
            ];
        } catch (Exception $e) {
            error_log("Error in ProductController::getProductForEdit: " . $e->getMessage());
            return [
                'product' => null,
                'categories' => [],
                'brands' => []
            ];
        }
    }
}
?>
