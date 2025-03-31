<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Elite - Thiết bị thể thao cao cấp</title>
    <meta name="description" content="Sport Elite - Cửa hàng thể thao cao cấp với các sản phẩm chính hãng">
    <meta name="keywords" content="thể thao, đồ thể thao, thiết bị tập luyện, Sport Elite">
    
    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="/WebbandoTT/app/public/css/style.css" rel="stylesheet"><head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader">
        <div class="loader"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/WebbandoTT/">
                <span class="gradient-text">Sport</span><strong>Elite</strong>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn-hover px-4" href="/WebbandoTT/">
                            <i class="bi bi-house-door-fill me-2"></i>Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-hover px-4" href="/WebbandoTT/gioi-thieu">
                            <i class="bi bi-info-circle-fill me-2"></i>Giới thiệu
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link btn-hover px-4 dropdown-toggle" href="/WebbandoTT/san-pham">
                            <i class="bi bi-grid-fill me-2"></i>Sản phẩm
                        </a>
                        <div class="submenu">
                            <div class="submenu-inner">
                                <div class="submenu-column">
                                    <h6 class="submenu-title">Thiết bị tập Gym</h6>
                                    <ul class="submenu-list">
                                        <li><a href="#">Máy tập thể hình</a></li>
                                        <li><a href="#">Tạ tay & Tạ đòn</a></li>
                                        <li><a href="#">Ghế tập</a></li>
                                        <li><a href="#">Phụ kiện gym</a></li>
                                    </ul>
                                </div>
                                <div class="submenu-column">
                                    <h6 class="submenu-title">Yoga & Fitness</h6>
                                    <ul class="submenu-list">
                                        <li><a href="#">Thảm tập yoga</a></li>
                                        <li><a href="#">Dụng cụ hỗ trợ</a></li>
                                        <li><a href="#">Quần áo yoga</a></li>
                                        <li><a href="#">Phụ kiện yoga</a></li>
                                    </ul>
                                </div>
                                <div class="submenu-column">
                                    <h6 class="submenu-title">Thể thao đồng đội</h6>
                                    <ul class="submenu-list">
                                        <li><a href="#">Bóng đá</a></li>
                                        <li><a href="#">Bóng rổ</a></li>
                                        <li><a href="#">Cầu lông</a></li>
                                        <li><a href="#">Tennis</a></li>
                                    </ul>
                                </div>
                                <div class="submenu-column">
                                    <h6 class="submenu-title">Phụ kiện</h6>
                                    <ul class="submenu-list">  
                                        <li><a href="#">Găng tay</a></li>
                                        <li><a href="#">Đồng hồ thể thao</a></li>
                                        <li><a href="#">Túi & Balo</a></li>
                                        <li><a href="#">Phụ kiện khác</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-hover px-4" href="/WebbandoTT/bai-viet">
                            <i class="bi bi-newspaper me-2"></i>Bài viết
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-hover px-4" href="/WebbandoTT/lien-he">
                            <i class="bi bi-envelope-fill me-2"></i>Liên hệ
                        </a>
                    </li>
                    <li class="nav-item nav-item-action ms-lg-4">
                        <a class="nav-link cart-btn" href="/WebbandoTT/app/views/cart.php">
                            <span class="d-none d-lg-none cart-text">Giỏ hàng</span>
                            <i class="bi bi-cart3"></i>
                            <span class="cart-badge">
                                <?php
                                $count = 0;
                                if (isset($_SESSION['cart'])) {
                                    foreach ($_SESSION['cart'] as $item) {
                                        $count += $item['quantity'];
                                    }
                                }
                                echo $count;
                                ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item nav-item-action ms-lg-3">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="dropdown">
                                <a class="nav-link user-menu dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-2"></i>
                                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                                    <li><a class="dropdown-item" href="/WebbandoTT/tai-khoan"><i class="bi bi-person me-2"></i>Tài khoản</a></li>
                                    <li><a class="dropdown-item" href="/WebbandoTT/don-hang"><i class="bi bi-bag me-2"></i>Đơn hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="handleAdminLogout()"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="/WebbandoTT/dang-nhap" class="nav-link login-btn">
                                <i class="bi bi-person me-2"></i>Đăng nhập
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/WebbandoTT/app/public/js/main.js"></script>
<script>
    function handleAdminLogout() {
        if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
            fetch('/WebbandoTT/dang-xuat', {
                method: 'POST',
                credentials: 'include'
            })
            .then(() => {
                window.location.href = '/WebbandoTT/dang-nhap';
            })
            .catch(error => {
                console.error('Lỗi khi đăng xuất:', error);
            });
        }
    }
</script>
</html>