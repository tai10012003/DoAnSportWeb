<?php
class ChiTietDonHang {
    private $conn;
    private $table_name = "chi_tiet_don_hang";

    // Thuộc tính của chi tiết đơn hàng
    public $id;
    public $don_hang_id;
    public $san_pham_id;
    public $so_luong;
    public $gia;
    public $created_at;

    public function __construct($db) {
        if (!$db) {
            throw new Exception("Database connection is required");
        }
        $this->conn = $db;
    }

    // Thêm chi tiết đơn hàng
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 (don_hang_id, san_pham_id, so_luong, gia) 
                 VALUES (:don_hang_id, :san_pham_id, :so_luong, :gia)";

        $stmt = $this->conn->prepare($query);

        // Bind dữ liệu
        $stmt->bindParam(":don_hang_id", $this->don_hang_id, PDO::PARAM_INT);
        $stmt->bindParam(":san_pham_id", $this->san_pham_id, PDO::PARAM_INT);
        $stmt->bindParam(":so_luong", $this->so_luong, PDO::PARAM_INT);
        $stmt->bindParam(":gia", $this->gia, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Lấy danh sách chi tiết đơn hàng theo đơn hàng ID
    public function getByOrderId($don_hang_id) {
        $query = "SELECT ctdh.*, sp.ten_sp, sp.hinh_anh 
                  FROM " . $this->table_name . " ctdh
                  LEFT JOIN san_pham sp ON ctdh.san_pham_id = sp.id
                  WHERE ctdh.don_hang_id = :don_hang_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":don_hang_id", $don_hang_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa chi tiết đơn hàng theo đơn hàng ID
    public function deleteByOrderId($don_hang_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE don_hang_id = :don_hang_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":don_hang_id", $don_hang_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>