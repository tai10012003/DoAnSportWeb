<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/ProductController.php';
checkAdminAuth();

$productController = new ProductController();
$data = $productController->index();
$categories = $data['categories'];
$brands = $data['brands'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm mới - Sport Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>

        <div class="dashboard-content">
            <div class="content-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Thêm sản phẩm mới</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/products">Sản phẩm</a></li>
                                <li class="breadcrumb-item active">Thêm mới</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="createProductForm" class="product-form" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-md-8">
                                <div class="form-section">
                                    <h5 class="form-section-title">Thông tin cơ bản</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label required">Mã sản phẩm</label>
                                                <input type="text" class="form-control" name="ma_sp" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label required">Tên sản phẩm</label>
                                                <input type="text" class="form-control" name="ten_sp" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">Mô tả ngắn</label>
                                                <textarea class="form-control" name="mo_ta" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">Mô tả chi tiết</label>
                                                <textarea class="form-control" name="mo_ta_chi_tiet" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section mt-4">
                                    <h5 class="form-section-title">Giá & Kho hàng</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label required">Giá bán</label>
                                                <input type="number" class="form-control" name="gia" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Giá khuyến mãi</label>
                                                <input type="number" class="form-control" name="gia_khuyen_mai">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label required">Số lượng</label>
                                                <input type="number" class="form-control" name="so_luong" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-section mt-4">
                                    <h5 class="form-section-title">Thông số kỹ thuật</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Kích thước<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="kich_thuoc" required
                                                    placeholder="VD: S,M,L,XL hoặc 38,39,40,41,42">
                                                <div class="form-text">Nhập các kích thước, phân cách bằng dấu phẩy</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Màu sắc<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="mau_sac" required
                                                    placeholder="VD: Đen,Trắng,Xám">
                                                <div class="form-text">Nhập các màu sắc, phân cách bằng dấu phẩy</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Chất liệu</label>
                                                <input type="text" class="form-control" name="chat_lieu" 
                                                    placeholder="VD: 95% cotton, 5% spandex">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Xuất xứ</label>
                                                <input type="text" class="form-control" name="xuat_xu"
                                                    placeholder="VD: Việt Nam">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Bảo hành</label>
                                                <input type="text" class="form-control" name="bao_hanh"
                                                    placeholder="VD: 12 tháng">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-section">
                                    <h5 class="form-section-title">Phân loại</h5>
                                    <div class="form-group mb-3">
                                        <label class="form-label required">Danh mục</label>
                                        <select class="form-select" name="danh_muc_id" required>
                                            <option value="">Chọn danh mục</option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['ten_danh_muc']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label required">Thương hiệu</label>
                                        <select class="form-select" name="thuong_hieu_id" required>
                                            <option value="">Chọn thương hiệu</option>
                                            <?php foreach ($brands as $brand): ?>
                                            <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['ten_thuong_hieu']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-section mt-4">
                                    <h5 class="form-section-title">Trạng thái</h5>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="tinh_trang" value="1" checked>
                                        <label class="form-check-label">Còn bán</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="noi_bat" value="1">
                                        <label class="form-check-label">Sản phẩm nổi bật</label>
                                    </div>
                                </div>

                                <div class="form-section mt-4">
                                    <h5 class="form-section-title">Hình ảnh</h5>
                                    <div class="image-upload-container">
                                        <input type="file" class="form-control" name="hinh_anh" accept="image/*" onchange="previewImage(this)">
                                        <div id="imagePreview" class="image-preview mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class='bx bx-arrow-back'></i> Quay lại
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Lưu sản phẩm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-preview';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('createProductForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const response = await fetch('/WebbandoTT/app/api/products/api.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Thêm sản phẩm thành công!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '/WebbandoTT/admin/products';
                    });
                } else {
                    throw new Error(result.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: error.message || 'Không thể thêm sản phẩm'
                });
            }
        });
    </script>
</body>
</html>
