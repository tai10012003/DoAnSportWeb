<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/BaiViet.php';

class BlogController extends BaseController {
    private $baiVietModel;

    public function __construct() {
        parent::__construct();
        $this->baiVietModel = new BaiViet($this->db);
    }

    public function index($page = 1) {
        try {
            $limit = 10;
            $posts = $this->baiVietModel->getAllPosts($page, $limit);
            $totalPosts = $this->baiVietModel->getTotalPosts();
            $totalPages = ceil($totalPosts / $limit);

            return [
                'posts' => $posts,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalPosts' => $totalPosts
            ];
        } catch (Exception $e) {
            error_log("Error in BlogController::index: " . $e->getMessage());
            return [
                'posts' => [],
                'currentPage' => 1,
                'totalPages' => 0,
                'totalPosts' => 0
            ];
        }
    }

    public function getPostForEdit($id) {
        try {
            $post = $this->baiVietModel->getPost($id);
            return [
                'post' => $post
            ];
        } catch (Exception $e) {
            error_log("Error in BlogController::getPostForEdit: " . $e->getMessage());
            return [
                'post' => null
            ];
        }
    }

    public function createSlug($string) {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#[^a-z0-9\s-]#'
        );
        
        $replace = array(
            'a', 'e', 'i', 'o', 'u', 'y', 'd', ''
        );
        
        $string = strtolower(preg_replace($search, $replace, $string));
        $string = preg_replace('/[\s-]+/', '-', $string);
        
        return trim($string, '-');
    }

    public function show($slug) {
        $db = new Database();
        $baiViet = new BaiViet($db->getConnection());
        
        $post = $baiViet->getBySlug($slug);
        if (!$post) {
            header('Location: /WebbandoTT/404');
            exit;
        }

        $baiViet->incrementViews($post['id']);      
        $relatedPosts = $baiViet->getRelatedPosts($post['id'], 3);

        return [
            'post' => $post,
            'relatedPosts' => $relatedPosts
        ];
    }
}