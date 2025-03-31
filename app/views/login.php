<?php
require_once __DIR__ . '/../config/google_config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Google\Client;

$client = getGoogleClient();
$google_login_url = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Sport Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="/WebbandoTT/app/public/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-tabs">
                <button class="auth-tab active" data-tab="login">
                    <i class="bi bi-person-check-fill"></i> Đăng nhập
                </button>
                <button class="auth-tab" data-tab="register" onclick="location.href='/WebbandoTT/dang-ky'">
                    <i class="bi bi-person-plus-fill"></i> Đăng ký
                </button>
            </div>

            <div class="auth-form-container">
                <form id="loginForm" class="auth-form" method="post" action="/WebbandoTT/user/login" onsubmit="return validateLoginForm()">
                    <h2 class="auth-title">Đăng nhập Sport Elite</h2>
                    <p class="auth-subtitle">Chào mừng bạn trở lại! Vui lòng đăng nhập để tiếp tục.</p>

                    <?php 
                    if(isset($_SESSION['login_error'])): 
                    ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php 
                        echo htmlspecialchars($_SESSION['login_error']); 
                        unset($_SESSION['login_error']); 
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <i class="bi bi-envelope"></i>
                            <input type="email" id="email" name="email" placeholder="Nhập email của bạn" required>
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

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Ghi nhớ đăng nhập</span>
                        </label>
                        <a href="forgot-password.php" class="forgot-password">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="auth-button">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Đăng nhập
                    </button>

                    <div class="social-divider">
                        <span>Hoặc đăng nhập với</span>
                    </div>

                    <div class="social-buttons">
                        <button type="button" class="social-button google" onclick="loginWithGoogle()">
                            <img src="/WebbandoTT/app/public/images/auth/google.png" width="24" height="24" alt="Google">
                            Google
                        </button>
                        <button type="button" class="social-button facebook" onclick="loginWithFacebook()">
                            <img src="/WebbandoTT/app/public/images/auth/facebook.png" width="24" height="24" alt="Facebook">
                            Facebook
                        </button>
                    </div>

                    <div class="auth-footer">
                        <p>Chưa có tài khoản? <a href="WebbandoTT/dang-ky">Đăng ký ngay</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

        // Form validation
        function validateLoginForm() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            let isValid = true;

            // Reset previous errors
            document.querySelectorAll('.input-group').forEach(group => {
                group.classList.remove('error');
            });
            document.querySelectorAll('.error-message').forEach(msg => {
                msg.remove();
            });

            // Email validation
            if (!email || !/\S+@\S+\.\S+/.test(email)) {
                showError('email', 'Email không hợp lệ');
                isValid = false;
            }

            // Password validation
            if (!password || password.length < 6) {
                showError('password', 'Mật khẩu phải có ít nhất 6 ký tự');
                isValid = false;
            }

            return isValid;
        }

        function showError(inputId, message) {
            const input = document.getElementById(inputId);
            const inputGroup = input.parentElement;
            inputGroup.classList.add('error');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.innerHTML = `<i class="bi bi-exclamation-circle"></i>${message}`;
            
            inputGroup.parentElement.appendChild(errorDiv);
        }

        // Social login functions
        window.loginWithGoogle = function() {
            window.location.href = '<?= $google_login_url ?>';
        }

        window.loginWithFacebook = function() {
            // Facebook login implementation
            console.log('Facebook login clicked');
        }
    });
    </script>

</body>
</html>
