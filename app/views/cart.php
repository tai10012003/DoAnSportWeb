<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $cart_empty = true;
} else {
    $cart_empty = false;
}

$total = 0;
?>
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

    <div class="container mb-4" style="margin-top: 10px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="margin-bottom: 0">
                <li class="breadcrumb-item"><a href="/WebbandoTT">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/WebbandoTT/app/views/cart.php">Giỏ hàng</a></li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="card-title mt-3 mb-3">GIỎ HÀNG CỦA BẠN</h4>
                        <?php if (!empty($_SESSION['cart'])): ?>
                            <button class="btn btn-danger" onclick="clearCart()">
                                <i class="bi bi-trash"></i> Xóa tất cả
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (empty($_SESSION['cart'])): ?>
                            <div class="text-center py-5">
                                <img src="/WebbandoTT/public/images/empty-cart.png" alt="Empty Cart" class="img-fluid mb-4" style="max-width: 200px;">
                                <h5 class="text-muted mb-4">Giỏ hàng của bạn đang trống</h5>
                                <a href="/WebbandoTT/san-pham" class="btn btn-primary">Tiếp tục mua sắm</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 120px">Hình ảnh</th>
                                            <th>Sản phẩm</th>
                                            <th class="text-center" style="width: 150px">Đơn giá</th>
                                            <th class="text-center" style="width: 150px">Số lượng</th>
                                            <th class="text-center" style="width: 150px">Thành tiền</th>
                                            <th class="text-center" style="width: 80px">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                                            <tr class="cart-item" data-product-id="<?php echo $product_id; ?>">
                                                <td class="text-center">
                                                    <img src="<?php echo $item['image'] 
                                                        ? "/WebbandoTT/public/uploads/products/" . $item['image']
                                                        : "/WebbandoTT/app/public/images/no-image.jpg"; ?>" 
                                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                         class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                                </td>
                                                <td>
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold price-text"><?php echo number_format($item['price']); ?>₫</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="quantity-control d-flex align-items-center justify-content-center mx-auto" style="width: 120px;">
                                                        <button class="btn btn-outline btn-sm btn-decrease" 
                                                                data-product-id="<?php echo $product_id; ?>"
                                                                onclick="updateQuantity(<?php echo $product_id; ?>, 'decrease')">-</button>
                                                        <input type="number" class="form-control form-control-sm mx-2 text-center quantity-input"
                                                            value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['so_luong'] ?? 10; ?>"         
                                                            data-product-id="<?php echo $product_id; ?>" 
                                                            readonly
                                                            style="width: 50px; background-color: white;">
                                                        <button class="btn btn-outline btn-sm btn-increase" 
                                                                data-product-id="<?php echo $product_id; ?>"
                                                                onclick="updateQuantity(<?php echo $product_id; ?>, 'increase')">+</button>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold price-text item-total-price" id="total-<?php echo $product_id; ?>">
                                                        <?php echo number_format($item['price'] * $item['quantity']); ?>₫
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-link text-danger remove-item" data-product-id="<?php echo $product_id; ?>">
                                                    <i class="bi bi-trash"></i>    
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php
                            $subtotal = 0;
                            foreach ($_SESSION['cart'] as $item) {
                                $subtotal += $item['price'] * $item['quantity'];
                            }
                            ?>
                            
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-4 border-top">
                                <a href="/WebbandoTT/san-pham" class="btn btn-outline-primary mb-3 mb-md-0">
                                    <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                                </a>
                                <div class="d-flex align-items-center">
                                    <div class="me-4">
                                        <span class="h5 mb-0 me-2">Tổng cộng:</span>
                                        <span class="h5 mb-0 text-danger" id="total-price"><?php echo number_format($subtotal); ?>₫</span>
                                    </div>  
                                    <button class="btn btn-primary btn-lg btn-checkout">
                                        Tiến hành thanh toán<i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/WebbandoTT/app/public/js/main.js"></script>
    <script>
    function updateQuantity(productId, action) {
        let input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
        let currentValue = parseInt(input.value);
        let newValue = currentValue;

        if (action === 'increase') {
            newValue = Math.min(currentValue + 1, 10);
        } else if (action === 'decrease') {
            newValue = Math.max(currentValue - 1, 1);
        } else if (action === 'input') {
            newValue = Math.max(1, Math.min(parseInt(input.value) || 1, 10));
        }

        if (newValue !== currentValue) {
            fetch('/WebbandoTT/app/api/carts/update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: newValue
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = newValue;
                    document.getElementById(`total-${productId}`).innerHTML = data.itemTotal;
                    document.getElementById('total-price').innerHTML = data.total;
                    location.reload();
                } else {
                    // Hiển thị thông báo lỗi từ server
                    throw new Error(data.message || 'Có lỗi xảy ra khi cập nhật số lượng');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Khôi phục giá trị cũ
                input.value = currentValue;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: error.message || 'Không thể cập nhật số lượng sản phẩm'
                });
            });
        }
    }

    function clearCart() {
        Swal.fire({
            title: 'Xác nhận xóa tất cả?',
            text: "Bạn có chắc muốn xóa tất cả sản phẩm khỏi giỏ hàng?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa tất cả',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/WebbandoTT/app/api/carts/clear_cart.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        });
    }

    function updateCartTotal(total, count) {
        document.getElementById('total-price').innerHTML = total;
        document.getElementById('cart-count').innerHTML = count;
    }
    </script>
</body>
</html>