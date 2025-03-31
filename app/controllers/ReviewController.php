<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/DanhGia.php';

class ReviewController extends BaseController {
    // Change from private to public so it can be accessed
    public $danhGiaModel;

    public function __construct() {
        parent::__construct();
        $this->danhGiaModel = new DanhGia($this->db);
    }

    public function addReview() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            if (!isset($_SESSION['user_id'])) {
                return json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đánh giá']);
            }

            $san_pham_id = (int)$_POST['san_pham_id'];
            $diem_danh_gia = (int)$_POST['diem_danh_gia'];
            $noi_dung = trim($_POST['noi_dung']);

            if (!$san_pham_id || !$diem_danh_gia || empty($noi_dung)) {
                return json_encode([
                    'success' => false, 
                    'message' => 'Vui lòng điền đầy đủ thông tin đánh giá',
                    'debug' => [
                        'san_pham_id' => $san_pham_id,
                        'diem_danh_gia' => $diem_danh_gia,
                        'noi_dung' => $noi_dung
                    ]
                ]);
            }

            if ($diem_danh_gia < 1 || $diem_danh_gia > 5) {
                return json_encode(['success' => false, 'message' => 'Số sao đánh giá không hợp lệ']);
            }

            if ($this->danhGiaModel->create($san_pham_id, $_SESSION['user_id'], $diem_danh_gia, $noi_dung)) {
                return json_encode(['success' => true, 'message' => 'Cảm ơn bạn đã đánh giá sản phẩm!']);
            }

            return json_encode(['success' => false, 'message' => 'Không thể lưu đánh giá']);
        } catch (Exception $e) {
            error_log("Error in ReviewController::addReview: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }

    public function getProductReviews($san_pham_id) {
        return $this->danhGiaModel->getProductReviews($san_pham_id);
    }
}
