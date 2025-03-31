<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/OrderController.php';
checkAdminAuth();

$orderController = new OrderController();
$formData = $orderController->getDataForOrderForm();
$users = $formData['users'];
$payment_methods = $formData['payment_methods'];

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    header('Location: /WebbandoTT/admin/orders');
    exit;
}

$data = $orderController->getOrderById($orderId);
$order = $data['order'];

if (!$order) {
    header('Location: /WebbandoTT/admin/orders');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật đơn hàng - Sport Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>

        <div class="dashboard-content">
            <div class="content-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Cập nhật đơn hàng</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/orders">Đơn hàng</a></li>
                                <li class="breadcrumb-item active">Cập nhật</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="updateOrderForm" class="form-product">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($order['id']) ?>">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-section">
                                    <h5 class="form-section-title">Thông tin đơn hàng</h5>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Mã đơn hàng</label>
                                            <input type="text" class="form-control" name="ma_don_hang"
                                                value="<?= htmlspecialchars($order['ma_don_hang']) ?>" readonly>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="user_id">Khách hàng</label>
                                            <select class="form-select" name="user_id" id="user_id" required>
                                                <option value="">Chọn khách hàng</option>
                                                <?php foreach ($users as $user): ?>
                                                    <option value="<?= $user['id'] ?>" <?= ($order['user_id'] == $user['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($user['ho_ten']) ?> (<?= htmlspecialchars($user['email']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Ghi chú</label>
                                            <textarea class="form-control" name="ghi_chu" rows="3"><?= htmlspecialchars($order['ghi_chu']) ?></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Địa chỉ giao hàng</label>
                                            <textarea class="form-control" name="dia_chi" rows="2" required><?= htmlspecialchars($order['dia_chi']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-section">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tổng tiền</label>
                                            <input type="number" class="form-control" name="tong_tien" step="0.01"
                                                value="<?= htmlspecialchars($order['tong_tien']) ?>" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Phí vận chuyển</label>
                                            <input type="number" class="form-control" name="phi_van_chuyen" step="0.01"
                                                value="<?= htmlspecialchars($order['phi_van_chuyen']) ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="payment_method">Phương thức thanh toán</label>
                                        <select class="form-select" name="payment_method" id="payment_method" required>
                                            <?php foreach ($payment_methods as $method): ?>
                                                <option value="<?= $method ?>" <?= ($order['payment_method'] == $method) ? 'selected' : '' ?>>
                                                    <?= $method ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Trạng thái đơn hàng</label>
                                        <select class="form-select" name="trang_thai">
                                            <option value="pending" <?= $order['trang_thai'] === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                            <option value="processing" <?= $order['trang_thai'] === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                            <option value="shipped" <?= $order['trang_thai'] === 'shipped' ? 'selected' : '' ?>>Đang giao hàng</option>
                                            <option value="completed" <?= $order['trang_thai'] === 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
                                            <option value="cancelled" <?= $order['trang_thai'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class='bx bx-arrow-back'></i> Quay lại
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Cập nhật đơn hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('updateOrderForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            try {
                const formData = new FormData(this);
                const response = await fetch('/WebbandoTT/app/api/orders/update.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Cập nhật đơn hàng thành công!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '/WebbandoTT/admin/orders';
                    });
                } else {
                    throw new Error(result.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: error.message || 'Không thể cập nhật đơn hàng'
                });
            }
        });
    </script>
</body>
</html>