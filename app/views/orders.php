<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /WebbandoTT/dang-nhap");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'];
// Lấy danh sách đơn hàng của user
$query = "SELECT *, DATE_FORMAT(created_at, '%Y%m%d%H%i') as ma_don_hang 
          FROM don_hang WHERE user_id = :user_id 
          ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi - Sport Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="orders-wrapper">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="orders-title">
                            <i class="bi bi-box-seam"></i>
                            ĐƠN HÀNG CỦA TÔI
                        </h5>
                        <a href="/WebbandoTT/san-pham" class="btn btn-outline-primary">
                            <i class="bi bi-plus-lg me-2"></i>Mua thêm
                        </a>
                    </div>

                    <?php if (empty($orders)): ?>
                        <div class="text-center empty-orders">
                            <img src="/WebbandoTT/public/images/empty-order.svg" alt="Empty Order" class="mb-4" style="width: 200px;">
                            <h4 class="fw-bold mb-3">Bạn chưa có đơn hàng nào</h4>
                            <p class="text-muted mb-4">Hãy khám phá các sản phẩm và đặt hàng ngay!</p>
                            <a href="/WebbandoTT/san-pham" class="btn btn-primary btn-lg">
                                <i class="bi bi-cart me-2"></i>Mua sắm ngay
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="orders-list">
                            <?php foreach ($orders as $order): ?>
                                <div class="order-item">
                                    <div class="order-header">
                                        <div class="d-flex align-items-center gap-4">
                                            <div class="order-info">
                                                <div class="order-id mb-2">
                                                    Mã đơn hàng: <span class="fw-bold text-dark">#DH<?php echo $order['ma_don_hang']; ?></span>
                                                </div>
                                                <div class="order-date">
                                                    <i class="bi bi-calendar3 me-2"></i>
                                                    <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                                </div>
                                            </div>
                                            <div class="order-status">
                                                <?php
                                                $statusClass = '';
                                                $statusText = '';
                                                switch($order['trang_thai']) {
                                                    case 'pending':
                                                        $statusClass = 'pending';
                                                        $statusText = 'Chờ xác nhận';
                                                        break;
                                                    case 'processing':
                                                        $statusClass = 'processing';
                                                        $statusText = 'Đang xử lý';
                                                        break;
                                                    case 'shipped':
                                                        $statusClass = 'shipped';
                                                        $statusText = 'Đang giao';
                                                        break;
                                                    case 'completed':
                                                        $statusClass = 'completed';
                                                        $statusText = 'Đã hoàn thành';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'cancelled';
                                                        $statusText = 'Đã hủy';
                                                        break;
                                                }
                                                ?>
                                                <span class="status-badge <?php echo $statusClass; ?>">
                                                    <i class="bi bi-circle-fill me-2"></i><?php echo $statusText; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="order-body">
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <div class="order-info-box">
                                                    <div class="info-label">Tổng tiền</div>
                                                    <div class="info-value text-danger fw-bold">
                                                        <?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>₫
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="order-info-box">
                                                    <div class="info-label">Phương thức thanh toán</div>
                                                    <div class="info-value">
                                                        <i class="bi bi-credit-card me-2"></i>
                                                        <?php echo $order['payment_method']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <button class="btn btn-outline-dark view-details w-100" 
                                                        data-order-id="<?php echo $order['id']; ?>">
                                                    <i class="bi bi-eye me-2"></i>Xem chi tiết đơn hàng
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chi tiết đơn hàng -->
    <div class="modal fade order-detail-modal" id="orderDetailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-wrapper">
                        <h5 class="modal-title">Chi tiết đơn hàng</h5>
                        <span class="order-id">#DH<span id="orderIdSpan"></span></span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetailContent"></div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.dataset.orderId;
                document.getElementById('orderIdSpan').textContent = orderId;
                
                // Fetch chi tiết đơn hàng
                fetch(`/WebbandoTT/app/api/orders/get_order_details.php?id=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const orderDetails = data.details;
                            let totalAmount = 0;
                            let html = `
                                <div class="order-detail-items">
                                    <table class="order-detail-table">
                                        <thead>
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th class="text-center">Giá</th>
                                                <th class="text-center">Số lượng</th>
                                                <th class="text-end">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            `;
                            
                            orderDetails.forEach(item => {
                                const itemTotal = item.gia * item.so_luong;
                                totalAmount += itemTotal;
                                html += `
                                    <tr>
                                        <td>
                                            <div class="order-product-info">
                                                <div class="order-product-image">
                                                    <img src="/WebbandoTT/public/uploads/products/${item.hinh_anh}" 
                                                         alt="${item.ten_sp}">
                                                </div>
                                                <div class="order-product-details">
                                                    <h6>${item.ten_sp}</h6>
                                                    <span class="sku">SKU: SP${item.san_pham_id}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center order-price">
                                            ${new Intl.NumberFormat('vi-VN').format(item.gia)}₫
                                        </td>
                                        <td class="text-center">
                                            <span class="order-quantity">${item.so_luong}</span>
                                        </td>
                                        <td class="text-end order-price">
                                            ${new Intl.NumberFormat('vi-VN').format(itemTotal)}₫
                                        </td>
                                    </tr>
                                `;
                            });
                            
                            html += `
                                        </tbody>
                                    </table>
                                </div>
                                <div class="order-detail-summary">
                                    <div class="summary-row">
                                        <span class="summary-label">Tạm tính</span>
                                        <span class="summary-value">${new Intl.NumberFormat('vi-VN').format(totalAmount)}₫</span>
                                    </div>
                                    <div class="summary-row">
                                        <span class="summary-label">Phí vận chuyển</span>
                                        <span class="summary-value">Miễn phí</span>
                                    </div>
                                    <div class="summary-row">
                                        <span class="summary-label">Tổng cộng</span>
                                        <span class="summary-value">${new Intl.NumberFormat('vi-VN').format(totalAmount)}₫</span>
                                    </div>
                                </div>
                            `;
                            
                            document.getElementById('orderDetailContent').innerHTML = html;
                            new bootstrap.Modal(document.getElementById('orderDetailModal')).show();
                        }
                    });
            });
        });
    </script>
</body>
</html>
