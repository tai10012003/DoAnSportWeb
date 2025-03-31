<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/OrderController.php';
checkAdminAuth();

$route = $_SERVER['REQUEST_URI'];
$route = str_replace('/WebbandoTT', '', $route);

$orderController = new OrderController();
$data = $orderController->getAllOrdersNoPagination();
$orders = $data['orders'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng - Sport Elite</title>
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
                        <h4>Quản lý đơn hàng</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Đơn hàng</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="/WebbandoTT/admin/orders/create" class="btn btn-add-product">
                        <i class='bx bx-plus'></i>
                        <span>Tạo đơn hàng</span>
                    </a>
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-item">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class='bx bx-search'></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" 
                               placeholder="Tìm kiếm đơn hàng ..." id="searchOrder">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>PT Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($order['ma_don_hang']) ?></strong></td>
                                    <td>
                                        <div>
                                            <?= htmlspecialchars($order['ho_ten']) ?><br>
                                            <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                                        </div>
                                    </td>
                                    <td><?= number_format($order['tong_tien'], 0, ',', '.') ?>đ</td>
                                    <td><?= htmlspecialchars($order['payment_method']) ?></td>
                                    <td>
                                        <span class="status-badge 
                                            <?= match ($order['trang_thai']) {
                                                'pending' => 'badge bg-warning',
                                                'processing' => 'badge bg-info',
                                                'shipped' => 'badge bg-primary',
                                                'completed' => 'badge bg-success',
                                                'cancelled' => 'badge bg-danger',
                                                default => 'badge bg-secondary'
                                            } ?>">
                                            <?= match ($order['trang_thai']) {
                                                'pending' => 'Chờ xác nhận',
                                                'processing' => 'Đang xử lý',
                                                'shipped' => 'Đang giao hàng',
                                                'completed' => 'Đã hoàn thành',
                                                'cancelled' => 'Đã hủy',
                                                default => 'Không xác định'
                                            } ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/WebbandoTT/admin/orders/edit?id=<?= $order['id'] ?>" 
                                               class="btn-action" title="Sửa">
                                                <i class='bx bx-edit-alt'></i>
                                            </a>
                                            <button class="btn-action delete delete-order" 
                                                    data-id="<?= $order['id'] ?>" 
                                                    title="Xóa">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">Không có đơn hàng nào</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Xoá đơn hàng
        document.querySelectorAll('.delete-order').forEach(button => {
            button.addEventListener('click', async () => {
                const id = button.getAttribute('data-id');

                const confirm = await Swal.fire({
                    title: 'Xác nhận xoá?',
                    text: 'Bạn có chắc muốn xoá đơn hàng này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xoá',
                    cancelButtonText: 'Huỷ'
                });

                if (confirm.isConfirmed) {
                    try {
                        const response = await fetch(`/WebbandoTT/app/api/orders/delete.php?id=${id}`, {
                            method: 'POST'
                        });

                        const result = await response.json();
                        if (result.success) {
                            Swal.fire('Đã xoá', 'Đơn hàng đã được xoá thành công', 'success')
                                .then(() => location.reload());
                        } else {
                            throw new Error(result.message);
                        }
                    } catch (error) {
                        Swal.fire('Lỗi', error.message || 'Không thể xoá đơn hàng', 'error');
                    }
                }
            });
        });
    </script>
</body>
</html>