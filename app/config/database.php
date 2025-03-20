<?php
class Database {
    private $host = "localhost";
    private $database_name = "webbandott";
    private $username = "root";
    private $password = "";
    private static $instance = null;
    private $conn = null;

    public function __construct() {
        try {
            if ($this->conn === null) {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->database_name,
                    $this->username,
                    $this->password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                $this->conn->exec("set names utf8mb4");
            }
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
            exit();
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
