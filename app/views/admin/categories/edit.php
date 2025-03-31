<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/CategoryController.php';
checkAdminAuth();

$categoryController = new CategoryController();
$categoryId = $_GET['id'] ?? null;

if (!$categoryId) {
    header('Location: /WebbandoTT/admin/categories');
    exit;
}

$data = $categoryController->getCategoryForEdit($categoryId);
$category = $data['category'];
$parentCategories = $categoryController->getAllParentCategories();

if (!$category) {
    header('Location: /WebbandoTT/admin/categories');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật danh mục - Sport Elite</title>
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
                        <h4>Cập nhật danh mục</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/categories">Danh mục</a></li>
                                <li class="breadcrumb-item active">Cập nhật</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="updateCategoryForm" class="form-product" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($category['id']) ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-section">
                                    <h5 class="form-section-title">Thông tin cơ bản</h5>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label required">Mã danh mục</label>
                                                <input type="text" class="form-control" name="ma_danh_muc" 
                                                       value="<?= htmlspecialchars($category['ma_danh_muc']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label required">Tên danh mục</label>
                                                <input type="text" class="form-control" name="ten_danh_muc" 
                                                       value="<?= htmlspecialchars($category['ten_danh_muc']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">Mô tả</label>
                                                <textarea class="form-control" name="mo_ta" rows="3"><?= htmlspecialchars($category['mo_ta']) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-section">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Danh mục cha</label>
                                        <select class="form-select" name="danh_muc_cha_id">
                                            <option value="">Không có</option>
                                            <?php foreach ($parentCategories as $parentCategory): ?>
                                                <?php if ($parentCategory['id'] != $category['id']): ?>
                                                    <option value="<?= $parentCategory['id'] ?>" 
                                                            <?= $parentCategory['id'] == $category['danh_muc_cha_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($parentCategory['ten_danh_muc']) ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Thứ tự hiển thị</label>
                                        <input type="number" class="form-control" name="thu_tu" 
                                               value="<?= $category['thu_tu'] ?>" min="0">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select class="form-select" name="trang_thai">
                                            <option value="1" <?= $category['trang_thai'] == 1 ? 'selected' : '' ?>>Hiện</option>
                                            <option value="0" <?= $category['trang_thai'] == 0 ? 'selected' : '' ?>>Ẩn</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <h5 class="form-section-title">Hình ảnh</h5>
                                        <div class="image-upload-container">
                                            <input type="file" class="form-control" name="hinh_anh" accept="image/*" onchange="previewImage(this)">
                                            <div id="imagePreview" class="image-preview mt-2">
                                                <?php if (!empty($category['hinh_anh'])): ?>
                                                    <img src="/WebbandoTT/public/uploads/categories/<?= htmlspecialchars($category['hinh_anh']) ?>" 
                                                         class="img-thumbnail" style="max-height: 200px">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class='bx bx-arrow-back'></i> Quay lại
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Cập nhật danh mục
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px">`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('updateCategoryForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const response = await fetch('/WebbandoTT/app/api/categories/update.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Cập nhật danh mục thành công!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '/WebbandoTT/admin/categories';
                    });
                } else {
                    throw new Error(result.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: error.message || 'Không thể cập nhật danh mục'
                });
            }
        });
    </script>
</body>
</html>
