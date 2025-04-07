<?php
session_start();

// Load controllers and models
require_once __DIR__ . '/app/controllers/BaseController.php';
require_once __DIR__ . '/app/controllers/ProductController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/CategoryController.php';
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/SanPham.php';
require_once __DIR__ . '/app/models/DanhMuc.php';
require_once __DIR__ . '/app/models/ThuongHieu.php';
require_once __DIR__ . '/app/controllers/HomeController.php';

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize controllers
$productController = new ProductController();
$categoryController = new CategoryController();

// Basic routing
$request = $_SERVER['REQUEST_URI'];
$basePath = '/WebbandoTT';
$route = str_replace($basePath, '', $request);

// Routes handling
switch ($route) {
    case '/':
    case '/index':
    case '/index.php':
    case '':
        $homeController = new HomeController();
        $featuredProducts = $homeController->index();
        require __DIR__ . '/app/views/home.php';
        break;

    case '/products':
    case '/products.php':
    case '/san-pham':
        if (isset($_GET['search']) || isset($_GET['category']) || 
            isset($_GET['min_price']) || isset($_GET['max_price']) || 
            isset($_GET['brand'])) {
            require __DIR__ . '/app/views/products.php';
            break;
        }
        require __DIR__ . '/app/views/products.php';
        break;
    
    case '/about':
    case '/about.php':
    case '/gioi-thieu':
        require __DIR__ . '/app/views/about.php';
        break;

    case '/danh-muc':
        require __DIR__ . '/app/views/categories.php';
        break;

    case '/thuong-hieu':
        require __DIR__ . '/app/views/brands.php';
        break;

    case '/contact':
    case '/contact.php':
    case '/lien-he':
        require __DIR__ . '/app/views/contact.php';
        break;

    case '/gio-hang':
        require __DIR__ . '/app/views/cart.php';
        break;

    case '/thanh-toan':
        require __DIR__ . '/app/views/checkout.php';
        break;
    
        case '/tai-khoan':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/account.php';
        break;

    case '/don-hang':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/orders.php';
        break;

    case '/dang-nhap':
        if (isset($_SESSION['user_id'])) {
            header('Location: /WebbandoTT/');
            exit;
        }
        require __DIR__ . '/app/views/login.php';
        break;

    case '/dang-ky':
        if (isset($_SESSION['user_id'])) {
            header('Location: /WebbandoTT/');
            exit;
        }
        require __DIR__ . '/app/views/register.php';
        break;

    case '/dang-xuat':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_destroy();
            echo json_encode(['success' => true]);
            exit;
        }
        session_destroy();
        header('Location: /WebbandoTT/dang-nhap');
        exit;

    // API Routes
    case '/api/san-pham/search':
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $db = new Database();
            $sanPham = new SanPham($db->getConnection());
            $results = $sanPham->search($_GET['keyword']);
            echo json_encode($results->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case '/api/gio-hang/them':
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            echo json_encode(['success' => true]);
        }
        break;

    case '/api/reviews/add':
        header('Content-Type: application/json; charset=utf-8');
        require_once __DIR__ . '/app/controllers/ReviewController.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $reviewController = new ReviewController();
                echo $reviewController->addReview();
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống']);
            }

            exit;
        }
        break;

    case '/user/login':
        $userController = new UserController();
        $userController->login();
        break;

    case '/user/register':
        $userController = new UserController();
        $userController->register();
        break;

    case '/user/logout':
        $userController = new UserController();
        $userController->logout();
        break;

    case '/thanh-toan/ket-qua':
        require __DIR__ . '/app/views/payment_result.php';
         break;

    case (preg_match('/^\/san-pham\/[\w-]+$/', $route) ? true : false):
        $ma_sp = basename($route);
        require __DIR__ . '/app/views/product_detail.php';
        break;

    case (preg_match('/^\/danh-muc\/[\w-]+$/', $route) ? true : false):
        require __DIR__ . '/app/views/category_products.php';
        break;

    case (preg_match('/^\/thuong-hieu\/[\w-]+$/', $route) ? true : false):
        require __DIR__ . '/app/views/brand_products.php';
        break;

    case '/admin/dashboard':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/dashboard.php';
        break;

    case '/admin/products':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        $data = $productController->index();
        require __DIR__ . '/app/views/admin/products/index.php';
        break;

    case '/admin/products/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/products/create.php';
        break;

    case '/admin/products/edit':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/products/edit.php';
        break;

    case (preg_match('/^\/admin\/products\/edit/', $route) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/products/edit.php';
        break;

    case (preg_match('/^\/san-pham/', $route) ? true : false):
        require __DIR__ . '/app/views/products.php';
        break;

    case '/san-pham':
        require __DIR__ . '/app/views/products.php';
        break;

    case '/admin/categories':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        $data = $categoryController->index();
        require __DIR__ . '/app/views/admin/categories/index.php';
        break;

    case '/admin/categories/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/categories/create.php';
        break;
        
    case '/admin/categories/edit':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/categories/edit.php';
        break;
    case (preg_match('/^\/admin\/categories\/edit/', $route) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/categories/edit.php';
        break;
    
        case '/admin/categories':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        $data = $categoryController->index();
        require __DIR__ . '/app/views/admin/categories/index.php';
        break;

    case '/admin/categories/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/categories/create.php';
        break;
        
    case '/admin/categories/edit':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/categories/edit.php';
        break;
    case (preg_match('/^\/admin\/categories\/edit/', $route) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/categories/edit.php';
        break;
    
    case '/admin/brands':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        $data = $categoryController->index();
        require __DIR__ . '/app/views/admin/brands/index.php';
        break;

    case '/admin/brands/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/brands/create.php';
        break;
        
    case '/admin/brands/edit':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/brands/edit.php';
        break;
    case (preg_match('/^\/admin\/brands\/edit/', $route) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/brands/edit.php';
        break;
    
    case '/admin/users':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        $data = $categoryController->index();
        require __DIR__ . '/app/views/admin/users/index.php';
        break;

    case '/admin/users/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/users/create.php';
        break;
        
    case '/admin/users/edit':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/users/edit.php';
        break;
    case (preg_match('/^\/admin\/users\/edit/', $route) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/users/edit.php';
        break;
    
    case '/admin/blogs':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        $data = $categoryController->index();
        require __DIR__ . '/app/views/admin/blogs/index.php';
        break;

    case '/admin/blogs/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/blogs/create.php';
        break;
        
    case '/admin/blogs/edit':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/blogs/edit.php';
        break;
    case (preg_match('/^\/admin\/blogs\/edit/', $route) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/blogs/edit.php';
        break;

    case '/bai-viet':
        require_once __DIR__ . '/app/controllers/BlogController.php';
        $blogController = new BlogController();
        $data = $blogController->index();
        require __DIR__ . '/app/views/blog.php';
        break;

    case (preg_match('/^\/bai-viet\/[\w-]+$/', $route) ? true : false):
        require_once __DIR__ . '/app/controllers/BlogController.php';
        $blogController = new BlogController();
        $slug = basename($route);
        $data = $blogController->show($slug);
        require __DIR__ . '/app/views/blog_detail.php';
        break;
    
    case '/admin/orders':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        $data = $categoryController->index();
        require __DIR__ . '/app/views/admin/orders/index.php';
        break;

    case '/admin/orders/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/orders/create.php';
        break;
        
    case '/admin/orders/edit':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/orders/edit.php';
        break;
    case (preg_match('/^\/admin\/orders\/edit/', $route) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/orders/edit.php';
        break;

    case '/admin/revenue':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /WebbandoTT/dang-nhap');
            exit;
        }
        require __DIR__ . '/app/views/admin/revenue/index.php';
        break;

    default:
        // Kiểm tra và loại bỏ đuôi .php nếu có
        $route = preg_replace('/\.php$/', '', $route);
        
        if (preg_match('/^\/san-pham\/[\w-]+$/', $route)) {
            require __DIR__ . '/app/views/product_detail.php';
        } 
        elseif (preg_match('/^\/danh-muc\/[\w-]+$/', $route)) {
            require __DIR__ . '/app/views/category_products.php';
        }
        else {
            http_response_code(404);
            require __DIR__ . '/app/views/errors/404.php';
        }
        break;
}
?>
