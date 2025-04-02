<?php
$status = $_GET['resultCode'] ?? null;
$orderInfo = $_GET['orderInfo'] ?? '';
$amount = $_GET['amount'] ?? 0;
$transId = $_GET['transId'] ?? '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả thanh toán - Sport Elite</title>
    <link href="/WebbandoTT/app/public/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if ($status == '0'): ?>
                            <div class="text-success mb-4">
                                <i class="bi bi-check-circle" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="card-title text-success">Thanh toán thành công!</h3>
                            <p>Số tiền: <?= number_format($amount) ?>₫</p>
                            <p>Mã giao dịch: <?= htmlspecialchars($transId) ?></p>
                        <?php else: ?>
                            <div class="text-danger mb-4">
                                <i class="bi bi-x-circle" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="card-title text-danger">Thanh toán thất bại!</h3>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <a href="/WebbandoTT/don-hang" class="btn btn-primary">Xem đơn hàng</a>
                            <a href="/WebbandoTT" class="btn btn-secondary">Về trang chủ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
