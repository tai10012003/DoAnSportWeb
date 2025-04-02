<?php
require_once __DIR__ . '/../../../config/database.php';
$db = new Database();
$conn = $db->getConnection();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu - Sport Elite</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/admin.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        
        <div class="dashboard-content">
            <div class="content-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Thống kê doanh thu</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Thống kê doanh thu</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="revenue-filters mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select id="timeRange" class="form-select">
                            <option value="week">Tuần này</option>
                            <option value="month">Tháng này</option>
                            <option value="year">Năm nay</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="revenue-stats row mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <i class='bx bx-money'></i>
                            <h3>Tổng doanh thu</h3>
                        </div>
                        <div class="stat-card-body">
                            <span id="totalRevenue">0₫</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <i class='bx bx-package'></i>
                            <h3>Số đơn hàng</h3>
                        </div>
                        <div class="stat-card-body">
                            <span id="totalOrders">0</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <i class='bx bx-trending-up'></i>
                            <h3>Trung bình/đơn</h3>
                        </div>
                        <div class="stat-card-body">
                            <span id="averageOrder">0₫</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="revenue-chart">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        let revenueChart;

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        }

        function updateChart(timeRange) {
            fetch(`/WebbandoTT/app/api/admin/get_revenue.php?timeRange=${timeRange}`)
                .then(response => response.json())
                .then(data => {
                    if (revenueChart) {
                        revenueChart.destroy();
                    }

                    const ctx = document.getElementById('revenueChart').getContext('2d');
                    revenueChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Doanh thu',
                                data: data.values,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatCurrency(value);
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Doanh thu: ' + formatCurrency(context.raw);
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Cập nhật thống kê
                    document.getElementById('totalRevenue').textContent = formatCurrency(data.totalRevenue);
                    document.getElementById('totalOrders').textContent = data.totalOrders;
                    document.getElementById('averageOrder').textContent = formatCurrency(data.averageOrder);
                });
        }

        document.getElementById('timeRange').addEventListener('change', function() {
            updateChart(this.value);
        });

        // Load dữ liệu ban đầu
        updateChart('week');
    </script>
</body>
</html>
