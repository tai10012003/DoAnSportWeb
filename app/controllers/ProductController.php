<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/SanPham.php';
require_once __DIR__ . '/../models/DanhGia.php';  // Thêm dòng này
require_once __DIR__ . '/../config/database.php';

class ProductController extends BaseController {
    private $sanPhamModel;
    private $danhGiaModel;  // Thêm property

    public function __construct() {
        parent::__construct();
        $this->sanPhamModel = new SanPham($this->db);
        $this->danhGiaModel = new DanhGia($this->db);  // Thêm khởi tạo
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

    public function filterAndGetProducts() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 12;
            
            // Lấy và validate các tham số filter
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $categoryId = isset($_GET['category']) && !empty($_GET['category']) ? (int)$_GET['category'] : null;
            $brandId = isset($_GET['brand']) && !empty($_GET['brand']) ? (int)$_GET['brand'] : null;
            $priceRange = isset($_GET['price']) ? $_GET['price'] : null;
            $sort = isset($_GET['sort']) ? $_GET['sort'] : '';

            $products = $this->sanPhamModel->filterProducts(
                $search,
                $categoryId,
                $priceRange,
                $brandId,
                $page,
                $perPage,
                $sort
            );

            // Thêm đoạn code này để lấy đánh giá cho mỗi sản phẩm
            foreach ($products as &$product) {
                $avgRating = $this->danhGiaModel->getAverageRating($product['id']);
                $product['avg_rating'] = round($avgRating['avg_rating'] ?? 0, 1);
                $product['total_reviews'] = $avgRating['total_reviews'] ?? 0;
            }

            $categories = $this->sanPhamModel->getAllCategories();
            $brands = $this->sanPhamModel->getAllBrands();

            return [
                'products' => $products,
                'categories' => $categories,
                'brands' => $brands,
                'currentPage' => $page,
                'totalPages' => ceil(count($products) / $perPage),
                'totalProducts' => count($products),
                'filters' => [
                    'search' => $search,
                    'category' => $categoryId,
                    'brand' => $brandId,
                    'price' => $priceRange,
                    'sort' => $sort
                ]
            ];
        } catch (Exception $e) {
            error_log("Error in filterAndGetProducts: " . $e->getMessage());
            return [
                'products' => [],
                'categories' => [],
                'brands' => [],
                'currentPage' => 1,
                'totalPages' => 0,
                'totalProducts' => 0,
                'filters' => []
            ];
        }
    }
}
?>
