<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ThuongHieu.php';

class BrandController extends BaseController {
    private $thuongHieuModel;

    public function __construct() {
        parent::__construct();
        $this->thuongHieuModel = new ThuongHieu($this->db);
    }

    public function index($page = 1) {
        try {
            $limit = 10;
            $brands = $this->thuongHieuModel->getAllBrands($page, $limit);
            $totalBrands = $this->thuongHieuModel->getTotalBrands();
            $totalPages = ceil($totalBrands / $limit);

            return [
                'brands' => $brands,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalBrands' => $totalBrands
            ];
        } catch (Exception $e) {
            error_log("Error in BrandController::index: " . $e->getMessage());
            return [
                'brands' => [],
                'currentPage' => 1,
                'totalPages' => 0,
                'totalBrands' => 0
            ];
        }
    }

    public function getBrandById($id) {
        try {
            return $this->thuongHieuModel->getBrand($id);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getBrandForEdit($id) {
        try {
            $brand = $this->thuongHieuModel->getBrand($id);
            
            return [
                'brand' => $brand
            ];
        } catch (Exception $e) {
            error_log("Error in BrandController::getBrandForEdit: " . $e->getMessage());
            return [
                'brand' => null
            ];
        }
    }
}
