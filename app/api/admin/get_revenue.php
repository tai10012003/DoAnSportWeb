<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$timeRange = $_GET['timeRange'] ?? 'week';
$data = [];

try {
    switch($timeRange) {
        case 'week':
            $query = "SELECT DATE(created_at) as date, 
                            COUNT(*) as total_orders,
                            SUM(tong_tien) as revenue
                     FROM don_hang 
                     WHERE trang_thai = 'completed'
                     AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                     GROUP BY DATE(created_at)
                     ORDER BY date";
            $dateFormat = 'd/m/Y';  // Giữ nguyên định dạng ngày/tháng/năm cho tuần
            break;

        case 'month':
            $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as date,
                            COUNT(*) as total_orders,
                            SUM(tong_tien) as revenue
                     FROM don_hang 
                     WHERE trang_thai = 'completed'
                     AND MONTH(created_at) = MONTH(CURRENT_DATE())
                     AND YEAR(created_at) = YEAR(CURRENT_DATE())
                     GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                     ORDER BY date";
            $dateFormat = 'm/Y';  // Thay đổi thành tháng/năm cho tháng
            break;

        case 'year':
            $query = "SELECT YEAR(created_at) as date,
                            COUNT(*) as total_orders,
                            SUM(tong_tien) as revenue
                     FROM don_hang 
                     WHERE trang_thai = 'completed'
                     AND YEAR(created_at) = YEAR(CURRENT_DATE())
                     GROUP BY YEAR(created_at)
                     ORDER BY date";
            $dateFormat = 'Y';  // Chỉ hiển thị năm
            break;
    }

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    $values = [];
    $totalRevenue = 0;
    $totalOrders = 0;

    // Xử lý dữ liệu trống nếu không có kết quả
    if (empty($results)) {
        $endDate = new DateTime();
        $startDate = clone $endDate;
        
        switch($timeRange) {
            case 'week':
                $startDate->modify('-7 days');
                $interval = new DateInterval('P1D');
                break;
            case 'month':
                $startDate->modify('-12 months');
                $interval = new DateInterval('P1M');
                break;
            case 'year':
                $startDate->modify('-5 years');
                $interval = new DateInterval('P1Y');
                break;
        }

        $period = new DatePeriod($startDate, $interval, $endDate);
        foreach ($period as $date) {
            $labels[] = $date->format($dateFormat);
            $values[] = 0;
        }
    } else {
        foreach ($results as $row) {
            if ($timeRange == 'year') {
                $labels[] = $row['date']; // Trực tiếp sử dụng năm
            } else {
                $date = new DateTime($row['date']);
                $labels[] = $date->format($dateFormat);
            }
            $values[] = (float)$row['revenue'];
            $totalRevenue += (float)$row['revenue'];
            $totalOrders += (int)$row['total_orders'];
        }
    }

    echo json_encode([
        'labels' => $labels,
        'values' => $values,
        'totalRevenue' => $totalRevenue,
        'totalOrders' => $totalOrders,
        'averageOrder' => $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0
    ]);

} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
