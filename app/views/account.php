<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /WebbandoTT/dang-nhap");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - Sport Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="account-wrapper">
                    <div class="account-content">
                        <div class="account-section">
                            <h5 class="section-titles">
                                <i class="bi bi-person-circle me-2"></i>
                                THÔNG TIN CÁ NHÂN
                            </h5>
                            <form id="updateProfileForm" class="account-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="ho_ten">Họ và tên</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                <input type="text" class="form-control" id="ho_ten" name="ho_ten" 
                                                       value="<?php echo htmlspecialchars($user['ho_ten']); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="email">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                <input type="email" class="form-control" id="email" name="email" 
                                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="so_dien_thoai">Số điện thoại</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                                <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai" 
                                                       value="<?php echo htmlspecialchars($user['so_dien_thoai']); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="dia_chi">Địa chỉ</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                <textarea class="form-control" id="dia_chi" name="dia_chi" rows="3" 
                                                          required><?php echo htmlspecialchars($user['dia_chi']); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-update">
                                            <i class="bi bi-check-circle me-2"></i>Cập nhật thông tin
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/WebbandoTT/app/api/users/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Thông tin tài khoản đã được cập nhật',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: data.message || 'Có lỗi xảy ra khi cập nhật thông tin'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi cập nhật thông tin'
                });
            });
        });
    </script>
</body>
</html>