<?php
class DanhGia {
    private $conn;
    private $table_name = "danh_gia_san_pham";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($san_pham_id, $user_id, $diem_danh_gia, $noi_dung) {
        try {
            error_log("Creating review with: " . print_r([
                'san_pham_id' => $san_pham_id,
                'user_id' => $user_id,
                'diem_danh_gia' => $diem_danh_gia,
                'noi_dung' => $noi_dung
            ], true));

            $query = "INSERT INTO " . $this->table_name . " 
                    (san_pham_id, user_id, diem_danh_gia, noi_dung) 
                    VALUES (:san_pham_id, :user_id, :diem_danh_gia, :noi_dung)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":san_pham_id", $san_pham_id, PDO::PARAM_INT);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":diem_danh_gia", $diem_danh_gia, PDO::PARAM_INT);
            $stmt->bindParam(":noi_dung", $noi_dung, PDO::PARAM_STR);

            $result = $stmt->execute();
            error_log("Review creation result: " . ($result ? "success" : "failed"));
            return $result;
        } catch(PDOException $e) {
            error_log("Error in DanhGia::create: " . $e->getMessage());
            return false;
        }
    }

    public function getProductReviews($san_pham_id) {
        try {
            $query = "SELECT dg.*, u.username as ten_nguoi_dung 
                     FROM " . $this->table_name . " dg
                     INNER JOIN users u ON dg.user_id = u.id
                     WHERE dg.san_pham_id = :san_pham_id
                     ORDER BY dg.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":san_pham_id", $san_pham_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in DanhGia::getProductReviews: " . $e->getMessage());
            return [];
        }
    }

    public function getAverageRating($san_pham_id) {
        try {
            $query = "SELECT AVG(diem_danh_gia) as avg_rating, COUNT(*) as total_reviews 
                     FROM " . $this->table_name . " 
                     WHERE san_pham_id = :san_pham_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":san_pham_id", $san_pham_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in DanhGia::getAverageRating: " . $e->getMessage());
            return ['avg_rating' => 0, 'total_reviews' => 0];
        }
    }
}
