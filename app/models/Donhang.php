<?php
class DonHang {
    private $conn;
    private $table_name = "don_hang";

    // Thuộc tính của đơn hàng
    public $id;
    public $ma_don_hang;
    public $user_id;
    public $tong_tien;
    public $phi_van_chuyen;
    public $trang_thai;
    public $ghi_chu;
    public $created_at;
    public $updated_at;
    public $dia_chi;
    public $payment_method;

    public function __construct($db) {
        if (!$db) {
            throw new Exception("Database connection is required");
        }
        $this->conn = $db;
    }

    // Tạo đơn hàng mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
             (ma_don_hang, user_id, tong_tien, phi_van_chuyen, trang_thai, ghi_chu, payment_method, dia_chi)
             VALUES (:ma_don_hang, :user_id, :tong_tien, :phi_van_chuyen, :trang_thai, :ghi_chu, :payment_method, :dia_chi)";

        $stmt = $this->conn->prepare($query);

        // Tạo mã đơn hàng duy nhất
        $this->ma_don_hang = 'DH' . time();

        // Sanitize input
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->tong_tien = htmlspecialchars(strip_tags($this->tong_tien));
        $this->phi_van_chuyen = htmlspecialchars(strip_tags($this->phi_van_chuyen));
        $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
        $this->ghi_chu = htmlspecialchars(strip_tags($this->ghi_chu));
        $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
        $this->dia_chi = htmlspecialchars(strip_tags($this->dia_chi));
        // Bind giá trị
        $stmt->bindParam(":ma_don_hang", $this->ma_don_hang);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":tong_tien", $this->tong_tien);
        $stmt->bindParam(":phi_van_chuyen", $this->phi_van_chuyen);
        $stmt->bindParam(":trang_thai", $this->trang_thai);
        $stmt->bindParam(":ghi_chu", $this->ghi_chu);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":dia_chi", $this->dia_chi);
        return $stmt->execute();
    }

    // Lấy danh sách đơn hàng (có thể lọc theo user_id hoặc lấy tất cả)
    public function read($user_id = null) {
        $query = "SELECT dh.*, u.username, u.email 
                 FROM " . $this->table_name . " dh
                 LEFT JOIN users u ON dh.user_id = u.id";

        if ($user_id) {
            $query .= " WHERE dh.user_id = :user_id";
        }

        $stmt = $this->conn->prepare($query);

        if ($user_id) {
            $stmt->bindParam(":user_id", $user_id);
        }

        $stmt->execute();
        return $stmt;
    }

    // Lấy thông tin đơn hàng theo ID
    public function getOrderById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " 
                 SET trang_thai = :trang_thai, updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":trang_thai", $status);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET 
                        ma_don_hang = :ma_don_hang,
                        user_id = :user_id,
                        tong_tien = :tong_tien,
                        phi_van_chuyen = :phi_van_chuyen,
                        trang_thai = :trang_thai,
                        ghi_chu = :ghi_chu,
                        payment_method = :payment_method,
                        dia_chi = :dia_chi,
                        updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";
    
            $stmt = $this->conn->prepare($query);
    
            // Sanitize input
            $this->ma_don_hang = htmlspecialchars(strip_tags($this->ma_don_hang));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->tong_tien = htmlspecialchars(strip_tags($this->tong_tien));
            $this->phi_van_chuyen = htmlspecialchars(strip_tags($this->phi_van_chuyen));
            $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
            $this->ghi_chu = htmlspecialchars(strip_tags($this->ghi_chu));
            $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
            $this->dia_chi = htmlspecialchars(strip_tags($this->dia_chi));
            $this->id = htmlspecialchars(strip_tags($this->id));
    
            // Bind parameters
            $stmt->bindParam(":ma_don_hang", $this->ma_don_hang);
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":tong_tien", $this->tong_tien);
            $stmt->bindParam(":phi_van_chuyen", $this->phi_van_chuyen);
            $stmt->bindParam(":trang_thai", $this->trang_thai);
            $stmt->bindParam(":ghi_chu", $this->ghi_chu);
            $stmt->bindParam(":payment_method", $this->payment_method);
            $stmt->bindParam(":dia_chi", $this->dia_chi);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in DonHang::update: " . $e->getMessage());
            return false;
        }
    }

    // Xóa đơn hàng
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
// Lấy toàn bộ đơn hàng (không phân trang)
public function getAllOrdersNoPagination() {
    try {
        $query = "SELECT dh.*, u.ho_ten, u.email 
                  FROM " . $this->table_name . " dh
                  LEFT JOIN users u ON dh.user_id = u.id
                  ORDER BY dh.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in DonHang::getAllOrdersNoPagination: " . $e->getMessage());
        return [];
    }
}
   // Lấy tất cả đơn hàng có phân trang
public function getAllOrders($page = 1, $limit = 10) {
    $start = ($page - 1) * $limit;
    $query = "SELECT dh.*, u.username, u.email 
              FROM " . $this->table_name . " dh
              LEFT JOIN users u ON dh.user_id = u.id
              ORDER BY dh.created_at DESC
              LIMIT :start, :limit";

    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    // Kiểm tra xem có dữ liệu nào được trả về không
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result ? $result : []; // Trả về mảng rỗng nếu không có dữ liệu
}

    // Lấy tổng số đơn hàng
    public function getTotalOrders() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $stmt = $this->conn->query($query);
        return $stmt->fetchColumn();
    }

    // Lấy danh sách đơn hàng theo trạng thái
    public function getOrdersByStatus($status) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE trang_thai = :trang_thai";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":trang_thai", $status);
        $stmt->execute();

        return $stmt;
    }
    // Lấy các đơn hàng đang chờ xác nhận (pending)
    public function getPendingOrders() {
        try {
            $query = "SELECT dh.*, u.ho_ten, u.email 
                    FROM " . $this->table_name . " dh
                    LEFT JOIN users u ON dh.user_id = u.id
                    WHERE dh.trang_thai = 'pending'
                    ORDER BY dh.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in DonHang::getPendingOrders: " . $e->getMessage());
            return [];
        }
    }

}
?>