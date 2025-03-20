<?php include 'includes/header.php'; ?>

<div class="blog-detail-hero">
    <img src="images/blog/blog-detail-hero.jpg" alt="Blog Detail" class="blog-detail-image">
    <div class="blog-detail-header">
        <div class="container">
            <div class="blog-detail-meta">
                <div class="blog-meta-item">
                    <i class="fas fa-user"></i>
                    <span>Admin</span>
                </div>
                <div class="blog-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>24 Tháng 3, 2024</span>
                </div>
                <div class="blog-meta-item">
                    <i class="fas fa-comments"></i>
                    <span>24 bình luận</span>
                </div>
                <div class="blog-meta-item">
                    <i class="fas fa-folder"></i>
                    <span>Công nghệ</span>
                </div>
            </div>
            <h1 class="blog-detail-title">10 Xu hướng công nghệ nổi bật năm 2024</h1>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="blog-detail-content">
                <p class="lead">
                    Năm 2024 hứa hẹn sẽ mang đến nhiều đột phá trong lĩnh vực công nghệ. Hãy cùng điểm qua những xu hướng nổi bật nhất.
                </p>

                <h2>1. Trí tuệ nhân tạo (AI) và Machine Learning</h2>
                <p>
                    AI và Machine Learning tiếp tục là xu hướng dẫn đầu trong năm 2024. Các ứng dụng AI ngày càng thông minh hơn và được tích hợp sâu vào cuộc sống hàng ngày...
                </p>
                
                <img src="images/blog/content-1.jpg" alt="AI Technology">

                <h2>2. Internet of Things (IoT)</h2>
                <p>
                    IoT đang phát triển mạnh mẽ với sự xuất hiện của nhiều thiết bị thông minh và kết nối. Các giải pháp smart home ngày càng phổ biến...
                </p>

                <blockquote>
                    "Công nghệ đang thay đổi cách chúng ta sống, làm việc và kết nối. Năm 2024 sẽ là năm của những đột phá công nghệ đáng kinh ngạc."
                </blockquote>

                <!-- Social Share -->
                <div class="social-share">
                    <a href="#" class="share-button share-facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>Share</span>
                    </a>
                    <a href="#" class="share-button share-twitter">
                        <i class="fab fa-twitter"></i>
                        <span>Tweet</span>
                    </a>
                    <a href="#" class="share-button share-linkedin">
                        <i class="fab fa-linkedin-in"></i>
                        <span>Share</span>
                    </a>
                </div>

                <!-- Author Box -->
                <div class="author-box">
                    <div class="author-image">
                        <img src="images/author.jpg" alt="Author">
                    </div>
                    <div class="author-info">
                        <h4>John Doe</h4>
                        <p class="author-bio">
                            Chuyên gia công nghệ với hơn 10 năm kinh nghiệm. Thường xuyên viết về các xu hướng công nghệ mới nhất.
                        </p>
                        <div class="author-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <h3>Bình luận (24)</h3>
                    <div class="comment-list">
                        <?php for($i = 1; $i <= 3; $i++): ?>
                        <div class="comment-item">
                            <div class="comment-avatar">
                                <img src="images/users/user-<?php echo $i; ?>.jpg" alt="User">
                            </div>
                            <div class="comment-content">
                                <div class="comment-header">
                                    <h5 class="comment-author">Người dùng <?php echo $i; ?></h5>
                                    <span class="comment-date">2 giờ trước</span>
                                </div>
                                <p class="comment-text">
                                    Bài viết rất hay và bổ ích. Cảm ơn tác giả đã chia sẻ thông tin!
                                </p>
                                <a href="#" class="comment-reply">
                                    <i class="fas fa-reply"></i>
                                    <span>Trả lời</span>
                                </a>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Comment Form -->
                    <div class="comment-form">
                        <h4>Để lại bình luận</h4>
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Họ tên</label>
                                    <input type="text" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội dung</label>
                                <textarea class="form-control" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="blog-sidebar">
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
                    <h4 class="widget-title">Bài viết liên quan</h4>
                    <div class="recent-posts">
                        <?php for($i = 1; $i <= 4; $i++): ?>
                        <div class="recent-post-item">
                            <div class="recent-post-image">
                                <img src="images/blog/recent-<?php echo $i; ?>.jpg" alt="Recent Post">
                            </div>
                            <div class="recent-post-info">
                                <h5><a href="#">Đánh giá laptop gaming mới nhất 2024</a></h5>
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

    <!-- Related Posts -->
    <div class="related-posts">
        <h3 class="section-title">Bài viết liên quan</h3>
        <div class="row">
            <?php for($i = 1; $i <= 3; $i++): ?>
            <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-image">
                        <img src="images/blog/related-<?php echo $i; ?>.jpg" alt="Related Post">
                        <div class="blog-date">24 Tháng 3, 2024</div>
                    </div>
                    <div class="blog-content">
                        <a href="#" class="blog-category">Công nghệ</a>
                        <h3 class="blog-title">
                            <a href="#">Top 5 laptop gaming đáng mua nhất 2024</a>
                        </h3>
                        <div class="blog-meta">
                            <div class="blog-meta-item">
                                <i class="fas fa-user"></i>
                                <span>Admin</span>
                            </div>
                            <div class="blog-meta-item">
                                <i class="fas fa-comments"></i>
                                <span>15 bình luận</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
