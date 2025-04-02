<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../config/database.php';

class ChatBot {
    private $api_key;
    private $client;
    private $db;
    
    public function __construct() {
        $this->api_key = getenv('OPENAI_API_KEY');
        if (!$this->api_key) {
            throw new Exception('OpenAI API key not found in environment variables');
        }
        $this->client = OpenAI::client($this->api_key);
        
        // Kết nối database
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    private function getProductInfo($query) {
        try {
            $sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu,
                    (SELECT COUNT(*) FROM don_hang_chi_tiet dh 
                     WHERE dh.san_pham_id = sp.id) as so_luot_mua
                    FROM san_pham sp
                    LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                    LEFT JOIN thuong_hieu th ON sp.thuong_hieu_id = th.id
                    WHERE MATCH(sp.ten_sp, sp.mo_ta) AGAINST(:query IN BOOLEAN MODE)
                    OR sp.ten_sp LIKE :query_like
                    AND sp.tinh_trang = 1";
            
            $stmt = $this->db->prepare($sql);
            $searchTerm = '%' . $query . '%';
            $stmt->execute([
                'query' => $query,
                'query_like' => $searchTerm
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error searching products: " . $e->getMessage());
            return [];
        }
    }

    private function getFeaturedProducts() {
        try {
            $sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu
                    FROM san_pham sp
                    LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                    LEFT JOIN thuong_hieu th ON sp.thuong_hieu_id = th.id
                    WHERE sp.noi_bat = 1 AND sp.tinh_trang = 1
                    LIMIT 5";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting featured products: " . $e->getMessage());
            return [];
        }
    }

    private function getOrderInfo($query) {
        try {
            preg_match('/\b\d{6,}\b/', $query, $matches); // Tìm mã đơn hàng trong câu hỏi
            if (empty($matches)) return null;

            $orderNumber = $matches[0];
            $sql = "SELECT dh.*, tt.ten_trang_thai 
                   FROM don_hang dh
                   LEFT JOIN trang_thai tt ON dh.trang_thai = tt.ma_trang_thai
                   WHERE dh.id = :order_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['order_id' => $orderNumber]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting order info: " . $e->getMessage());
            return null;
        }
    }

    private function getPaymentMethods() {
        return [
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'banking' => 'Chuyển khoản ngân hàng - STK: 123456789 (VCB)',
            'momo' => 'Ví điện tử MoMo - SĐT: 0123456789'
        ];
    }

    public function getResponse($message) {
        try {
            // Kiểm tra loại câu hỏi
            $lowerMessage = strtolower($message);
            $context = "";

            // Tìm kiếm thông tin sản phẩm
            if (strpos($lowerMessage, "sản phẩm") !== false || 
                strpos($lowerMessage, "giá") !== false) {
                $products = $this->getProductInfo($message);
                if (!empty($products)) {
                    foreach ($products as $product) {
                        $context .= "Sản phẩm {$product['ten_sp']} " .
                                  "giá " . number_format($product['gia']) . "đ, " .
                                  "thuộc danh mục {$product['ten_danh_muc']}, " .
                                  "thương hiệu {$product['ten_thuong_hieu']}. ";
                    }
                }
            }

            // Kiểm tra sản phẩm nổi bật
            if (strpos($lowerMessage, "nổi bật") !== false || 
                strpos($lowerMessage, "bán chạy") !== false) {
                $featuredProducts = $this->getFeaturedProducts();
                if (!empty($featuredProducts)) {
                    $context .= "Các sản phẩm nổi bật: ";
                    foreach ($featuredProducts as $product) {
                        $context .= "{$product['ten_sp']} " .
                                  "giá " . number_format($product['gia']) . "đ. ";
                    }
                }
            }

            // Kiểm tra thông tin đơn hàng
            if (strpos($lowerMessage, "đơn hàng") !== false || 
                strpos($lowerMessage, "tracking") !== false) {
                $orderInfo = $this->getOrderInfo($message);
                if ($orderInfo) {
                    $context .= "Đơn hàng #{$orderInfo['id']} " .
                              "đang ở trạng thái {$orderInfo['ten_trang_thai']}. ";
                }
            }

            // Thông tin thanh toán
            if (strpos($lowerMessage, "thanh toán") !== false || 
                strpos($lowerMessage, "payment") !== false) {
                $paymentMethods = $this->getPaymentMethods();
                $context .= "Chúng tôi hỗ trợ các phương thức thanh toán sau: ";
                foreach ($paymentMethods as $method => $desc) {
                    $context .= "$desc. ";
                }
            }

            // Gửi context cho GPT
            $response = $this->client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Bạn là trợ lý AI của Sport Elite - cửa hàng thể thao. ' .
                                   'Dưới đây là thông tin cập nhật: ' . $context
                    ],
                    ['role' => 'user', 'content' => $message]
                ],
                'temperature' => 0.7,
                'max_tokens' => 200
            ]);

            return [
                'success' => true,
                'message' => $response->choices[0]->message->content
            ];

        } catch (Exception $e) {
            error_log("ChatBot Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.'
            ];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $message = $data['message'] ?? '';
    
    if (!empty($message)) {
        $chatbot = new ChatBot();
        $response = $chatbot->getResponse($message);
        echo json_encode($response);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tin nhắn không được để trống'
        ]);
    }
}
