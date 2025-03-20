<?php
require_once __DIR__ . '/../../middleware/auth_middleware.php';
checkAdminAuth();
$route = $_SERVER['REQUEST_URI'];
$route = str_replace('/WebbandoTT', '', $route);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản trị - Sport Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <div class="dashboard-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Tổng quan hệ thống</h4>
                    <p class="text-muted mb-0">Xin chào, <?php echo htmlspecialchars($_SESSION['ho_ten']); ?></p>
                </div>
                <div>
                    <button class="btn btn-light" id="refreshStats">
                        <i class='bx bx-refresh'></i> Làm mới
                    </button>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Tổng sản phẩm</h6>
                                <h3 class="mb-0">150</h3>
                                <p class="small text-success mb-0">
                                    <i class='bx bx-up-arrow-alt'></i> +12% tuần này
                                </p>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10">
                                <i class='bx bxs-shopping-bag text-primary'></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Đơn hàng mới</h6>
                                <h3 class="mb-0">25</h3>
                                <p class="small text-success mb-0">
                                    <i class='bx bx-up-arrow-alt'></i> +5% hôm nay
                                </p>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10">
                                <i class='bx bxs-cart text-warning'></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Doanh thu tháng</h6>
                                <h3 class="mb-0">52.5M</h3>
                                <p class="small text-success mb-0">
                                    <i class='bx bx-up-arrow-alt'></i> +8% tháng này
                                </p>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10">
                                <i class='bx bx-money text-success'></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Khách hàng mới</h6>
                                <h3 class="mb-0">45</h3>
                                <p class="small text-success mb-0">
                                    <i class='bx bx-up-arrow-alt'></i> +15% tuần này
                                </p>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10">
                                <i class='bx bxs-user-plus text-info'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Đơn hàng mới nhất</h5>
                        <a href="#" class="btn btn-primary btn-sm">
                            <i class='bx bx-list-ul'></i> Xem tất cả
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dữ liệu đơn hàng sẽ được thêm vào đây -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
