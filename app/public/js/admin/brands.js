document.addEventListener('DOMContentLoaded', function() {
    // Delete Brand
    const deleteBrandButtons = document.querySelectorAll('.delete-brand');
    deleteBrandButtons.forEach(button => {
        button.addEventListener('click', handleDeleteBrand);
    });

    async function handleDeleteBrand(e) {
        e.preventDefault();
        const brandId = this.dataset.id;

        const result = await Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa thương hiệu này? Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/WebbandoTT/app/api/brands/delete.php?id=${brandId}`, {
                    method: 'POST'
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã xóa!',
                        text: 'Thương hiệu đã được xóa thành công.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: error.message || 'Không thể xóa thương hiệu'
                });
            }
        }
    }

    // Search Brand
    const searchBrandInput = document.getElementById('searchBrand');
    if (searchBrandInput) {
        searchBrandInput.addEventListener('input', debounce(function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const brandName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const brandCode = row.querySelector('.text-muted.small').textContent.toLowerCase();
                
                if (brandName.includes(searchTerm) || brandCode.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }, 300));
    }

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
});
