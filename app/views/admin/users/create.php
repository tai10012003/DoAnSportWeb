<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
checkAdminAuth();

$userController = new UserController();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm người dùng - Sport Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>

        <div class="dashboard-content">
            <div class="content-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Thêm người dùng mới</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/users">Người dùng</a></li>
                                <li class="breadcrumb-item active">Thêm mới</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="createUserForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label required">Tên đăng nhập</label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label required">Mật khẩu</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label required">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label required">Họ tên</label>
                                    <input type="text" class="form-control" name="ho_ten" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" name="so_dien_thoai">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" name="dia_chi" rows="3"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Vai trò</label>
                                    <select class="form-select" name="role">
                                        <option value="user">Người dùng</option>
                                        <option value="admin">Quản trị viên</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select class="form-select" name="trang_thai">
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Tạm khóa</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class='bx bx-arrow-back'></i> Quay lại
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Lưu người dùng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('createUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const response = await fetch('/WebbandoTT/app/api/users/create.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Thêm người dùng thành công!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '/WebbandoTT/admin/users';
                    });
                } else {
                    throw new Error(result.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: error.message || 'Không thể thêm người dùng'
                });
            }
        });
    </script>
</body>
</html>
