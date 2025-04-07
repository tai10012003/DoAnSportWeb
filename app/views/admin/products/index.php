<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/ProductController.php';
checkAdminAuth();

$route = $_SERVER['REQUEST_URI'];
$route = str_replace('/WebbandoTT', '', $route);

$productController = new ProductController();
$data = $productController->index();

$products = $data['products'];
$categories = $data['categories'];
$brands = $data['brands'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm - Sport Elite</title>
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
                        <h4>Quản lý sản phẩm</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Sản phẩm</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="/WebbandoTT/admin/products/create" class="btn btn-add-product">
                        <i class='bx bx-plus'></i>
                        <span>Thêm sản phẩm</span>
                    </a>
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-item">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['ten_danh_muc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-item">
                    <select class="form-select" id="brandFilter">
                        <option value="">Tất cả thương hiệu</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['ten_thuong_hieu']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-item">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class='bx bx-search'></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" 
                               placeholder="Tìm kiếm sản phẩm..." id="searchProduct">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá bán</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <div class="product-info-cell">
                                            <div class="product-img-wrapper">
                                                <?php 
                                                $imagePath = $product['hinh_anh'] 
                                                    ? "/WebbandoTT/public/uploads/products/" . $product['hinh_anh']
                                                    : "/WebbandoTT/app/public/images/products/no-image.jpg";
                                                ?>
                                                <img src="<?= htmlspecialchars($imagePath) ?>" 
                                                     alt="<?= htmlspecialchars($product['ten_sp']) ?>">
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($product['ten_sp']) ?></div>
                                                <div class="text-muted small">SKU: <?= htmlspecialchars($product['ma_sp']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($product['ten_danh_muc'] ?? 'Chưa phân loại') ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= number_format($product['gia'], 0, ',', '.') ?>₫</div>
                                        <?php if ($product['gia_khuyen_mai']): ?>
                                            <div class="text-danger small"><?= number_format($product['gia_khuyen_mai'], 0, ',', '.') ?>₫</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $product['so_luong'] ?></td>
                                    <td>
                                        <span class="status-badge <?= $product['tinh_trang'] == 1 ? 'in-stock' : 'out-of-stock' ?>">
                                            <?= $product['tinh_trang'] == 1 ? 'Còn bán' : 'Ngừng bán' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/WebbandoTT/admin/products/edit?id=<?= $product['id'] ?>" 
                                               class="btn-action" 
                                               title="Sửa">
                                                <i class='bx bx-edit-alt'></i>
                                            </a>
                                            <button class="btn-action delete delete-product" 
                                                    data-id="<?= $product['id'] ?>" 
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
                                    <div class="text-muted">Không có sản phẩm nào</div>
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
    <script src="/WebbandoTT/app/public/js/admin/products.js"></script>
</body>
</html>
