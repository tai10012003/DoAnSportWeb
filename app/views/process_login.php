<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Vui lòng nhập đầy đủ thông tin");
        exit();
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['fullname'];

            if ($remember) {
                // Set remember me cookie - 30 days
                setcookie('remember_token', generateRememberToken($user['id']), time() + (86400 * 30), "/");
            }

            header("Location: index.php");
            exit();
        }
    }

    header("Location: login.php?error=Email hoặc mật khẩu không chính xác");
    exit();
}

function generateRememberToken($userId) {
    $token = bin2hex(random_bytes(32));
    // Save token to database
    return $token;
}
?>
