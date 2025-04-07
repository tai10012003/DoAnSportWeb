document.addEventListener('DOMContentLoaded', function() {
    // Product Form Submission
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
        addProductForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch('/WebbandoTT/api/products/add', {
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
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: result.message || 'Có lỗi xảy ra'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra khi thêm sản phẩm'
                });
            }
        });
    }

    // Edit Product
    const editButtons = document.querySelectorAll('.edit-product');
    editButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.id;
            try {
                const response = await fetch(`/WebbandoTT/api/products/${productId}`);
                const product = await response.json();
                
                // Populate edit form
                document.getElementById('editProductId').value = product.id;
                document.getElementById('editMaSp').value = product.ma_sp;
                document.getElementById('editTenSp').value = product.ten_sp;
                document.getElementById('editDanhMuc').value = product.danh_muc_id;
                document.getElementById('editThuongHieu').value = product.thuong_hieu_id;
                document.getElementById('editGia').value = product.gia;
                document.getElementById('editGiaKm').value = product.gia_khuyen_mai;
                document.getElementById('editMoTa').value = product.mo_ta;
                
                // Show edit modal
                const editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
                editModal.show();
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể tải thông tin sản phẩm'
                });
            }
        });
    });

    // Delete Product
    const deleteButtons = document.querySelectorAll('.delete-product');
    deleteButtons.forEach(button => {
        button.addEventListener('click', handleDelete);
    });

    async function handleDelete(e) {
        e.preventDefault();
        const productId = this.dataset.id;

        const result = await Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch('/WebbandoTT/app/api/products/delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${productId}`
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã xóa!',
                        text: 'Sản phẩm đã được xóa thành công.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Refresh page sau khi xóa
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: error.message || 'Không thể xóa sản phẩm'
                });
            }
        }
    }

    // Search and Filter functionality
    const searchInput = document.getElementById('searchProduct');
    const categoryFilter = document.getElementById('categoryFilter');
    const brandFilter = document.getElementById('brandFilter');
    const tbody = document.querySelector('.table tbody');

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Function to fetch and update product list
    async function updateProducts(searchTerm = '', categoryId = '', brandId = '') {
        try {
            const response = await fetch(`/WebbandoTT/app/api/products/search.php?search=${searchTerm}&category=${categoryId}&brand=${brandId}`);
            const data = await response.json();

            if (data.success) {
                tbody.innerHTML = data.products.map(product => `
                    <tr>
                        <td>
                            <div class="product-info-cell">
                                <div class="product-img-wrapper">
                                    <img src="${product.hinh_anh ? '/WebbandoTT/public/uploads/products/' + product.hinh_anh : '/WebbandoTT/app/public/images/products/no-image.jpg'}" 
                                         alt="${product.ten_sp}">
                                </div>
                                <div>
                                    <div class="fw-semibold">${product.ten_sp}</div>
                                    <div class="text-muted small">SKU: ${product.ma_sp}</div>
                                </div>
                            </div>
                        </td>
                        <td>${product.ten_danh_muc || 'Chưa phân loại'}</td>
                        <td>
                            <div class="fw-semibold">${new Intl.NumberFormat('vi-VN').format(product.gia)}₫</div>
                            ${product.gia_khuyen_mai ? `<div class="text-danger small">${new Intl.NumberFormat('vi-VN').format(product.gia_khuyen_mai)}₫</div>` : ''}
                        </td>
                        <td>${product.so_luong}</td>
                        <td>
                            <span class="status-badge ${product.tinh_trang == 1 ? 'in-stock' : 'out-of-stock'}">
                                ${product.tinh_trang == 1 ? 'Còn hàng' : 'Hết hàng'}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="/WebbandoTT/admin/products/edit?id=${product.id}" 
                                   class="btn-action" 
                                   title="Sửa">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button class="btn-action delete delete-product" 
                                        data-id="${product.id}" 
                                        title="Xóa">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('') || '<tr><td colspan="6" class="text-center">Không tìm thấy sản phẩm nào</td></tr>';

                // Rebind delete event listeners
                bindDeleteEvents();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Bind event listeners
    searchInput.addEventListener('input', debounce(function(e) {
        updateProducts(
            e.target.value,
            categoryFilter.value,
            brandFilter.value
        );
    }, 300));

    categoryFilter.addEventListener('change', function() {
        updateProducts(
            searchInput.value,
            this.value,
            brandFilter.value
        );
    });

    brandFilter.addEventListener('change', function() {
        updateProducts(
            searchInput.value,
            categoryFilter.value,
            this.value
        );
    });

    function bindDeleteEvents() {
        document.querySelectorAll('.delete-product').forEach(button => {
            button.addEventListener('click', handleDelete);
        });
    }
});







