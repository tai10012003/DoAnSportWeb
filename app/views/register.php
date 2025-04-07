<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Sport Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="/WebbandoTT/app/public/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-tabs">
                <button class="auth-tab" onclick="location.href='/WebbandoTT/dang-nhap'">
                    <i class="bi bi-person-check-fill"></i> Đăng nhập
                </button>
                <button class="auth-tab active">
                    <i class="bi bi-person-plus-fill"></i> Đăng ký
                </button>
            </div>

            <div class="auth-form-container">
                <form id="registerForm" class="auth-form" method="post" action="/WebbandoTT/user/register" onsubmit="return validateRegisterForm()">
                    <h2 class="auth-title">Đăng ký tài khoản</h2>
                    <p class="auth-subtitle">Tham gia cùng Sport Elite ngay hôm nay!</p>

                    <?php if(isset($_SESSION['register_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php 
                        echo htmlspecialchars($_SESSION['register_error']);
                        unset($_SESSION['register_error']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <div class="input-group">
                            <i class="bi bi-person"></i>
                            <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" maxlength="12" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ho_ten">Họ và tên</label>
                        <div class="input-group">
                            <i class="bi bi-person-badge"></i>
                            <input type="text" id="ho_ten" name="ho_ten" placeholder="Nhập họ và tên" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <i class="bi bi-envelope"></i>
                            <input type="email" id="email" name="email" placeholder="Nhập email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="so_dien_thoai">Số điện thoại</label>
                        <div class="input-group">
                            <i class="bi bi-phone"></i>
                            <input type="tel" id="so_dien_thoai" name="so_dien_thoai" placeholder="Nhập số điện thoại">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dia_chi">Địa chỉ</label>
                        <div class="input-group">
                            <i class="bi bi-geo-alt"></i>
                            <input type="text" id="dia_chi" name="dia_chi" placeholder="Nhập địa chỉ">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <div class="input-group">
                            <i class="bi bi-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                            <i class="bi bi-eye-slash toggle-password"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Nhập lại mật khẩu</label>
                        <div class="input-group">
                            <i class="bi bi-lock"></i>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
                            <i class="bi bi-eye-slash toggle-password"></i>
                        </div>
                    </div>
                    <button type="submit" class="auth-button">
                        <i class="bi bi-person-plus"></i>
                        Đăng ký
                    </button>
                    <div class="auth-footer">
                        <p>Đã có tài khoản? <a href="/WebbandoTT/dang-nhap">Đăng nhập ngay</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePasswordBtns = document.querySelectorAll('.toggle-password');
            togglePasswordBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.classList.remove('bi-eye-slash');
                        this.classList.add('bi-eye');
                    } else {
                        input.type = 'password';
                        this.classList.remove('bi-eye');
                        this.classList.add('bi-eye-slash');
                    }
                });
            });
        });

        function validateRegisterForm() {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            let isValid = true;

            // Reset previous errors
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            // Validate username
            if (username.length < 3) {
                showError('username', 'Tên đăng nhập phải có ít nhất 3 ký tự');
                isValid = false;
            }

            // Validate email
            if (!/\S+@\S+\.\S+/.test(email)) {
                showError('email', 'Email không hợp lệ');
                isValid = false;
            }

            // Validate password
            if (password.length < 6) {
                showError('password', 'Mật khẩu phải có ít nhất 6 ký tự');
                isValid = false;
            }

            // Validate password confirmation
            if (password !== confirmPassword) {
                showError('confirm_password', 'Mật khẩu nhập lại không khớp');
                isValid = false;
            }

            return isValid;
        }

        //hien thi loi cho người dùng
        function showError(inputId, message) {
            const input = document.getElementById(inputId);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback d-block';
            errorDiv.textContent = message;
            input.parentElement.appendChild(errorDiv);
        }
    </script>
</body>
</html>
