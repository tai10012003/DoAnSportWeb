<?php
class DanhMuc {
    private $conn;
    private $table_name = "danh_muc";

    public $id;
    public $ma_danh_muc;
    public $ten_danh_muc;
    public $mo_ta;
    public $hinh_anh;
    public $danh_muc_cha_id;
    public $thu_tu;
    public $trang_thai;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    (ma_danh_muc, ten_danh_muc, mo_ta, hinh_anh, danh_muc_cha_id, thu_tu, trang_thai) 
                    VALUES 
                    (:ma_danh_muc, :ten_danh_muc, :mo_ta, :hinh_anh, :danh_muc_cha_id, :thu_tu, :trang_thai)";
            
            $stmt = $this->conn->prepare($query);

            // Sanitize input
            $this->ma_danh_muc = htmlspecialchars(strip_tags($this->ma_danh_muc));
            $this->ten_danh_muc = htmlspecialchars(strip_tags($this->ten_danh_muc));
            $this->mo_ta = htmlspecialchars(strip_tags($this->mo_ta));
            $this->hinh_anh = htmlspecialchars(strip_tags($this->hinh_anh));
            $this->danh_muc_cha_id = htmlspecialchars(strip_tags($this->danh_muc_cha_id));
            $this->thu_tu = htmlspecialchars(strip_tags($this->thu_tu));
            $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
            
            // Bind values
            $stmt->bindParam(":ma_danh_muc", $this->ma_danh_muc);
            $stmt->bindParam(":ten_danh_muc", $this->ten_danh_muc);
            $stmt->bindParam(":mo_ta", $this->mo_ta);
            $stmt->bindParam(":hinh_anh", $this->hinh_anh);
            $stmt->bindParam(":danh_muc_cha_id", $this->danh_muc_cha_id);
            $stmt->bindParam(":thu_tu", $this->thu_tu);
            $stmt->bindParam(":trang_thai", $this->trang_thai);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in DanhMuc::create: " . $e->getMessage());
            return false;
        }
    }
    public function getAllCategories($page = 1, $limit = 10) {
        try {
            $start = ($page - 1) * $limit;
            $query = "SELECT dm.*, parent.ten_danh_muc as ten_danh_muc_cha 
                     FROM " . $this->table_name . " dm
                     LEFT JOIN " . $this->table_name . " parent ON dm.danh_muc_cha_id = parent.id
                     ORDER BY dm.created_at DESC
                     LIMIT :start, :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getAllCategories: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalCategories() {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table_name;
            $stmt = $this->conn->query($query);
            return $stmt->fetchColumn();
        } catch(PDOException $e) {
            error_log("Error in getTotalCategories: " . $e->getMessage());
            return 0;
        }
    }

    public function read($id = null) {
        $query = "SELECT dm.*, dm_cha.ten_danh_muc as ten_danh_muc_cha 
                FROM " . $this->table_name . " dm
                LEFT JOIN " . $this->table_name . " dm_cha ON dm.danh_muc_cha_id = dm_cha.id";
        
        if ($id) {
            $query .= " WHERE dm.id = :id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($id) {
            $stmt->bindParam(":id", $id);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET 
                    ma_danh_muc = :ma_danh_muc,
                    ten_danh_muc = :ten_danh_muc,
                    mo_ta = :mo_ta,
                    hinh_anh = :hinh_anh,
                    danh_muc_cha_id = :danh_muc_cha_id,
                    thu_tu = :thu_tu,
                    trang_thai = :trang_thai,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Bind values
            $stmt->bindParam(":ma_danh_muc", $this->ma_danh_muc);
            $stmt->bindParam(":ten_danh_muc", $this->ten_danh_muc);
            $stmt->bindParam(":mo_ta", $this->mo_ta);
            $stmt->bindParam(":hinh_anh", $this->hinh_anh);
            $stmt->bindParam(":thu_tu", $this->thu_tu, PDO::PARAM_INT);
            $stmt->bindParam(":trang_thai", $this->trang_thai, PDO::PARAM_INT);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

            // Special handling for danh_muc_cha_id
            if ($this->danh_muc_cha_id === null || $this->danh_muc_cha_id === '') {
                $stmt->bindValue(":danh_muc_cha_id", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":danh_muc_cha_id", $this->danh_muc_cha_id, PDO::PARAM_INT);
            }
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in DanhMuc::update: " . $e->getMessage());
            throw new Exception("Không thể cập nhật danh mục");
        }
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Các phương thức bổ sung
    public function getParentCategories() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE danh_muc_cha_id IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    public function getSubCategories($parent_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE danh_muc_cha_id = :parent_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":parent_id", $parent_id);
        $stmt->execute();
        
        return $stmt;
    }

    public function getCategory($id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in DanhMuc::getById: " . $e->getMessage());
            return null;
        }
    }
}
?>