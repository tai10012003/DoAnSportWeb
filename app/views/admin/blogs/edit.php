<?php
require_once __DIR__ . '/../../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../../controllers/BlogController.php';
checkAdminAuth();

$blogController = new BlogController();
$postId = $_GET['id'] ?? null;

if (!$postId) {
    header('Location: /WebbandoTT/admin/blogs');
    exit;
}

$data = $blogController->getPostForEdit($postId);
$post = $data['post'];

if (!$post) {
    header('Location: /WebbandoTT/admin/blogs');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa bài viết - Sport Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="/WebbandoTT/app/public/css/admin.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>

        <div class="dashboard-content">
            <div class="content-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Chỉnh sửa bài viết</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/dashboard">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="/WebbandoTT/admin/blogs">Bài viết</a></li>
                                <li class="breadcrumb-item active">Chỉnh sửa</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="updatePostForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label class="form-label required">Tiêu đề bài viết</label>
                                    <input type="text" class="form-control" name="tieu_de" required
                                           value="<?= htmlspecialchars($post['tieu_de']) ?>"
                                           onkeyup="generateSlug(this.value)">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label required">Slug</label>
                                    <input type="text" class="form-control" name="slug" id="slug" required
                                           value="<?= htmlspecialchars($post['slug']) ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" 
                                              rows="3"><?= htmlspecialchars($post['meta_description']) ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Mô tả ngắn</label>
                                    <textarea class="form-control" name="mo_ta_ngan" 
                                              rows="3"><?= htmlspecialchars($post['mo_ta_ngan']) ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Hình ảnh đại diện</label>
                                    <input type="file" class="form-control" name="hinh_anh" accept="image/*">
                                    <div id="image-preview" class="mt-2">
                                        <?php if ($post['hinh_anh']): ?>
                                            <img src="/WebbandoTT/public/uploads/blogs/<?= htmlspecialchars($post['hinh_anh']) ?>" 
                                                 class="img-thumbnail" style="max-height: 200px">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title"
                                           value="<?= htmlspecialchars($post['meta_title']) ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select class="form-select" name="trang_thai">
                                        <option value="1" <?= $post['trang_thai'] == 1 ? 'selected' : '' ?>>Công khai</option>
                                        <option value="0" <?= $post['trang_thai'] == 0 ? 'selected' : '' ?>>Riêng tư</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label required">Nội dung bài viết</label>
                                    <textarea id="editor" name="noi_dung"><?= $post['noi_dung'] ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class='bx bx-arrow-back'></i> Quay lại
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let editor;
        
        class UploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file.then(file => {
                    const formData = new FormData();
                    formData.append('upload', file);

                    return fetch('/WebbandoTT/app/api/blogs/upload-image.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.uploaded) {
                            return { default: result.url };
                        }
                        throw new Error(result.error?.message || 'Upload failed');
                    });
                });
            }

            abort() {
                // Abort upload implementation
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        }

        ClassicEditor
            .create(document.querySelector('#editor'), {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                // Các cấu hình khác nếu cần
            })
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });

        function generateSlug(title) {
            let slug = title.toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[đĐ]/g, 'd')
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '-');
            document.getElementById('slug').value = slug;
        }

        document.querySelector('input[name="hinh_anh"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px">
                    `;
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('updatePostForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('noi_dung', editor.getData());
            
            try {
                const response = await fetch('/WebbandoTT/app/api/blogs/update.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Cập nhật bài viết thành công!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '/WebbandoTT/admin/blogs';
                    });
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: error.message || 'Không thể cập nhật bài viết'
                });
            }
        });
    </script>
</body>
</html>
