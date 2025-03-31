<?php
class NguoiDung {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $email;
    public $ho_ten;
    public $so_dien_thoai;
    public $dia_chi;
    public $role;
    public $trang_thai;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    (username, password, email, ho_ten, so_dien_thoai, dia_chi, role, trang_thai) 
                    VALUES 
                    (:username, :password, :email, :ho_ten, :so_dien_thoai, :dia_chi, :role, :trang_thai)";
            
            $stmt = $this->conn->prepare($query);

            // Hash password
            $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

            // Bind values
            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":ho_ten", $this->ho_ten);
            $stmt->bindParam(":so_dien_thoai", $this->so_dien_thoai);
            $stmt->bindParam(":dia_chi", $this->dia_chi);
            $stmt->bindParam(":role", $this->role);
            $stmt->bindParam(":trang_thai", $this->trang_thai);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in NguoiDung::create: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers($page = 1, $limit = 10) {
        try {
            $start = ($page - 1) * $limit;
            $query = "SELECT * FROM " . $this->table_name . " 
                     ORDER BY created_at DESC
                     LIMIT :start, :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getAllUsers: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalUsers() {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table_name;
            $stmt = $this->conn->query($query);
            return $stmt->fetchColumn();
        } catch(PDOException $e) {
            error_log("Error in getTotalUsers: " . $e->getMessage());
            return 0;
        }
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET 
                    username = :username,
                    email = :email,
                    ho_ten = :ho_ten,
                    so_dien_thoai = :so_dien_thoai,
                    dia_chi = :dia_chi,
                    role = :role,
                    trang_thai = :trang_thai
                    " . (!empty($this->password) ? ", password = :password" : "") . "
                    WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            // Bind values
            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":ho_ten", $this->ho_ten);
            $stmt->bindParam(":so_dien_thoai", $this->so_dien_thoai);
            $stmt->bindParam(":dia_chi", $this->dia_chi);
            $stmt->bindParam(":role", $this->role);
            $stmt->bindParam(":trang_thai", $this->trang_thai);
            $stmt->bindParam(":id", $this->id);

            // Only bind password if it's being updated
            if (!empty($this->password)) {
                $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindParam(":password", $hashed_password);
            }

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in NguoiDung::update: " . $e->getMessage());
            return false;
        }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in NguoiDung::delete: " . $e->getMessage());
            return false;
        }
    }

    public function getUser($id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in NguoiDung::getUser: " . $e->getMessage());
            return null;
        }
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function getAllActiveUsers() {
        try {
            $query = "SELECT id, ho_ten, email FROM users WHERE trang_thai = 1 ORDER BY ho_ten ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in NguoiDung::getAllActiveUsers: " . $e->getMessage());
            return [];
        }
    }
}
?>
