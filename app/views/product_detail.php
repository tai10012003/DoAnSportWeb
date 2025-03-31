<?php
include 'includes/header.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/ReviewController.php';

// Get product data first
$ma_sp = basename($_SERVER['REQUEST_URI']);
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

if (!$product) {
    header("Location: /WebbandoTT/404");
    exit;
}

// Now get reviews after we have the product
$reviewController = new ReviewController();
$reviews = $reviewController->getProductReviews($product['id']);
$avgRating = $reviewController->danhGiaModel->getAverageRating($product['id']);
$averageRating = round($avgRating['avg_rating'] ?? 0, 1);
$totalReviews = $avgRating['total_reviews'] ?? 0;

// Update views
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
                        <div class="detail-meta-item">
                            <span class="detail-meta-label">Đánh giá:</span>
                            <span class="d-flex align-items-center">
                                <div class="star-rating me-2">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?= $i <= $averageRating ? '-fill text-warning' : ($i - $averageRating <= 0.5 ? '-half text-warning' : '') ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span>(<?= $averageRating ?>/5 - <?= $totalReviews ?> đánh giá)</span>
                            </span>
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
                    <form action="/WebbandoTT/app/api/carts/add_to_cart.php" method="POST" class="detail-add-cart-form">
                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                        <div class="detail-quantity-control">
                            <label class="detail-quantity-label">Số lượng:</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity('decrease')">-</button>
                                <input style="padding: 0; border: 1px solid" type="number" class="form-control text-center" id="quantity" name="quantity" 
                                value="1" min="1" max="<?= $product['so_luong'] ?>">
                                <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity('increase')">+</button>
                            </div>
                            <span class="detail-stock-info">(Còn <?= $product['so_luong'] ?> sản phẩm)</span>
                        </div>

                        <div class="product-variations mt-4 mb-4">
                            <?php if (!empty($product['mau_sac'])): ?>
                            <div class="variation-group mb-4">
                                <label class="form-label">Màu sắc:</label>
                                <div class="btn-group" role="group">
                                    <?php 
                                    $colors = explode(',', $product['mau_sac']);
                                    foreach ($colors as $color): 
                                    ?>
                                        <input type="radio" class="btn-check" name="color" 
                                               id="color_<?= htmlspecialchars(trim($color)) ?>" 
                                               value="<?= htmlspecialchars(trim($color)) ?>" required>
                                        <label class="btn btn-outline-dark" style="margin-left: 20px" 
                                               for="color_<?= htmlspecialchars(trim($color)) ?>">
                                            <?= htmlspecialchars(trim($color)) ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($product['kich_thuoc'])): ?>
                            <div class="variation-group">
                                <label class="form-label">Kích thước:</label>
                                <div class="btn-group" role="group">
                                    <?php 
                                    $sizes = explode(',', $product['kich_thuoc']);
                                    foreach ($sizes as $size): 
                                    ?>
                                        <input type="radio" class="btn-check" name="size" 
                                               id="size_<?= htmlspecialchars(trim($size)) ?>" 
                                               value="<?= htmlspecialchars(trim($size)) ?>" required>
                                        <label class="btn btn-outline-dark" style="margin-left: 20px" 
                                               for="size_<?= htmlspecialchars(trim($size)) ?>">
                                            <?= htmlspecialchars(trim($size)) ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
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
                    <?php else: ?>
                    <div class="alert alert-warning mt-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php if (!$product['tinh_trang']): ?>
                            <strong>Sản phẩm tạm ngừng kinh doanh!</strong>
                            <p class="mb-0 mt-2">Vui lòng quay lại sau hoặc liên hệ với chúng tôi để biết thêm thông tin !</p>
                        <?php else: ?>
                            <strong>Sản phẩm đã hết hàng!</strong>
                            <p class="mb-0 mt-2">Vui lòng quay lại sau, chúng tôi sẽ thông báo cho bạn khi có hàng trở lại !</p>
                        <?php endif; ?>
                    </div>
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
                    <a class="nav-link detail-tab-link active" data-bs-toggle="tab" href="#description" style="border: none">Mô tả chi tiết</a>
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
                                <tr>
                                    <td><strong>Màu sắc:</strong></td>
                                    <td><?= htmlspecialchars($product['mau_sac'] ?? 'Chưa cập nhật') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Kích thước:</strong></td>
                                    <td><?= htmlspecialchars($product['kich_thuoc'] ?? 'Chưa cập nhật') ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><strong>Chất liệu:</strong></td>
                                    <td><?= htmlspecialchars($product['chat_lieu'] ?? 'Chưa cập nhật') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Xuất xứ:</strong></td>
                                    <td><?= htmlspecialchars($product['xuat_xu'] ?? 'Chưa cập nhật') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Bảo hành:</strong></td>
                                    <td><?= htmlspecialchars($product['bao_hanh'] ?? 'Chưa cập nhật') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="reviews" class="tab-pane fade">
                        <div class="review-form mb-4">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form id="reviewForm">
                                    <input type="hidden" name="san_pham_id" value="<?= $product['id'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Đánh giá của bạn</label>
                                        <div class="rating-input">
                                            <?php for($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="diem_danh_gia" value="<?= $i ?>" id="star<?= $i ?>" required>
                                                <label for="star<?= $i ?>"><i class="bi bi-star-fill"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nhận xét của bạn</label>
                                        <textarea class="form-control" name="noi_dung" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    Vui lòng <a href="/WebbandoTT/dang-nhap">đăng nhập</a> để viết đánh giá
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="reviews-list mt-4">
                            <?php if (!empty($reviews)): ?>
                                <?php foreach ($reviews as $review): ?>
                                    <div class="review-item mb-4 p-3 border rounded bg-light">
                                        <div class="review-header d-flex justify-content-between align-items-center mb-2">
                                            <div class="reviewer-info">
                                                <span class="reviewer-name fw-bold"><?= htmlspecialchars($review['ten_nguoi_dung']) ?></span>
                                                <small class="text-muted ms-2"><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></small>
                                            </div>
                                            <div class="star-rating">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="bi bi-star<?= $i <= $review['diem_danh_gia'] ? '-fill text-warning' : '' ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <?= nl2br(htmlspecialchars($review['noi_dung'])) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center p-4">
                                    <div class="mb-3"><i class="bi bi-chat-square-text" style="font-size: 2rem;"></i></div>
                                    <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này</p>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <p>Hãy là người đầu tiên đánh giá sản phẩm!</p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
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
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= floor($averageRating)): ?>
                                                <i class="bi bi-star-fill text-warning" style="margin-left: 2px"></i>
                                            <?php elseif ($i - $averageRating <= 0.5): ?>
                                                <i class="bi bi-star-half text-warning" style="margin-left: 2px"></i>
                                            <?php else: ?>
                                                <i class="bi bi-star" style="margin-left: 2px"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    <span class="rating-count">
                                        (<?= number_format($averageRating, 1) ?> - <?= $totalReviews ?> đánh giá)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/WebbandoTT/app/public/js/main.js"></script>
<script>
    document.getElementById('addToCartForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // Ngăn chặn hành vi mặc định của form

        const formData = new FormData(this); // Lấy dữ liệu từ form

        try {
            const response = await fetch('/WebbandoTT/app/api/carts/add_to_cart.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                // Cập nhật số lượng sản phẩm trong giỏ hàng
                alert(result.message); // Hiển thị thông báo thành công
                updateCartCount(result.cart_count); // Cập nhật số lượng giỏ hàng
            } else {
                alert(result.message); // Hiển thị thông báo lỗi
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
        }
    });

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

    document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        
        const formData = new FormData(this);

        fetch('/WebbandoTT/api/reviews/add', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra khi gửi đánh giá');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: error.message || 'Không thể gửi đánh giá'
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
    });
</script>

<?php include 'includes/footer.php'; ?>