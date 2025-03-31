<?php
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/google_config.php';

session_start();

try {
    $client = getGoogleClient();
    
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);
        
        $oauth2 = new Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();
        
        $email = $userInfo->email;
        $name = $userInfo->name;
        
        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $username = explode('@', $email)[0];
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $username = $username . rand(100, 999);
            }
            
            $stmt = $conn->prepare("INSERT INTO users (username, email, ho_ten, password, role, trang_thai) VALUES (?, ?, ?, ?, 'user', 1)");
            $random_password = bin2hex(random_bytes(8));
            $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
            $stmt->execute([$username, $email, $name, $hashed_password]);
            
            $user_id = $conn->lastInsertId();
            $role = 'user';
        } else {
            if ($user['trang_thai'] != 1) {
                $_SESSION['login_error'] = "Tài khoản của bạn đã bị vô hiệu hóa";
                header('Location: /WebbandoTT/dang-nhap');
                exit;
            }
            $user_id = $user['id'];
            $role = $user['role'];
            $username = $user['username'];
        }
        
        // Set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['ho_ten'] = $name;
        $_SESSION['role'] = $role;
        
        // Set success message for SweetAlert
        $_SESSION['login_success'] = true;
        
        // Redirect based on role
        if ($role === 'admin') {
            header('Location: /WebbandoTT/admin/dashboard');
        } else {
            header('Location: /WebbandoTT/');
        }
        exit;
    }
} catch (Exception $e) {
    $_SESSION['login_error'] = "Đăng nhập bằng Google thất bại: " . $e->getMessage();
    header('Location: /WebbandoTT/dang-nhap');
    exit;
}
