<?php
class ThuongHieu {
    private $conn;
    private $table_name = "thuong_hieu";

    public $id;
    public $ma_thuong_hieu;
    public $ten_thuong_hieu;
    public $mo_ta;
    public $logo;
    public $website;
    public $thu_tu;
    public $trang_thai; // 1: Hiện, 0: Ẩn
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (ma_thuong_hieu, ten_thuong_hieu, mo_ta, logo, website, thu_tu, trang_thai)
                VALUES
                (:ma_thuong_hieu, :ten_thuong_hieu, :mo_ta, :logo, :website, :thu_tu, :trang_thai)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->ma_thuong_hieu = htmlspecialchars(strip_tags($this->ma_thuong_hieu));
        $this->ten_thuong_hieu = htmlspecialchars(strip_tags($this->ten_thuong_hieu));
        $this->mo_ta = htmlspecialchars(strip_tags($this->mo_ta));
        $this->logo = htmlspecialchars(strip_tags($this->logo));
        $this->website = htmlspecialchars(strip_tags($this->website));
        $this->thu_tu = htmlspecialchars(strip_tags($this->thu_tu));
        $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
        
        // Bind values
        $stmt->bindParam(":ma_thuong_hieu", $this->ma_thuong_hieu);
        $stmt->bindParam(":ten_thuong_hieu", $this->ten_thuong_hieu);
        $stmt->bindParam(":mo_ta", $this->mo_ta);
        $stmt->bindParam(":logo", $this->logo);
        $stmt->bindParam(":website", $this->website);
        $stmt->bindParam(":thu_tu", $this->thu_tu);
        $stmt->bindParam(":trang_thai", $this->trang_thai);
        
        return $stmt->execute();
    }

    public function read($id = null) {
        $query = "SELECT * FROM " . $this->table_name;
        
        if ($id) {
            $query .= " WHERE id = :id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($id) {
            $stmt->bindParam(":id", $id);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET 
                ma_thuong_hieu = :ma_thuong_hieu,
                ten_thuong_hieu = :ten_thuong_hieu,
                mo_ta = :mo_ta,
                logo = :logo,
                website = :website,
                thu_tu = :thu_tu,
                trang_thai = :trang_thai,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->ma_thuong_hieu = htmlspecialchars(strip_tags($this->ma_thuong_hieu));
        $this->ten_thuong_hieu = htmlspecialchars(strip_tags($this->ten_thuong_hieu));
        $this->mo_ta = htmlspecialchars(strip_tags($this->mo_ta));
        $this->logo = htmlspecialchars(strip_tags($this->logo));
        $this->website = htmlspecialchars(strip_tags($this->website));
        $this->thu_tu = htmlspecialchars(strip_tags($this->thu_tu));
        $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind values
        $stmt->bindParam(":ma_thuong_hieu", $this->ma_thuong_hieu);
        $stmt->bindParam(":ten_thuong_hieu", $this->ten_thuong_hieu);
        $stmt->bindParam(":mo_ta", $this->mo_ta);
        $stmt->bindParam(":logo", $this->logo);
        $stmt->bindParam(":website", $this->website);
        $stmt->bindParam(":thu_tu", $this->thu_tu);
        $stmt->bindParam(":trang_thai", $this->trang_thai);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Các phương thức bổ sung
    public function getFeaturedBrands($limit = 8) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE trang_thai = 1 
                ORDER BY thu_tu ASC 
                LIMIT " . $limit;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    public function getProductCount($brand_id) {
        $query = "SELECT COUNT(*) as total FROM san_pham WHERE thuong_hieu_id = :brand_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":brand_id", $brand_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getAllBrands($page = 1, $limit = 10) {
        try {
            $start = ($page - 1) * $limit;
            $query = "SELECT th.*
                     FROM " . $this->table_name . " th
                     ORDER BY th.created_at DESC
                     LIMIT :start, :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getAllBrands: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalBrands() {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table_name;
            $stmt = $this->conn->query($query);
            return $stmt->fetchColumn();
        } catch(PDOException $e) {
            error_log("Error in getTotalBrands: " . $e->getMessage());
            return 0;
        }
    }
    public function getBrand($id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getBrand: " . $e->getMessage());
            return null;
        }
    }
}
?>
