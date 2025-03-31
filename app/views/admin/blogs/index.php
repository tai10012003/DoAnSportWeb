<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/BlogController.php';
checkAdminAuth();

$blogController = new BlogController();
$data = $blogController->index();
$posts = $data['posts'];
$totalPages = $data['totalPages'];
$currentPage = $data['currentPage'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý bài viết - Sport Elite</title>
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
                        <h4>Quản lý bài viết</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Bài viết</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="/WebbandoTT/admin/blogs/create" class="btn btn-add-product">
                        <i class='bx bx-plus'></i>
                        <span>Thêm bài viết</span>
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
                               placeholder="Tìm kiếm bài viết..." id="searchBlog">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bài viết</th>
                            <th>Tác giả</th>
                            <th>Lượt xem</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td>
                                        <div class="product-info-cell">
                                            <div class="product-img-wrapper">
                                                <?php 
                                                $imagePath = $post['hinh_anh'] 
                                                    ? "/WebbandoTT/public/uploads/blogs/" . $post['hinh_anh']
                                                    : "/WebbandoTT/app/public/images/no-image.jpg";
                                                ?>
                                                <img src="<?= htmlspecialchars($imagePath) ?>" 
                                                     alt="<?= htmlspecialchars($post['tieu_de']) ?>">
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($post['tieu_de']) ?></div>
                                                <div class="text-muted small">Slug: <?= htmlspecialchars($post['slug']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($post['ten_tac_gia']) ?></td>
                                    <td><?= number_format($post['luot_xem']) ?></td>
                                    <td>
                                        <span class="status-badge <?= $post['trang_thai'] == 1 ? 'in-stock' : 'out-of-stock' ?>">
                                            <?= $post['trang_thai'] == 1 ? 'Công khai' : 'Riêng tư' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/WebbandoTT/admin/blogs/edit?id=<?= $post['id'] ?>" 
                                               class="btn-action" title="Sửa">
                                                <i class='bx bx-edit-alt'></i>
                                            </a>
                                            <button class="btn-action delete delete-post" 
                                                    data-id="<?= $post['id'] ?>" 
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
                                    <div class="text-muted">Không có bài viết nào</div>
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
    <script src="/WebbandoTT/app/public/js/admin/blogs.js"></script>
</body>
</html>
