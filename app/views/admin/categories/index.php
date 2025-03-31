<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/CategoryController.php';
checkAdminAuth();

$route = $_SERVER['REQUEST_URI'];
$route = str_replace('/WebbandoTT', '', $route);

$categoryController = new CategoryController();
$data = $categoryController->index();

$categories = $data['categories'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục - Sport Elite</title>
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
                        <h4>Quản lý danh mục</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Danh mục</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="/WebbandoTT/admin/categories/create" class="btn btn-add-product">
                        <i class='bx bx-plus'></i>
                        <span>Thêm danh mục</span>
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
                               placeholder="Tìm kiếm danh mục..." id="searchCategory">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Danh mục</th>
                            <th>Mô tả</th>
                            <th>Danh mục cha</th>
                            <th>Thứ tự</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td>
                                        <div class="product-info-cell">
                                            <div class="product-img-wrapper">
                                                <?php 
                                                $imagePath = $category['hinh_anh'] 
                                                    ? "/WebbandoTT/public/uploads/categories/" . $category['hinh_anh']
                                                    : "/WebbandoTT/app/public/images/categories/no-image.jpg";
                                                ?>
                                                <img src="<?= htmlspecialchars($imagePath) ?>" 
                                                     alt="<?= htmlspecialchars($category['ten_danh_muc']) ?>">
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($category['ten_danh_muc']) ?></div>
                                                <div class="text-muted small">SKU: <?= htmlspecialchars($category['ma_danh_muc']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td> <?= htmlspecialchars($category['mo_ta'] ?? 'Chưa có mô tả') ?></td>
                                    <td><?= htmlspecialchars($category['danh_muc_cha_id'] ?? 'Chưa có danh mục cha') ?></td>
                                    <td><?= $category['thu_tu'] ?></td>
                                    <td>
                                        <span class="status-badge <?= $category['trang_thai'] == 1 ? 'in-stock' : 'out-of-stock' ?>">
                                            <?= $category['trang_thai'] == 1 ? 'Hiện' : 'Ẩn' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/WebbandoTT/admin/categories/edit?id=<?= $category['id'] ?>" 
                                               class="btn-action" 
                                               title="Sửa">
                                                <i class='bx bx-edit-alt'></i>
                                            </a>
                                            <button class="btn-action delete delete-category" 
                                                    data-id="<?= $category['id'] ?>" 
                                                    title="Xóa">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">Không có danh mục nào</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
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
    <script src="/WebbandoTT/app/public/js/admin/categories.js"></script>
</body>
</html>
