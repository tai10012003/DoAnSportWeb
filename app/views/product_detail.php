<?php
include 'includes/header.php';
require_once __DIR__ . '/../config/database.php';

// Lấy mã sản phẩm từ URL
$ma_sp = basename($_SERVER['REQUEST_URI']);

// Kết nối database và lấy thông tin sản phẩm
$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
        FROM san_pham sp
        LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
        LEFT JOIN thuong_hieu th ON sp.thuong_hieu_id = th.id
        WHERE sp.ma_sp = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$ma_sp]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Cập nhật lượt xem
$update_sql = "UPDATE san_pham SET luot_xem = luot_xem + 1 WHERE ma_sp = ?";
$stmt = $conn->prepare($update_sql);
$stmt->execute([$ma_sp]);
?>

<div class="product-detail">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/WebbandoTT">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/WebbandoTT/san-pham">Sản phẩm</a></li>
                <li class="breadcrumb-item"><?= htmlspecialchars($product['ten_danh_muc']) ?></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($product['ten_sp']) ?></li>
            </ol>
        </nav>

        <div class="row">
            <!-- Gallery Section -->
            <div class="col-lg-5">
                <div class="detail-gallery">
                    <div class="detail-main-image">
                        <img src="/WebbandoTT/public/uploads/products/<?= htmlspecialchars($product['hinh_anh']) ?>" 
                             alt="<?= htmlspecialchars($product['ten_sp']) ?>" 
                             id="main-product-image">
                    </div>
                    <div class="detail-thumbnails">
                        <?php
                        $images = [$product['hinh_anh']];
                        foreach ($images as $index => $img): 
                        ?>
                        <img src="/WebbandoTT/public/uploads/products/<?= htmlspecialchars($img) ?>" 
                             alt="Thumbnail <?= $index + 1 ?>"
                             class="detail-thumbnail <?= $index === 0 ? 'active' : '' ?>"
                             onclick="changeMainImage(this.src)">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="col-lg-7">
                <div class="detail-info-wrapper">
                    <h1 class="detail-product-title"><?= htmlspecialchars($product['ten_sp']) ?></h1>

                    <div class="detail-meta-info">
                        <div class="detail-meta-item">
                            <span class="detail-meta-label">Mã sản phẩm:</span>
                            <span><?= htmlspecialchars($product['ma_sp']) ?></span>
                        </div>
                        <div class="detail-meta-item">
                            <span class="detail-meta-label">Danh mục:</span>
                            <span><?= htmlspecialchars($product['ten_danh_muc']) ?></span>
                        </div>
                        <div class="detail-meta-item">
                            <span class="detail-meta-label">Thương hiệu:</span>
                            <span><?= htmlspecialchars($product['ten_thuong_hieu']) ?></span>
                        </div>
                        <div class="detail-meta-item">
                            <span class="detail-meta-label">Lượt xem:</span>
                            <span><?= number_format($product['luot_xem']) ?></span>
                        </div>
                    </div>

                    <div class="detail-price-wrapper">
                        <?php if ($product['gia_khuyen_mai'] > 0): ?>
                            <span class="detail-current-price"><?= number_format($product['gia_khuyen_mai']) ?>₫</span>
                            <span class="detail-old-price"><?= number_format($product['gia']) ?>₫</span>
                            <span class="detail-discount">
                                -<?= round((($product['gia'] - $product['gia_khuyen_mai']) / $product['gia']) * 100) ?>%
                            </span>
                        <?php else: ?>
                            <span class="detail-current-price"><?= number_format($product['gia']) ?>₫</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($product['tinh_trang'] && $product['so_luong'] > 0): ?>
                    <form class="detail-add-cart-form">
                        <div class="detail-quantity-control">
                            <label class="detail-quantity-label">Số lượng:</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity('decrease')">-</button>
                                <input style="padding: 0;" type="number" class="form-control text-center" id="quantity" name="quantity" 
                                       value="1" min="1" max="<?= $product['so_luong'] ?>">
                                <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity('increase')">+</button>
                            </div>
                            <span class="detail-stock-info">(Còn <?= $product['so_luong'] ?> sản phẩm)</span>
                        </div>
                        
                        <div class="detail-buttons">
                            <button type="submit" class="detail-add-to-cart">
                                <i class="bi bi-cart-plus"></i>
                                Thêm vào giỏ hàng
                            </button>
                            <button type="button" class="detail-add-to-wishlist">
                                <i class="bi bi-heart"></i>
                                Thêm vào yêu thích
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>

                    <div class="detail-social-share">
                        <a href="#" class="detail-social-btn detail-facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="detail-social-btn detail-twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="detail-social-btn detail-pinterest"><i class="bi bi-pinterest"></i></a>
                    </div>

                    <div class="detail-short-desc">
                        <h5 class="detail-short-desc-title">Mô tả sản phẩm</h5>
                        <div class="detail-short-desc-content">
                            <?= nl2br(htmlspecialchars($product['mo_ta'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="detail-tabs">
            <ul class="nav nav-tabs detail-tab-nav" role="tablist">
                <li class="nav-item">
                    <a class="nav-link detail-tab-link active" data-bs-toggle="tab" href="#description" style="border: none">Mô tả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link detail-tab-link" data-bs-toggle="tab" href="#specifications" style="border: none">Thông số kỹ thuật</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link detail-tab-link" data-bs-toggle="tab" href="#reviews" style="border: none">Đánh giá</a>
                </li>
            </ul>
            <div class="detail-tab-content">
                <div class="tab-content">
                    <div id="description" class="tab-pane fade show active">
                        <div class="detail-description">
                            <?= nl2br(htmlspecialchars($product['mo_ta_chi_tiet'])) ?>
                        </div>
                    </div>

                    <div id="specifications" class="tab-pane fade">
                        <table class="table table-striped">
                            <tbody>
                                <!-- Thêm thông số kỹ thuật chi tiết -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="reviews" class="tab-pane fade">
                        <div class="review-form mb-4">
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Đánh giá của bạn</label>
                                    <div class="rating-input">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                            <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>">
                                            <label for="star<?= $i ?>"><i class="bi bi-star-fill"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nhận xét của bạn</label>
                                    <textarea class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                            </form>
                        </div>
                            <!-- Reviews List -->
                            <div class="reviews-list">
                                <!-- Sample Review -->
                                <div class="review-item">
                                    <div class="review-header">
                                        <span class="reviewer-name">Nguyễn Văn A</span>
                                        <span class="review-date">20/11/2023</span>
                                    </div>
                                    <div class="star-rating">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <p class="review-content">
                                        Sản phẩm rất tốt, chất lượng cao, đóng gói cẩn thận...
                                    </p>
                                </div>
                            </div>
                        <?php else: ?></div>
                            <div class="alert alert-info">
                                Vui lòng <a href="/WebbandoTT/dang-nhap">đăng nhập</a> để viết đánh giá
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="related-products">
            <h3 class="section-title">SẢN PHẨM LIÊN QUAN</h3>
            <div class="row g-4">
                <?php
                $sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu,
                        CASE
                            WHEN sp.gia_khuyen_mai > 0 THEN ((sp.gia - sp.gia_khuyen_mai) / sp.gia * 100)
                            ELSE 0
                        END as phan_tram_giam
                        FROM san_pham sp
                        LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                        LEFT JOIN thuong_hieu th ON sp.thuong_hieu_id = th.id
                        WHERE (sp.danh_muc_id = ? OR sp.thuong_hieu_id = ?) 
                        AND sp.ma_sp != ? 
                        AND sp.tinh_trang = 1
                        ORDER BY sp.luot_xem DESC 
                        LIMIT 4";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute([$product['danh_muc_id'], $product['thuong_hieu_id'], $product['ma_sp']]);
                while($related = $stmt->fetch()): 
                    $imagePath = $related['hinh_anh'] 
                        ? "/WebbandoTT/public/uploads/products/" . $related['hinh_anh']
                        : "/WebbandoTT/app/public/images/no-image.jpg";
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="product-card">
                            <?php if($related['gia_khuyen_mai'] > 0): ?>
                                <div class="product-badge">
                                    -<?= round($related['phan_tram_giam']) ?>%
                                </div>
                            <?php endif; ?>
                            <div class="product-image">
                                <a href="/WebbandoTT/san-pham/<?= htmlspecialchars($related['ma_sp']) ?>" class="product-link">
                                    <img src="<?= htmlspecialchars($imagePath) ?>" 
                                         alt="<?= htmlspecialchars($related['ten_sp']) ?>"
                                         class="img-fluid">
                                </a>
                                <div class="product-actions">
                                    <button class="btn btn-light btn-sm add-to-cart" 
                                            data-product-id="<?= $related['id'] ?>"
                                            data-product-name="<?= htmlspecialchars($related['ten_sp']) ?>"
                                            data-product-price="<?= $related['gia_khuyen_mai'] ?: $related['gia'] ?>">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm btn-wishlist">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                    <a href="/WebbandoTT/san-pham/<?= htmlspecialchars($related['ma_sp']) ?>" 
                                       class="btn btn-light btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="product-info">
                                <a href="/WebbandoTT/san-pham/<?= htmlspecialchars($related['ma_sp']) ?>" 
                                   class="text-decoration-none">
                                    <h3 class="product-title"><?= htmlspecialchars($related['ten_sp']) ?></h3>
                                </a>
                                <div class="product-category">
                                    <?= htmlspecialchars($related['ten_danh_muc']) ?> | 
                                    <?= htmlspecialchars($related['ten_thuong_hieu']) ?>
                                </div>
                                <div class="product-price">
                                    <?php if($related['gia_khuyen_mai'] > 0): ?>
                                        <span class="price-new"><?= number_format($related['gia_khuyen_mai']) ?>₫</span>
                                        <span class="price-old"><?= number_format($related['gia']) ?>₫</span>
                                    <?php else: ?>
                                        <span class="price-new"><?= number_format($related['gia']) ?>₫</span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-rating">
                                    <div class="stars">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo $i <= 4.5 ? '-fill' : ($i - 4.5 <= 0.5 ? '-half' : ''); ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-count">(4.5)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/WebbandoTT/app/public/js/main.js"></script>
<script>
function changeMainImage(src) {
    document.getElementById('main-product-image').src = src;
    document.querySelectorAll('.detail-thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
        if (thumb.src === src) thumb.classList.add('active');
    });
}

function updateQuantity(action) {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const max = parseInt(input.max);
    
    if (action === 'increase' && currentValue < max) {
        input.value = currentValue + 1;
    } else if (action === 'decrease' && currentValue > 1) {
        input.value = currentValue - 1;
    }
}

document.querySelector('.detail-add-cart-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
});
</script>

<?php include 'includes/footer.php'; ?>