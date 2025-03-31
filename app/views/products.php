<?php
include 'includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

require_once __DIR__ . '/../controllers/ProductController.php';

$productController = new ProductController();
$data = $productController->filterAndGetProducts();
$products = $data['products'];
$categories = $data['categories'];
$brands = $data['brands'];
$totalProducts = $data['totalProducts'];
$currentPage = $data['currentPage'];
$totalPages = $data['totalPages'];
$filters = $data['filters'];
?>

<div class="hero-mini">
    <div class="container">
        <h1 class="display-6 text-center mb-4">SẢN PHẨM THỂ THAO</h1>
        <div class="search-box mb-5">
            <form action="/WebbandoTT/san-pham" method="GET" id="filterForm">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Tìm kiếm sản phẩm..." 
                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar lọc sản phẩm -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <!-- Danh mục -->
                <div class="filter-box mb-4">
                    <h5 class="filter-title border-bottom pb-2 mb-3">Danh Mục</h5>
                    <div class="list-group list-group-flush">
                        <a href="/WebbandoTT/san-pham" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo !($filters['category'] ?? '') ? 'active fw-bold' : ''; ?>">
                            <span>Tất cả</span>
                            <span class="badge bg-primary rounded-pill">
                                <?php echo $totalProducts; ?>
                            </span>
                        </a>
                        <?php foreach ($categories as $category): ?>
                            <a href="/WebbandoTT/san-pham?category=<?php echo $category['id']; ?>" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo ($filters['category'] ?? '') == $category['id'] ? 'active fw-bold' : ''; ?>">
                                <span><?php echo htmlspecialchars($category['ten_danh_muc']); ?></span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Thương hiệu -->
                <div class="filter-box mb-4">
                    <h5 class="filter-title border-bottom pb-2 mb-3 mt-4">Thương Hiệu</h5>
                    <div class="list-group list-group-flush">
                        <a href="/WebbandoTT/san-pham" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo !($filters['brand'] ?? '') ? 'active fw-bold' : ''; ?>">
                            <span>Tất cả</span>
                            <span class="badge bg-primary rounded-pill">
                                <?php echo $totalProducts; ?>
                            </span>
                        </a>
                        <?php foreach ($brands as $brand): ?>
                            <a href="/WebbandoTT/san-pham?brand=<?php echo $brand['id']; ?>" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo ($filters['brand'] ?? '') == $brand['id'] ? 'active fw-bold' : ''; ?>">
                                <span><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Khoảng giá -->
                <div class="filter-box mb-4">
                    <h5 class="filter-title border-bottom pb-2 mb-3 mt-4">Khoảng Giá</h5>
                    <div class="d-grid gap-2">
                        <a href="/WebbandoTT/san-pham" 
                           class="btn <?php echo empty($filters['price']) ? 'btn-success' : 'btn-outline-success'; ?> btn-sm">
                            Tất cả
                        </a>
                        <a href="/WebbandoTT/san-pham?price=0-500000" 
                           class="btn <?php echo ($filters['price'] ?? '') === '0-500000' ? 'btn-success' : 'btn-outline-success'; ?> btn-sm">
                            Dưới 500.000₫
                        </a>
                        <a href="/WebbandoTT/san-pham?price=500000-1000000" 
                           class="btn <?php echo ($filters['price'] ?? '') === '500000-1000000' ? 'btn-success' : 'btn-outline-success'; ?> btn-sm">
                            500.000₫ - 1.000.000₫
                        </a>
                        <a href="/WebbandoTT/san-pham?price=1000000-2000000" 
                           class="btn <?php echo ($filters['price'] ?? '') === '1000000-2000000' ? 'btn-success' : 'btn-outline-success'; ?> btn-sm">
                            1.000.000₫ - 2.000.000₫
                        </a>
                        <a href="/WebbandoTT/san-pham?price=2000000-5000000" 
                           class="btn <?php echo ($filters['price'] ?? '') === '2000000-5000000' ? 'btn-success' : 'btn-outline-success'; ?> btn-sm">
                            2.000.000₫ - 5.000.000₫
                        </a>
                        <a href="/WebbandoTT/san-pham?price=5000000-up" 
                           class="btn <?php echo ($filters['price'] ?? '') === '5000000-up' ? 'btn-success' : 'btn-outline-success'; ?> btn-sm">
                            Trên 5.000.000₫
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-lg-9">
            <div class="product-controls mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <span>Hiển thị <?php echo count($products); ?> trong <?php echo $totalProducts; ?> sản phẩm</span>
                    </div>
                    <div class="col-auto">
                        <a href="/WebbandoTT/san-pham" 
                            class="btn btn-outline-dark btn-sm" style="font-size: 16px;">
                            Tất cả sản phẩm
                        </a>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" id="sortSelect">
                            <option value="">Tất cả</option>
                            <option value="promotion" <?php echo ($filters['sort'] ?? '') === 'promotion' ? 'selected' : ''; ?>>Khuyến mãi</option>
                            <option value="price_asc" <?php echo ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : ''; ?>>Giá tăng dần</option>
                            <option value="price_desc" <?php echo ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : ''; ?>>Giá giảm dần</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <?php
                if (!empty($products)) {
                    foreach ($products as $product) {
                        $masp = $product["ma_sp"] ?? '';
                        $hinh_anh = $product["hinh_anh"] ?? 'default.jpg';
                        $ten_sp = $product["ten_sp"] ?? 'Sản phẩm';
                        $gia = $product["gia"] ?? 0;
                        $gia_khuyen_mai = $product["gia_khuyen_mai"] ?? $gia;
                        $imagePath = $product['hinh_anh'] 
                            ? "/WebbandoTT/public/uploads/products/" . $product['hinh_anh']
                            : "/WebbandoTT/app/public/images/products/no-image.jpg";
                        
                        echo '<div class="col-md-4">
                                <div class="product-card">
                                    <div class="product-badge">-20%</div>
                                    <div class="product-image">
                                        <a href="/WebbandoTT/san-pham/' . htmlspecialchars($product['ma_sp']) . '">
                                            <img src="' . htmlspecialchars($imagePath) . '" 
                                                 alt="' . htmlspecialchars($ten_sp) . '"
                                                 class="img-fluid">
                                        </a>
                                        <div class="product-actions">
                                            <button class="btn btn-light btn-sm add-to-cart" data-product-id="' . ($product["id"] ?? 0) . '">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                            <button class="btn btn-light btn-sm">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                            <a href="/WebbandoTT/san-pham/' . htmlspecialchars($masp) . '" class="btn btn-light btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-info">
                                        <a href="/WebbandoTT/san-pham/' . htmlspecialchars($masp) . '" class="text-decoration-none">
                                            <h3 class="product-title">' . htmlspecialchars($ten_sp) . '</h3>
                                        </a>
                                        <div class="product-price">
                                            <span class="price-new">' . number_format($gia_khuyen_mai) . '₫</span>
                                            <span class="price-old">' . number_format($gia) . '₫</span>
                                        </div>
                                        <div class="product-rating">';
                                            $avgRating = $product['avg_rating'] ?? 0;
                                            for($i = 1; $i <= 5; $i++) {
                                                if ($i <= floor($avgRating)) {
                                                    echo '<i class="bi bi-star-fill text-warning" style="margin-left: 5px"></i>';
                                                } elseif ($i - $avgRating <= 0.5) {
                                                    echo '<i class="bi bi-star-half text-warning" style="margin-left: 5px"></i>';
                                                } else {
                                                    echo '<i class="bi bi-star" style="margin-left: 5px"></i>';
                                                }
                                            }
                                            echo'
                                            <span class="rating-count">(' . number_format($avgRating, 1) . ' - ' . ($product['total_reviews'] ?? 0) . ' đánh giá)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    }
                } else {
                    echo '<div class="col-12"><p class="text-center">Không tìm thấy sản phẩm nào</p></div>';
                }
                ?>
            </div>
            
            <?php if ($totalPages > 1): ?>
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $currentPage == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="/WebbandoTT/san-pham?page=<?php echo $i; ?><?php 
                                    echo !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : ''; 
                                    echo !empty($filters['category']) ? '&category=' . $filters['category'] : '';
                                    echo !empty($filters['brand']) ? '&brand=' . $filters['brand'] : '';
                                    echo isset($filters['maxPrice']) ? '&max_price=' . $filters['maxPrice'] : '';
                                ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/WebbandoTT/app/public/js/main.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý form tìm kiếm
        const filterForm = document.getElementById('filterForm');
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchValue = this.querySelector('input[name="search"]').value.trim();
            if (searchValue) {
                window.location.href = '/WebbandoTT/san-pham?search=' + encodeURIComponent(searchValue);
            }
        });

        // Xử lý sắp xếp
        const sortSelect = document.getElementById('sortSelect');
        sortSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            if (this.value) {
                currentUrl.searchParams.set('sort', this.value);
            } else {
                currentUrl.searchParams.delete('sort');
            }
            window.location.href = currentUrl.toString();
        });
    });
</script>
</body>
</html>