<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/SanPham.php';
require_once __DIR__ . '/../models/DanhGia.php';

class HomeController extends BaseController {
    private $sanPhamModel;
    private $danhGiaModel;

    public function __construct() {
        parent::__construct();
        $this->sanPhamModel = new SanPham($this->db);
        $this->danhGiaModel = new DanhGia($this->db);
    }

    public function index() {
        try {
            $featuredProducts = $this->sanPhamModel->getFeaturedProducts(8);
            
            // Thêm đánh giá cho mỗi sản phẩm
            foreach ($featuredProducts as &$product) {
                $avgRating = $this->danhGiaModel->getAverageRating($product['id']);
                $product['avg_rating'] = round($avgRating['avg_rating'] ?? 0, 1);
                $product['total_reviews'] = $avgRating['total_reviews'] ?? 0;
            }

            return $featuredProducts;
        } catch (Exception $e) {
            error_log("Error in HomeController::index: " . $e->getMessage());
            return [];
        }
    }
}
?>
