<?php
class BaiViet {
    private $conn;
    private $table_name = "bai_viet";

    // Properties
    public $id;
    public $tieu_de;
    public $slug;
    public $mo_ta_ngan;
    public $noi_dung;
    public $hinh_anh;
    public $luot_xem;
    public $user_id;
    public $trang_thai;
    public $meta_title;
    public $meta_description;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Methods
    public function getAllPosts($page = 1, $limit = 10) {
        try {
            $start = ($page - 1) * $limit;
            $query = "SELECT bv.*, u.ho_ten as ten_tac_gia 
                     FROM " . $this->table_name . " bv
                     LEFT JOIN users u ON bv.user_id = u.id
                     ORDER BY bv.created_at DESC
                     LIMIT :start, :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getAllPosts: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalPosts() {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table_name;
            $stmt = $this->conn->query($query);
            return $stmt->fetchColumn();
        } catch(PDOException $e) {
            error_log("Error in getTotalPosts: " . $e->getMessage());
            return 0;
        }
    }

    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                    (tieu_de, slug, mo_ta_ngan, noi_dung, hinh_anh, 
                    user_id, trang_thai, meta_title, meta_description)
                    VALUES
                    (:tieu_de, :slug, :mo_ta_ngan, :noi_dung, :hinh_anh,
                    :user_id, :trang_thai, :meta_title, :meta_description)";

            $stmt = $this->conn->prepare($query);

            // Sanitize input
            $this->tieu_de = htmlspecialchars(strip_tags($this->tieu_de));
            $this->slug = htmlspecialchars(strip_tags($this->slug));
            $this->mo_ta_ngan = htmlspecialchars(strip_tags($this->mo_ta_ngan));
            $this->noi_dung = htmlspecialchars($this->noi_dung);
            $this->hinh_anh = htmlspecialchars(strip_tags($this->hinh_anh));
            $this->meta_title = htmlspecialchars(strip_tags($this->meta_title));
            $this->meta_description = htmlspecialchars(strip_tags($this->meta_description));

            // Bind values
            $stmt->bindParam(':tieu_de', $this->tieu_de);
            $stmt->bindParam(':slug', $this->slug);
            $stmt->bindParam(':mo_ta_ngan', $this->mo_ta_ngan);
            $stmt->bindParam(':noi_dung', $this->noi_dung);
            $stmt->bindParam(':hinh_anh', $this->hinh_anh);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':trang_thai', $this->trang_thai);
            $stmt->bindParam(':meta_title', $this->meta_title);
            $stmt->bindParam(':meta_description', $this->meta_description);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in create: " . $e->getMessage());
            return false;
        }
    }

    public function getPost($id) {
        try {
            $query = "SELECT bv.*, u.ho_ten as ten_tac_gia 
                     FROM " . $this->table_name . " bv
                     LEFT JOIN users u ON bv.user_id = u.id
                     WHERE bv.id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getPost: " . $e->getMessage());
            return null;
        }
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET 
                    tieu_de = :tieu_de,
                    slug = :slug,
                    mo_ta_ngan = :mo_ta_ngan,
                    noi_dung = :noi_dung,
                    hinh_anh = :hinh_anh,
                    trang_thai = :trang_thai,
                    meta_title = :meta_title,
                    meta_description = :meta_description,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            // Bind values
            $stmt->bindParam(':tieu_de', $this->tieu_de);
            $stmt->bindParam(':slug', $this->slug);
            $stmt->bindParam(':mo_ta_ngan', $this->mo_ta_ngan);
            $stmt->bindParam(':noi_dung', $this->noi_dung);
            $stmt->bindParam(':hinh_anh', $this->hinh_anh);
            $stmt->bindParam(':trang_thai', $this->trang_thai);
            $stmt->bindParam(':meta_title', $this->meta_title);
            $stmt->bindParam(':meta_description', $this->meta_description);
            $stmt->bindParam(':id', $this->id);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in update: " . $e->getMessage());
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
            error_log("Error in delete: " . $e->getMessage());
            return false;
        }
    }

    public function updateViews($id) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET luot_xem = luot_xem + 1 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in updateViews: " . $e->getMessage());
            return false;
        }
    }

    public function getBySlug($slug) {
        $query = "SELECT bv.*, u.ho_ten as ten_tac_gia
                 FROM bai_viet bv
                 LEFT JOIN users u ON bv.user_id = u.id
                 WHERE bv.slug = :slug AND bv.trang_thai = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":slug", $slug);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function incrementViews($id) {
        $query = "UPDATE bai_viet SET luot_xem = luot_xem + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getRelatedPosts($currentId, $limit = 3) {
        $query = "SELECT bv.*, u.ho_ten as ten_tac_gia 
                 FROM bai_viet bv
                 LEFT JOIN users u ON bv.user_id = u.id 
                 WHERE bv.id != :current_id 
                 AND bv.trang_thai = 1
                 ORDER BY bv.created_at DESC 
                 LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":current_id", $currentId);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}