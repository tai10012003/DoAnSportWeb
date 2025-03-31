<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
checkAdminAuth();

$userController = new UserController();
$data = $userController->index();
$users = $data['users'];
$totalPages = $data['totalPages'];
$currentPage = $data['currentPage'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng - Sport Elite</title>
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
                        <h4>Quản lý người dùng</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Người dùng</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="/WebbandoTT/admin/users/create" class="btn btn-add-product">
                        <i class='bx bx-plus'></i>
                        <span>Thêm người dùng</span>
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
                               placeholder="Tìm kiếm người dùng..." id="searchUser">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['ho_ten']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['so_dien_thoai'] ?? 'Chưa cập nhật') ?></td>
                                    <td>
                                        <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                            <?= $user['role'] === 'admin' ? 'Quản trị viên' : 'Người dùng' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge <?= $user['trang_thai'] == 1 ? 'in-stock' : 'out-of-stock' ?>">
                                            <?= $user['trang_thai'] == 1 ? 'Hoạt động' : 'Tạm khóa' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/WebbandoTT/admin/users/edit?id=<?= $user['id'] ?>" 
                                               class="btn-action" title="Sửa">
                                                <i class='bx bx-edit-alt'></i>
                                            </a>
                                            <button class="btn-action delete delete-user" 
                                                    data-id="<?= $user['id'] ?>" 
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
                                    <div class="text-muted">Không có người dùng nào</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-end mb-0">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/WebbandoTT/app/public/js/admin/users.js"></script>
</body>
</html>
