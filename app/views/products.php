<?php
include 'includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

require_once __DIR__ . '/../controllers/ProductController.php';

$productController = new ProductController();
$data = $productController->getAllProducts();
$products = $data['products'];
?>

<div class="hero-mini">
    <div class="container">
        <h1 class="display-6 text-center mb-4">SẢN PHẨM THỂ THAO</h1>
        <div class="search-box mb-5">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                <button class="btn btn-primary"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar lọc sản phẩm -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <div class="filter-box mb-4">
                    <h5 class="filter-title">Danh Mục</h5>
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">Tất cả</a>
                        <a href="#" class="list-group-item list-group-item-action">Dụng cụ tập luyện</a>
                        <a href="#" class="list-group-item list-group-item-action">Thiết bị thể thao</a>
                        <a href="#" class="list-group-item list-group-item-action">Phụ kiện</a>
                    </div>
                </div>

                <div class="filter-box mb-4">
                    <h5 class="filter-title">Giá</h5>
                    <div class="price-range">
                        <input type="range" class="form-range" min="0" max="10000000" step="100000">
                        <div class="d-flex justify-content-between">
                            <span>0đ</span>
                            <span>10.000.000đ</span>
                        </div>
                    </div>
                </div>

                <div class="filter-box mb-4">
                    <h5 class="filter-title">Thương Hiệu</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="brand1">
                        <label class="form-check-label" for="brand1">Nike</label>
                    </div>
                    <!-- Thêm các thương hiệu khác -->
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-lg-9">
            <div class="product-controls mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <span>Hiển thị 1-12 trong 36 sản phẩm</span>
                    </div>
                    <div class="col-auto">
                        <select class="form-select">
                            <option>Mới nhất</option>
                            <option>Giá tăng dần</option>
                            <option>Giá giảm dần</option>
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
                                        <div class="product-rating">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-half"></i>
                                            <span>(4.5)</span>
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
            <!-- Phân trang -->
            <nav class="mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/WebbandoTT/app/public/js/main.js"></script>
</body>
</html>