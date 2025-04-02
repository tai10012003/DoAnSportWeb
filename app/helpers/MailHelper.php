<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        
        // Cấu hình email
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'pductai14@gmail.com';
        $this->mailer->Password = 'rtyz mszc cekw wxms';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;
        $this->mailer->CharSet = 'UTF-8';
    }

    public function sendOrderConfirmation($userEmail, $orderDetails, $orderItems, $totalAmount, $paymentMethod, $orderId) {
        try {
            $this->mailer->setFrom('pductai14@gmail.com', 'Sport Elite');
            $this->mailer->addAddress($userEmail);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Thông báo đơn hàng #DH' . $orderId;

            // Tạo nội dung email
            $body = '<h2>Cảm ơn bạn đã đặt hàng tại Sport Elite!</h2>';
            $body .= '<p>Đơn hàng của bạn đã đặt với thông tin chi tiết như sau:</p>';
            $body .= '<h3>Chi tiết đơn hàng #DH' . $orderId . '</h3>';
            
            // Thêm bảng sản phẩm
            $body .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
            $body .= '<tr style="background-color: #f8f9fa;">
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Sản phẩm</th>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Số lượng</th>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Giá</th>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Thành tiền</th>
                    </tr>';

            foreach ($orderItems as $item) {
                $body .= '<tr>';
                $body .= '<td style="padding: 10px; border: 1px solid #dee2e6;">' . $item['ten_sp'] . '</td>';
                $body .= '<td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">' . $item['so_luong'] . '</td>';
                $body .= '<td style="padding: 10px; border: 1px solid #dee2e6; text-align: right;">' . number_format($item['gia'], 0, ',', '.') . '₫</td>';
                $body .= '<td style="padding: 10px; border: 1px solid #dee2e6; text-align: right;">' . number_format($item['gia'] * $item['so_luong'], 0, ',', '.') . '₫</td>';
                $body .= '</tr>';
            }
            
            $body .= '</table>';
            
            // Thêm thông tin tổng quan
            $body .= '<div style="margin-bottom: 20px;">';
            $body .= '<p><strong>Tổng tiền:</strong> ' . number_format($totalAmount, 0, ',', '.') . '₫</p>';
            $body .= '<p><strong>Phương thức thanh toán:</strong> ' . $paymentMethod . '</p>';
            $body .= '</div>';

            $body .= '<p>Đơn hàng của bạn sẽ được xác nhận và xử lý trong thời gian sớm nhất.</p>';
            $body .= '<p>Để theo dõi đơn hàng, vui lòng truy cập trang <a href="http://localhost/WebbandoTT/don-hang">Đơn hàng của tôi</a></p>';
            $body .= '<p>Xin cảm ơn bạn đã tin tưởng Sport Elite!</p>';

            $this->mailer->Body = $body;
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
