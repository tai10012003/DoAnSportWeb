document.addEventListener('DOMContentLoaded', function() {
    // Delete Blog functionality
    const deletePostButtons = document.querySelectorAll('.delete-post');
    deletePostButtons.forEach(button => {
        button.addEventListener('click', handleDeletePost);
    });

    async function handleDeletePost(e) {
        e.preventDefault();
        const postId = this.dataset.id;

        const result = await Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa bài viết này? Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/WebbandoTT/app/api/blogs/delete.php?id=${postId}`, {
                    method: 'POST'
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã xóa!',
                        text: 'Bài viết đã được xóa thành công.',
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
                    text: error.message || 'Không thể xóa bài viết'
                });
            }
        }
    }

    // Search Blog functionality
    const searchBlogInput = document.getElementById('searchBlog');
    if (searchBlogInput) {
        searchBlogInput.addEventListener('input', debounce(function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const title = row.querySelector('.fw-semibold').textContent.toLowerCase();
                const author = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || author.includes(searchTerm)) {
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
