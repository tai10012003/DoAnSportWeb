<?php
include 'includes/header.php';
require_once __DIR__ . '/../controllers/BlogController.php';

$blogController = new BlogController();
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$data = $blogController->index($currentPage);
$posts = $data['posts'];
$totalPages = $data['totalPages'];
?>



<div class="blog-hero text-center text-white">
    <div class="container">
        <h3 class="display-5 fw-bold mb-3">TIN TỨC & BÀI VIẾT</h3>
        <p class="lead mb-0">Cập nhật những tin tức mới nhất về công nghệ và sản phẩm</p>
    </div>
</div>
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <?php if (!empty($posts)): ?>
                <div class="row g-4">
                    <?php foreach ($posts as $post): ?>
                        <?php if ($post['trang_thai'] == 1): ?>
                            <div class="col-md-6">
                                <div class="blog-card">
                                    <div class="blog-image">
                                        <a href="/WebbandoTT/bai-viet/<?= htmlspecialchars($post['slug']) ?>">
                                            <?php 
                                            $imagePath = $post['hinh_anh'] 
                                                ? "/WebbandoTT/public/uploads/blogs/" . $post['hinh_anh']
                                                : "/WebbandoTT/app/public/images/no-image.jpg";
                                            ?>
                                            <img src="<?= htmlspecialchars($imagePath) ?>" 
                                                 alt="<?= htmlspecialchars($post['tieu_de']) ?>"
                                                 class="img-fluid w-100">
                                        </a>
                                    </div>
                                    <div class="blog-content p-4">
                                        <div class="blog-meta text-muted small mb-2">
                                            <span><i class="bi bi-person"></i> <?= htmlspecialchars($post['ten_tac_gia']) ?></span>
                                            <span class="mx-2">|</span>
                                            <span><i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                                            <span class="mx-2">|</span>
                                            <span><i class="bi bi-eye"></i> <?= number_format($post['luot_xem']) ?> lượt xem</span>
                                        </div>
                                        <h3 class="blog-title">
                                            <a href="/WebbandoTT/bai-viet/<?= htmlspecialchars($post['slug']) ?>" 
                                               class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($post['tieu_de']) ?>
                                            </a>
                                        </h3>
                                        <p class="blog-excerpt text-muted">
                                            <?= htmlspecialchars(substr($post['mo_ta_ngan'], 0, 150)) ?>...
                                        </p>
                                        <a href="/WebbandoTT/bai-viet/<?= htmlspecialchars($post['slug']) ?>" 
                                           class="btn btn-outline-primary">
                                            Đọc thêm <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-5">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-journal-x display-1 text-muted"></i>
                    <p class="mt-3">Chưa có bài viết nào.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Search Widget -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Tìm kiếm</h5>
                    <form action="/WebbandoTT/bai-viet" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Tìm kiếm bài viết..." 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Posts Widget -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Bài viết mới nhất</h5>
                    <?php
                    $recentPosts = array_slice($posts, 0, 5);
                    foreach ($recentPosts as $post):
                    ?>
                        <?php if ($post['trang_thai'] == 1): ?>
                            <div class="recent-post d-flex mb-3">
                                <div class="flex-shrink-0" style="width: 80px;">
                                    <a href="/WebbandoTT/bai-viet/<?= htmlspecialchars($post['slug']) ?>">
                                        <?php 
                                        $imagePath = $post['hinh_anh'] 
                                            ? "/WebbandoTT/public/uploads/blogs/" . $post['hinh_anh']
                                            : "/WebbandoTT/app/public/images/no-image.jpg";
                                        ?>
                                        <img src="<?= htmlspecialchars($imagePath) ?>" 
                                            alt="<?= htmlspecialchars($post['tieu_de']) ?>"
                                            class="img-fluid rounded">
                                    </a>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2">
                                        <a href="/WebbandoTT/bai-viet/<?= htmlspecialchars($post['slug']) ?>" 
                                        class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($post['tieu_de']) ?>
                                        </a>
                                    </h6>
                                    <div class="small text-muted">
                                        <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Popular Posts Widget -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Bài viết phổ biến</h5>
                    <?php
                    // Sắp xếp lại mảng theo lượt xem
                    usort($posts, function($a, $b) {
                        return $b['luot_xem'] - $a['luot_xem'];
                    });
                    $popularPosts = array_slice($posts, 0, 5); // Lấy 5 bài viết có lượt xem cao nhất
                    foreach ($popularPosts as $post):
                    ?>
                        <?php if ($post['trang_thai'] == 1): ?>
                            <div class="popular-post d-flex mb-3">
                                <div class="flex-shrink-0" style="width: 80px;">
                                    <a href="/WebbandoTT/bai-viet/<?= htmlspecialchars($post['slug']) ?>">
                                        <?php 
                                        $imagePath = $post['hinh_anh'] 
                                            ? "/WebbandoTT/public/uploads/blogs/" . $post['hinh_anh']
                                            : "/WebbandoTT/app/public/images/no-image.jpg";
                                        ?>
                                        <img src="<?= htmlspecialchars($imagePath) ?>" 
                                            alt="<?= htmlspecialchars($post['tieu_de']) ?>"
                                            class="img-fluid rounded">
                                    </a>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2">
                                        <a href="/WebbandoTT/bai-viet/<?= htmlspecialchars($post['slug']) ?>" 
                                        class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($post['tieu_de']) ?>
                                        </a>
                                    </h6>
                                    <div class="small text-muted">
                                        <i class="bi bi-eye"></i> <?= number_format($post['luot_xem']) ?> lượt xem
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
             <!-- Tags Widget -->
             <div class="sidebar-widget">
                <h4 class="widget-title">Tags phổ biến</h4>
                <div class="blog-tags">
                    <a href="#" class="blog-tag">Công nghệ</a>
                    <a href="#" class="blog-tag">Laptop</a>
                    <a href="#" class="blog-tag">Gaming</a>
                    <a href="#" class="blog-tag">Review</a>
                    <a href="#" class="blog-tag">Thủ thuật</a>
                    <a href="#" class="blog-tag">Tin tức</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/WebbandoTT/app/public/js/main.js"></script>
<?php include 'includes/footer.php'; ?>