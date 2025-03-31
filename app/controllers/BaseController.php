<?php
require_once __DIR__ . '/../config/database.php';

class BaseController {
    protected $db;
    protected $conn;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    protected function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/{$view}.php";
    }

    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>
