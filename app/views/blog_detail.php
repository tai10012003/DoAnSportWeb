<?php include 'includes/header.php';
$post = $data['post'];
$relatedPosts = $data['relatedPosts'];
?>

<article class="article-detail">
    <div class="article-detail__banner">
        <div class="article-detail__image">
            <img src="/WebbandoTT/public/uploads/blogs/<?= htmlspecialchars($post['hinh_anh']) ?>" 
                 alt="<?= htmlspecialchars($post['tieu_de']) ?>">
            <div class="article-detail__overlay"></div>
        </div>
        
        <div class="article-detail__header">
            <div class="container">
                <div class="article-detail__meta">
                    <div class="article-detail__author">
                        <div class="author-info">
                            <span class="author-name"><?= htmlspecialchars($post['ten_tac_gia']) ?></span>
                            <time class="publish-date"><?= date('d/m/Y', strtotime($post['created_at'])) ?></time>
                        </div>
                    </div>
                    <div class="article-detail__stats">
                        <span class="stats-item">
                            <i class="far fa-eye"></i>
                            <?= number_format($post['luot_xem']) ?> lượt xem
                        </span>
                        <span class="stats-item">
                            <i class="far fa-clock"></i>
                            <?= ceil(str_word_count(strip_tags($post['noi_dung'])) / 200) ?> phút đọc
                        </span>
                    </div>
                </div>
                <h2 class="article-detail__title"><?= htmlspecialchars($post['tieu_de']) ?></h2>
                <?php if (!empty($post['mo_ta_ngan'])): ?>
                    <p class="article-detail__excerpt"><?= htmlspecialchars($post['mo_ta_ngan']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="article-detail__content">
        <div class="container">
            <div class="article-detail__body">
                <div class="article-content">
                    <?= $post['noi_dung'] ?>
                </div>

                <div class="article-detail__share">
                    <span class="share-label">Chia sẻ bài viết:</span>
                    <div class="share-buttons">
                        <a href="#" class="share-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="share-btn twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="share-btn linkedin">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($relatedPosts)): ?>
    <section class="article-detail__related">
        <div class="container">
            <h2 class="section-title mt-3 mb-5">BÀI VIẾT LIÊN QUAN</h2>
            <div class="row g-4">
                <?php 
                foreach ($relatedPosts as $post): ?>
                    <div class="col-md-6 col-lg-4">
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
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</article>

<?php include 'includes/footer.php'; ?>
