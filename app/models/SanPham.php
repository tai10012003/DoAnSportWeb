<?php
class SanPham {
    private $conn;
    private $table_name = "san_pham";

    // Thuộc tính đối tượng
    public $id;
    public $ma_sp;
    public $ten_sp;
    public $mo_ta;
    public $mo_ta_chi_tiet;
    public $gia;
    public $gia_khuyen_mai;
    public $so_luong;
    public $hinh_anh;
    public $danh_muc_id;
    public $thuong_hieu_id;
    public $tinh_trang; // 1: Còn hàng, 0: Hết hàng
    public $noi_bat;    // 1: Nổi bật, 0: Không nổi bật
    public $luot_xem;
    public $created_at;
    public $updated_at;
    public $kich_thuoc;
    public $mau_sac;
    public $chat_lieu;
    public $xuat_xu; 
    public $bao_hanh;

    public function __construct($db) {
        if (!$db) {
            throw new Exception("Database connection is required");
        }
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (ma_sp, ten_sp, mo_ta, mo_ta_chi_tiet, kich_thuoc, mau_sac, 
                chat_lieu, xuat_xu, bao_hanh, gia, gia_khuyen_mai, 
                so_luong, hinh_anh, danh_muc_id, thuong_hieu_id, tinh_trang, noi_bat)
                VALUES
                (:ma_sp, :ten_sp, :mo_ta, :mo_ta_chi_tiet, :kich_thuoc, :mau_sac,
                :chat_lieu, :xuat_xu, :bao_hanh, :gia, :gia_khuyen_mai,
                :so_luong, :hinh_anh, :danh_muc_id, :thuong_hieu_id, :tinh_trang, :noi_bat)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->ma_sp = htmlspecialchars(strip_tags($this->ma_sp));
        $this->ten_sp = htmlspecialchars(strip_tags($this->ten_sp));
        $this->mo_ta = htmlspecialchars(strip_tags($this->mo_ta));
        $this->mo_ta_chi_tiet = htmlspecialchars(strip_tags($this->mo_ta_chi_tiet));
        $this->gia = htmlspecialchars(strip_tags($this->gia));
        $this->gia_khuyen_mai = htmlspecialchars(strip_tags($this->gia_khuyen_mai));
        $this->so_luong = htmlspecialchars(strip_tags($this->so_luong));
        $this->hinh_anh = htmlspecialchars(strip_tags($this->hinh_anh));
        $this->danh_muc_id = htmlspecialchars(strip_tags($this->danh_muc_id));
        $this->thuong_hieu_id = htmlspecialchars(strip_tags($this->thuong_hieu_id));
        $this->tinh_trang = htmlspecialchars(strip_tags($this->tinh_trang));
        $this->noi_bat = htmlspecialchars(strip_tags($this->noi_bat));
        $this->kich_thuoc = htmlspecialchars(strip_tags($this->kich_thuoc));
        $this->mau_sac = htmlspecialchars(strip_tags($this->mau_sac));
        $this->chat_lieu = htmlspecialchars(strip_tags($this->chat_lieu));
        $this->xuat_xu = htmlspecialchars(strip_tags($this->xuat_xu));
        $this->bao_hanh = htmlspecialchars(strip_tags($this->bao_hanh));
        
        // Bind values
        $stmt->bindParam(":ma_sp", $this->ma_sp);
        $stmt->bindParam(":ten_sp", $this->ten_sp);
        $stmt->bindParam(":mo_ta", $this->mo_ta);
        $stmt->bindParam(":mo_ta_chi_tiet", $this->mo_ta_chi_tiet);
        $stmt->bindParam(":gia", $this->gia);
        $stmt->bindParam(":gia_khuyen_mai", $this->gia_khuyen_mai);
        $stmt->bindParam(":so_luong", $this->so_luong);
        $stmt->bindParam(":hinh_anh", $this->hinh_anh);
        $stmt->bindParam(":danh_muc_id", $this->danh_muc_id);
        $stmt->bindParam(":thuong_hieu_id", $this->thuong_hieu_id);
        $stmt->bindParam(":tinh_trang", $this->tinh_trang);
        $stmt->bindParam(":noi_bat", $this->noi_bat);
        $stmt->bindParam(":kich_thuoc", $this->kich_thuoc);
        $stmt->bindParam(":mau_sac", $this->mau_sac);
        $stmt->bindParam(":chat_lieu", $this->chat_lieu);
        $stmt->bindParam(":xuat_xu", $this->xuat_xu);
        $stmt->bindParam(":bao_hanh", $this->bao_hanh);
        
        return $stmt->execute();
    }

    public function read($id = null) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                FROM " . $this->table_name . " sp
                LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                LEFT JOIN thuong_hieu th ON sp.thuong_hieu_id = th.id";
        
        if ($id) {
            $query .= " WHERE sp.id = :id";
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
                ma_sp = :ma_sp,
                ten_sp = :ten_sp,
                mo_ta = :mo_ta,
                mo_ta_chi_tiet = :mo_ta_chi_tiet,
                gia = :gia,
                gia_khuyen_mai = :gia_khuyen_mai,
                so_luong = :so_luong,
                hinh_anh = :hinh_anh,
                danh_muc_id = :danh_muc_id,
                thuong_hieu_id = :thuong_hieu_id,
                tinh_trang = :tinh_trang,
                noi_bat = :noi_bat,
                kich_thuoc = :kich_thuoc,
                mau_sac = :mau_sac,
                chat_lieu = :chat_lieu,
                xuat_xu = :xuat_xu,
                bao_hanh = :bao_hanh,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->ma_sp = htmlspecialchars(strip_tags($this->ma_sp));
        $this->ten_sp = htmlspecialchars(strip_tags($this->ten_sp));
        $this->mo_ta = htmlspecialchars(strip_tags($this->mo_ta));
        $this->mo_ta_chi_tiet = htmlspecialchars(strip_tags($this->mo_ta_chi_tiet));
        $this->gia = htmlspecialchars(strip_tags($this->gia));
        $this->gia_khuyen_mai = htmlspecialchars(strip_tags($this->gia_khuyen_mai));
        $this->so_luong = htmlspecialchars(strip_tags($this->so_luong));
        $this->hinh_anh = htmlspecialchars(strip_tags($this->hinh_anh));
        $this->danh_muc_id = htmlspecialchars(strip_tags($this->danh_muc_id));
        $this->thuong_hieu_id = htmlspecialchars(strip_tags($this->thuong_hieu_id));
        $this->tinh_trang = htmlspecialchars(strip_tags($this->tinh_trang));
        $this->noi_bat = htmlspecialchars(strip_tags($this->noi_bat));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->kich_thuoc = htmlspecialchars(strip_tags($this->kich_thuoc));
        $this->mau_sac = htmlspecialchars(strip_tags($this->mau_sac));
        $this->chat_lieu = htmlspecialchars(strip_tags($this->chat_lieu));
        $this->xuat_xu = htmlspecialchars(strip_tags($this->xuat_xu));
        $this->bao_hanh = htmlspecialchars(strip_tags($this->bao_hanh));
        
        // Bind values
        $stmt->bindParam(":ma_sp", $this->ma_sp);
        $stmt->bindParam(":ten_sp", $this->ten_sp);
        $stmt->bindParam(":mo_ta", $this->mo_ta);
        $stmt->bindParam(":mo_ta_chi_tiet", $this->mo_ta_chi_tiet);
        $stmt->bindParam(":gia", $this->gia);
        $stmt->bindParam(":gia_khuyen_mai", $this->gia_khuyen_mai);
        $stmt->bindParam(":so_luong", $this->so_luong);
        $stmt->bindParam(":hinh_anh", $this->hinh_anh);
        $stmt->bindParam(":danh_muc_id", $this->danh_muc_id);
        $stmt->bindParam(":thuong_hieu_id", $this->thuong_hieu_id);
        $stmt->bindParam(":tinh_trang", $this->tinh_trang);
        $stmt->bindParam(":noi_bat", $this->noi_bat);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":kich_thuoc", $this->kich_thuoc);
        $stmt->bindParam(":mau_sac", $this->mau_sac);
        $stmt->bindParam(":chat_lieu", $this->chat_lieu);
        $stmt->bindParam(":xuat_xu", $this->xuat_xu);
        $stmt->bindParam(":bao_hanh", $this->bao_hanh);
        
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

    public function getByCategory($danh_muc_id, $limit = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE danh_muc_id = :danh_muc_id";
        
        if ($limit) {
            $query .= " LIMIT " . $limit;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":danh_muc_id", $danh_muc_id);
        $stmt->execute();
        
        return $stmt;
    }

    public function getAllProducts($page = 1, $limit = 10) {
        try {
            $start = ($page - 1) * $limit;
            $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                     FROM " . $this->table_name . " sp
                     LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                     LEFT JOIN thuong_hieu th ON sp.thuong_hieu_id = th.id
                     ORDER BY sp.created_at DESC
                     LIMIT :start, :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':start', $start, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getAllProducts: " . $e->getMessage());
            return [];
        }
    }

    public function getFeaturedProducts($limit = 8) {
        try {
            $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                    FROM " . $this->table_name . " sp
                    LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                    LEFT JOIN thuong_hieu th ON sp.thuong_hieu_id = th.id
                    WHERE sp.noi_bat = 1 
                    AND sp.tinh_trang = 1
                    ORDER BY sp.id DESC 
                    LIMIT :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getFeaturedProducts: " . $e->getMessage());
            return [];
        }
    }

    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE ten_sp LIKE :keyword 
                OR mo_ta LIKE :keyword 
                OR ma_sp LIKE :keyword";
        
        $stmt = $this->conn->prepare($query);
        
        $keyword = "%{$keyword}%";
        $stmt->bindParam(":keyword", $keyword);
        
        $stmt->execute();
        return $stmt;
    }

    public function updateViews($id) {
        $query = "UPDATE " . $this->table_name . " 
                SET luot_xem = luot_xem + 1 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function getRelatedProducts($danh_muc_id, $current_id, $limit = 4) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE danh_muc_id = :danh_muc_id 
                AND id != :current_id 
                LIMIT " . $limit;
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":danh_muc_id", $danh_muc_id);
        $stmt->bindParam(":current_id", $current_id);
        $stmt->execute();
        
        return $stmt;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalProducts() {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table_name;
            $stmt = $this->conn->query($query);
            return $stmt->fetchColumn();
        } catch(PDOException $e) {
            error_log("Error in getTotalProducts: " . $e->getMessage());
            return 0;
        }
    }

    public function getAllCategories() {
        try {
            $query = "SELECT id, ten_danh_muc FROM danh_muc WHERE trang_thai = 1";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getAllCategories: " . $e->getMessage());
            return [];
        }
    }

    public function getAllBrands() {
        try {
            $query = "SELECT id, ten_thuong_hieu FROM thuong_hieu WHERE trang_thai = 1";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getAllBrands: " . $e->getMessage());
            return [];
        }
    }

    public function getProduct($id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getProduct: " . $e->getMessage());
            return null;
        }
    }

    public function searchProducts($search = '', $categoryId = '', $brandId = '') {
        try {
            $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                     FROM " . $this->table_name . " sp
                     LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                     LEFT JOIN thuong_hieu th ON sp.thuong_hieu_id = th.id
                     WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $query .= " AND (sp.ten_sp LIKE :search OR sp.ma_sp LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if (!empty($categoryId)) {
                $query .= " AND sp.danh_muc_id = :category_id";
                $params[':category_id'] = $categoryId;
            }

            if (!empty($brandId)) {
                $query .= " AND sp.thuong_hieu_id = :brand_id";
                $params[':brand_id'] = $brandId;
            }

            $query .= " ORDER BY sp.created_at DESC";

            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in searchProducts: " . $e->getMessage());
            return [];
        }
    }

    public function filterProducts($search = '', $categoryId = null, $priceRange = null, $brandId = null, $page = 1, $perPage = 12, $sort = '') {
        try {
            $offset = ($page - 1) * $perPage;
            $params = [];
            
            $sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                   FROM " . $this->table_name . " sp
                   LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                   LEFT JOIN thuong_hieu th ON sp.thuong_hieu_id = th.id
                   WHERE 1=1";

            if (!empty($search)) {
                $sql .= " AND (sp.ten_sp LIKE :search OR sp.ma_sp LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if (!empty($categoryId)) {
                $sql .= " AND sp.danh_muc_id = :category_id";
                $params[':category_id'] = $categoryId;
            }

            if (!empty($priceRange)) {
                list($min, $max) = explode('-', $priceRange);
                if ($max === 'up') {
                    $sql .= " AND sp.gia >= :min_price";
                    $params[':min_price'] = $min;
                } else {
                    $sql .= " AND sp.gia BETWEEN :min_price AND :max_price";
                    $params[':min_price'] = $min;
                    $params[':max_price'] = $max;
                }
            }

            if (!empty($brandId)) {
                $sql .= " AND sp.thuong_hieu_id = :brand_id";
                $params[':brand_id'] = $brandId;
            }

            // Add sorting
            switch ($sort) {
                case 'promotion':
                    $sql .= " AND sp.gia_khuyen_mai < sp.gia ORDER BY (sp.gia - sp.gia_khuyen_mai) DESC";
                    break;
                case 'price_asc':
                    $sql .= " ORDER BY sp.gia ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY sp.gia DESC";
                    break;
                default:
                    $sql .= " ORDER BY sp.id DESC";
            }

            $sql .= " LIMIT :offset, :per_page";

            $stmt = $this->conn->prepare($sql);
            
            // Bind tham số cho LIMIT và OFFSET
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(':per_page', (int)$perPage, PDO::PARAM_INT);

            // Bind các tham số khác
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in filterProducts: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalFilteredProducts($search = '', $categoryId = null, $minPrice = null, $maxPrice = null, $brandId = null) {
        try {
            $params = [];
            $sql = "SELECT COUNT(*) FROM " . $this->table_name . " sp WHERE 1=1";

            if (!empty($search)) {
                $sql .= " AND sp.ten_sp LIKE :search";
                $params[':search'] = "%$search%";
            }

            if ($categoryId) {
                $sql .= " AND sp.danh_muc_id = :category_id";
                $params[':category_id'] = $categoryId;
            }

            if ($minPrice !== null) {
                $sql .= " AND sp.gia >= :min_price";
                $params[':min_price'] = $minPrice;
            }

            if ($maxPrice !== null) {
                $sql .= " AND sp.gia <= :max_price";
                $params[':max_price'] = $maxPrice;
            }

            if ($brandId) {
                $sql .= " AND sp.thuong_hieu_id = :brand_id";
                $params[':brand_id'] = $brandId;
            }

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => &$value) {
                $stmt->bindParam($key, $value);
            }
            
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch(PDOException $e) {
            error_log("Error in getTotalFilteredProducts: " . $e->getMessage());
            return 0;
        }
    }
}
?>
