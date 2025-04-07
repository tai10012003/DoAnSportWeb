<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: /WebbandoTT/dang-nhap");
    exit;
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: /WebbandoTT/gio-hang");
    exit;
}
$db = new Database();
$conn = $db->getConnection();



// Lấy thông tin người dùng từ session
$user_id = $_SESSION['user_id'];
$query = "SELECT ho_ten, so_dien_thoai, dia_chi FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Nếu không tìm thấy người dùng, chuyển hướng đến trang đăng nhập
    header("Location: /WebbandoTT/dang-nhap");
    exit;
}

$ho_ten = $user['ho_ten'];
$so_dien_thoai = $user['so_dien_thoai'];
$dia_chi = $user['dia_chi'];

// Tính tổng tiền
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = $subtotal >= 500000 ? 0 : 30000;
$total = $subtotal + $shipping;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - Sport Elite</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row g-4">
            <!-- Cột trái - Thông tin đặt hàng -->
            <div class="col-lg-7">
                <div class="checkout-section mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 py-2">
                                <i class="bi bi-person-lines-fill me-2"></i>THÔNG TIN NHẬN HÀNG
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="orderForm" class="checkout-form">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="receiver_name">Tên người nhận</label>
                                            <input type="text" class="form-control" id="receiver_name" 
                                                    value="<?php echo htmlspecialchars($ho_ten); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label class="form-label" for="receiver_phone">Số điện thoại</label>
                                            <input type="tel" class="form-control" id="receiver_phone" 
                                                   value="<?php echo htmlspecialchars($so_dien_thoai); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="city_select">Thành phố</label>
                                            <select class="form-select" id="city_select" required name="thanh_pho">
                                                <option value="">Chọn thành phố</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="receiver_address">Địa chỉ nhận hàng</label>
                                                <textarea class="form-control" id="receiver_address" style="height: 100px" 
                                                      required name="dia_chi"><?php echo htmlspecialchars($dia_chi); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="order_note">Ghi chú đơn hàng (nếu có)</label>
                                            <textarea class="form-control" id="order_note" style="height: 100px"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="checkout-section">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 py-2">
                                <i class="bi bi-credit-card me-2"></i>PHƯƠNG THỨC THANH TOÁN
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-methods">
                                <div class="payment-method">
                                    <input type="radio" class="btn-check" name="payment_method" id="cod" value="cod" checked>
                                    <label class="btn btn-outline-payment w-100 text-start mb-3" for="cod">
                                        <i class="bi bi-cash-coin me-2"></i>
                                        <span>Thanh toán khi nhận hàng (COD)</span>
                                    </label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" class="btn-check" name="payment_method" id="bank_transfer" value="bank_transfer">
                                    <label class="btn btn-outline-payment w-100 text-start mb-3" for="bank_transfer">
                                        <i class="bi bi-bank me-2"></i>
                                        <span>Chuyển khoản ngân hàng</span>
                                    </label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" class="btn-check" name="payment_method" id="momo" value="momo">
                                    <label class="btn btn-outline-payment w-100 text-start" for="momo">
                                        <i class="bi bi-wallet2 me-2"></i>
                                        <span>Thanh toán qua MoMo</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải - Thông tin đơn hàng -->
            <div class="col-lg-5">
                <div class="order-summary-section" style="top: 90px;">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 py-2">
                                <i class="bi bi-bag-check me-2"></i>ĐƠN HÀNG CỦA BẠN
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="order-items" style="max-height: 400px; overflow-y: auto">
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                <div class="order-item d-flex align-items-center mb-3" style="border: 1px solid">
                                    <img src="<?php echo $item['image'] ? '/WebbandoTT/public/uploads/products/'.$item['image'] : '/WebbandoTT/app/public/images/no-image.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                         class="order-item-image">
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">SL: <?php echo $item['quantity']; ?></span>
                                            <span class="text-danger"><?php echo number_format($item['price'] * $item['quantity']); ?>₫</span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="order-totals mt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính</span>
                                    <span><?php echo number_format($subtotal); ?>₫</span>
                                </div>
                                <?php
                                // Calculate shipping fee
                                $shipping_fee = ($subtotal >= 1000000) ? 0 : 30000;
                                // Calculate total amount
                                $total_amount = $subtotal + $shipping_fee;
                                
                                echo '<input type="hidden" name="phi_van_chuyen" value="' . $shipping_fee . '">';
                                echo '<input type="hidden" name="tong_tien" value="' . $total_amount . '">';
                                ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí vận chuyển</span>
                                    <span><?php echo $shipping_fee > 0 ? number_format($shipping_fee) . '₫' : 'Miễn phí'; ?></span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold border-top pt-3 mt-3">
                                    <span>Tổng cộng</span>
                                    <span class="text-danger h5 mb-0">
                                        <?php echo number_format($total_amount); ?>₫
                                    </span>
                                </div>
                            </div>

                            <button type="submit" id="confirm-order" class="btn btn-primary w-100 mt-4">
                                Đặt hàng ngay <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mt-3 bg-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-check text-primary me-2"></i>
                                <h6 class="mb-0">Thông tin bảo mật</h6>
                            </div>
                            <p class="small mb-0">
                                Thông tin thanh toán của bạn được mã hóa và bảo mật an toàn.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script>
        // Fetch cities from the API
        fetch('https://vn-public-apis.fpo.vn/provinces/getAll?limit=-1')
            .then(response => response.json())
            .then(data => {
                if (data.exitcode === 1) {
                    const citySelect = document.getElementById('city_select');
                    data.data.data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.code; // or any other unique identifier
                        option.textContent = city.name_with_type;
                        citySelect.appendChild(option);
                    });
                } else {
                    console.error('Failed to fetch cities:', data.message);
                }
            })
            .catch(error => console.error('Error fetching cities:', error));
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/WebbandoTT/app/public/js/order.js"></script>
</body>
</html>