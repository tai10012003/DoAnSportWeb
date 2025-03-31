<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/DonHang.php';

class OrderController extends BaseController {
    private $donHangModel;

    public function __construct() {
        parent::__construct();
        $this->donHangModel = new DonHang($this->db);
    }

    // Lấy danh sách đơn hàng (có phân trang)
    public function index($page = 1) {
        try {
            $limit = 10;
            $orders = $this->donHangModel->getAllOrders($page, $limit);
            $totalOrders = $this->donHangModel->getTotalOrders();
            $totalPages = ceil($totalOrders / $limit);

            return [
                'orders' => $orders,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalOrders' => $totalOrders
            ];
        } catch (Exception $e) {
            error_log("Error in OrderController::index: " . $e->getMessage());
            return [
                'orders' => [],
                'currentPage' => 1,
                'totalPages' => 0,
                'totalOrders' => 0
            ];
        }
    }

    public function getAllOrdersNoPagination() {
        try {
            $orders = $this->donHangModel->getAllOrdersNoPagination();
            return [
                'orders' => $orders
            ];
        } catch (Exception $e) {
            error_log("Error in OrderController::getAllOrdersNoPagination: " . $e->getMessage());
            return [
                'orders' => []
            ];
        }
    }
    // Lấy đơn hàng theo ID
    public function getOrderById($id) {
        try {
            $order = $this->donHangModel->getOrderById($id);
            return [
                'order' => $order
            ];
        } catch (Exception $e) {
            error_log("Error in OrderController::getOrderById: " . $e->getMessage());
            return [
                'order' => null
            ];
        }
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($id, $status) {
        try {
            $success = $this->donHangModel->updateStatus($id, $status);
            return [
                'success' => $success
            ];
        } catch (Exception $e) {
            error_log("Error in OrderController::updateOrderStatus: " . $e->getMessage());
            return [
                'success' => false
            ];
        }
    }

    // Xoá đơn hàng
    public function deleteOrder($id) {
        try {
            $this->donHangModel->id = $id;
            $success = $this->donHangModel->delete();
            return [
                'success' => $success
            ];
        } catch (Exception $e) {
            error_log("Error in OrderController::deleteOrder: " . $e->getMessage());
            return [
                'success' => false
            ];
        }
    }

    // Lọc đơn hàng theo trạng thái
    public function getOrdersByStatus($status) {
        try {
            $stmt = $this->donHangModel->getOrdersByStatus($status);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return [
                'orders' => $orders
            ];
        } catch (Exception $e) {
            error_log("Error in OrderController::getOrdersByStatus: " . $e->getMessage());
            return [
                'orders' => []
            ];
        }
    }

    public function getDataForOrderForm() {
        try {
            require_once __DIR__ . '/../models/NguoiDung.php';
            $nguoiDungModel = new NguoiDung($this->db);
            $users = $nguoiDungModel->getAllActiveUsers();
            
            return [
                'users' => $users,
                'payment_methods' => ['cod', 'banking', 'momo', 'zalopay']
            ];
        } catch (Exception $e) {
            error_log("Error in OrderController::getDataForOrderForm: " . $e->getMessage());
            return [
                'users' => [],
                'payment_methods' => []
            ];
        }
    }
}
?>