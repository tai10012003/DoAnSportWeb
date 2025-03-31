<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/DonHang.php';

class DashboardController extends BaseController {
    private $donHangModel;

    public function __construct() {
        parent::__construct();
        $this->donHangModel = new DonHang($this->db);
    }

    public function index() {
        try {
            $pendingOrders = $this->donHangModel->getPendingOrders();
            return [
                'pendingOrders' => $pendingOrders
            ];
        } catch (Exception $e) {
            error_log("Error in DashboardController::index: " . $e->getMessage());
            return [
                'pendingOrders' => []
            ];
        }
    }
}
