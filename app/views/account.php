
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: /WebbandoTT/dang-nhap");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Lấy thông tin người dùng từ session
$user_id = $_SESSION['user_id'];
$query = "SELECT ho_ten, so_dien_thoai, dia_chi FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Nếu không tìm thấy người dùng, chuyển hướng đến trang đăng nhập
    header("Location: /WebbandoTT/dang-nhap");
    exit;
}

$ho_ten = $user['ho_ten'];
$so_dien_thoai = $user['so_dien_thoai'];
$dia_chi = $user['dia_chi'];

// Xử lý cập nhật thông tin người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = htmlspecialchars($_POST['ho_ten']);
    $so_dien_thoai = htmlspecialchars($_POST['so_dien_thoai']);
    $dia_chi = htmlspecialchars($_POST['dia_chi']);

    $update_query = "UPDATE users SET ho_ten = :ho_ten, so_dien_thoai = :so_dien_thoai, dia_chi = :dia_chi WHERE id = :user_id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(":ho_ten", $ho_ten);
    $update_stmt->bindParam(":so_dien_thoai", $so_dien_thoai);
    $update_stmt->bindParam(":dia_chi", $dia_chi);
    $update_stmt->bindParam(":user_id", $user_id);

    if ($update_stmt->execute()) {
        $message = "Cập nhật thông tin thành công!";
    } else {
        $message = "Có lỗi xảy ra khi cập nhật thông tin.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - Sport Elite</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <div class="container py-5">
        <h2 class="mb-4">Thông tin tài khoản</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" class="form-control" name="ho_ten" value="<?php echo htmlspecialchars($ho_ten); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" name="so_dien_thoai" value="<?php echo htmlspecialchars($so_dien_thoai); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <textarea class="form-control" name="dia_chi" required><?php echo htmlspecialchars($dia_chi); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
        </form>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>