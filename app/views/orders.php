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
        <h2 class="mb-4">Chọn phương thức thanh toán</h2>

        <div class="row">
            <div class="col-lg-7">
                <form id="order-form">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Thông tin nhận hàng</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Tên người nhận</label>
                                <input type="text" class="form-control" id="receiver_name" value="<?php echo htmlspecialchars($ho_ten); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="receiver_phone" value="<?php echo htmlspecialchars($so_dien_thoai); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Địa chỉ nhận hàng</label>
                                <textarea class="form-control" id="receiver_address" required name="dia_chi"><?php echo htmlspecialchars($dia_chi); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Chọn thành phố</label>
                                <select class="form-select" id="city_select" required name="thanh_pho">
                                    <option value="">Chọn thành phố</option>
                                    <!-- Cities will be populated here -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú (nếu có)</label>
                                <textarea class="form-control" id="order_note"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="mb-3">Chọn phương thức thanh toán</h5>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                <label class="form-check-label" for="bank_transfer">Chuyển khoản ngân hàng</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo">
                                <label class="form-check-label" for="momo">Thanh toán qua MoMo</label>
                            </div>

                            <button type="button" id="confirm-order" class="btn btn-primary btn-lg mt-3 w-100">Thanh toán</button>
                        </div>
                    </div>
                </form>
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