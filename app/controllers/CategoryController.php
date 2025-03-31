<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/DanhMuc.php';

class CategoryController extends BaseController {
    private $danhMucModel;

    public function __construct() {
        parent::__construct();
        $this->danhMucModel = new DanhMuc($this->db);
    }

    public function index($page = 1) {
        try {
            $limit = 10;
            $categories = $this->danhMucModel->getAllCategories($page, $limit);
            $totalCategories = $this->danhMucModel->getTotalCategories();
            $totalPages = ceil($totalCategories / $limit);

            return [
                'categories' => $categories,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalCategories' => $totalCategories
            ];
        } catch (Exception $e) {
            error_log("Error in CategoryController::index: " . $e->getMessage());
            return [
                'categories' => [],
                'currentPage' => 1,
                'totalPages' => 0,
                'totalCategories' => 0
            ];
        }
    }

    public function getAllParentCategories() {
        try {
            return $this->danhMucModel->getAllCategories();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    public function getCategoryForEdit($id) {
        try {
            $category = $this->danhMucModel->getCategory($id);
            
            return [
                'category' => $category,
            ];
        } catch (Exception $e) {
            error_log("Error in CategoryController::getCategoryForEdit: " . $e->getMessage());
            return [
                'category' => null,
            ];
        }
    }
}
