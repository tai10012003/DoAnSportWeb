<?php include 'includes/header.php'; ?>

<div class="blog-hero text-center text-white">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">Blog & Tin Tức</h1>
        <p class="lead mb-0">Cập nhật những tin tức mới nhất về công nghệ và sản phẩm</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="row">
                <!-- Blog Card -->
                <?php for($i = 1; $i <= 6; $i++): ?>
                <div class="col-md-6 mb-4">
                    <div class="blog-card">
                        <div class="blog-image">
                            <img src="images/blog/blog-<?php echo $i; ?>.jpg" alt="Blog Image">
                            <div class="blog-date">24 Tháng 3, 2024</div>
                        </div>
                        <div class="blog-content">
                            <a href="#" class="blog-category">Công nghệ</a>
                            <h3 class="blog-title">
                                <a href="blog_detail.php">10 Xu hướng công nghệ nổi bật năm 2024</a>
                            </h3>
                            <p class="blog-excerpt">
                                Khám phá những xu hướng công nghệ mới nhất đang định hình tương lai của ngành công nghệ thông tin...
                            </p>
                            <div class="blog-meta">
                                <div class="blog-meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>Admin</span>
                                </div>
                                <div class="blog-meta-item">
                                    <i class="fas fa-comments"></i>
                                    <span>24 bình luận</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <!-- Pagination -->
            <nav class="blog-pagination">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link active" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Search Widget -->
            <div class="sidebar-widget">
                <div class="search-widget">
                    <input type="text" class="search-input" placeholder="Tìm kiếm bài viết...">
                    <button class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Categories Widget -->
            <div class="sidebar-widget">
                <h4 class="widget-title">Danh mục</h4>
                <ul class="category-list">
                    <li class="category-item">
                        <a href="#">Công nghệ</a>
                        <span class="category-count">15</span>
                    </li>
                    <li class="category-item">
                        <a href="#">Tin tức</a>
                        <span class="category-count">23</span>
                    </li>
                    <li class="category-item">
                        <a href="#">Hướng dẫn</a>
                        <span class="category-count">18</span>
                    </li>
                    <li class="category-item">
                        <a href="#">Đánh giá</a>
                        <span class="category-count">12</span>
                    </li>
                </ul>
            </div>

            <!-- Recent Posts Widget -->
            <div class="sidebar-widget">
                <h4 class="widget-title">Bài viết gần đây</h4>
                <div class="recent-posts">
                    <?php for($i = 1; $i <= 4; $i++): ?>
                    <div class="recent-post-item">
                        <div class="recent-post-image">
                            <img src="images/blog/recent-<?php echo $i; ?>.jpg" alt="Recent Post">
                        </div>
                        <div class="recent-post-info">
                            <h5><a href="blog_detail.php">Đánh giá laptop gaming mới nhất 2024</a></h5>
                            <span class="recent-post-date">24 Tháng 3, 2024</span>
                        </div>
                    </div>
                    <?php endfor; ?>
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
