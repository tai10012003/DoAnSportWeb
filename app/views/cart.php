<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Sport Elite</title>





    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/style.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h4 class="mb-0">Giỏ hàng của bạn</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($_SESSION['cart'])): ?>
                            <div class="text-center py-5">
                                <img src="/WebbandoTT/public/images/empty-cart.png" alt="Empty Cart" class="img-fluid mb-4" style="max-width: 200px;">
                                <h5 class="text-muted mb-4">Giỏ hàng của bạn đang trống</h5>
                                <a href="/WebbandoTT/san-pham" class="btn btn-primary">Tiếp tục mua sắm</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                                <div class="cart-item mb-4 border-bottom pb-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="/WebbandoTT/public/images/products/<?php echo htmlspecialchars($item['image']); ?>"
                                                class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="mb-2"><?php echo htmlspecialchars($item['name']); ?></h5>
                                            <p class="text-muted mb-0">Đơn giá: <?php echo number_format($item['price']); ?>₫</p>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="quantity-control d-flex align-items-center">
                                                <button class="btn btn-outline-secondary btn-sm" data-action="decrease">-</button>
                                                <input type="number" class="form-control form-control-sm mx-2 text-center quantity-input"
                                                    value="<?php echo $item['quantity']; ?>" min="1" max="10"
                                                    data-product-id="<?php echo $product_id; ?>" style="width: 60px">
                                                <button class="btn btn-outline-secondary btn-sm" data-action="increase">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="fw-bold text-end">
                                                <?php echo number_format($item['price'] * $item['quantity']); ?>₫
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-link text-danger remove-item" data-product-id="<?php echo $product_id; ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>



  










            <?php if (!empty($_SESSION['cart'])): ?>
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">Tổng đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $subtotal = 0;
                            foreach ($_SESSION['cart'] as $item) {
                                $subtotal += $item['price'] * $item['quantity'];
                            }
                            $shipping = $subtotal >= 500000 ? 0 : 30000;
                            $total = $subtotal + $shipping;
                            ?>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Tạm tính:</span>
                                <span class="fw-bold"><?php echo number_format($subtotal); ?>₫</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Phí vận chuyển:</span>
                                <span class="fw-bold"><?php echo number_format($shipping); ?>₫</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 pt-3 border-top">
                                <span class="h5 mb-0">Tổng cộng:</span>
                                <span class="h5 mb-0 text-primary"><?php echo number_format($total); ?>₫</span>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-lg">Tiến hành thanh toán</button>
                                <a href="/WebbandoTT/san-pham" class="btn btn-outline-primary">Tiếp tục mua sắm</a>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-truck text-primary me-2"></i>
                                    <small>Miễn phí vận chuyển cho đơn hàng từ 500.000₫</small>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-shield-check text-primary me-2"></i>
                                    <small>Bảo hành chính hãng 12 tháng</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-arrow-counterclockwise text-primary me-2"></i>
                                    <small>Đổi trả miễn phí trong 30 ngày</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>





    

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/WebbandoTT/public/js/main.js"></script>
</body>

</html>