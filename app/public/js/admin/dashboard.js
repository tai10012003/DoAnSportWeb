document.addEventListener('DOMContentLoaded', function() {
    async function handleOrderStatusUpdate(orderId, orderCode, status, actionText) {
        try {
            const response = await fetch('/WebbandoTT/app/api/orders/update-status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: orderId,
                    trang_thai: status
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                const orderRow = document.getElementById(`order-${orderId}`);
                if (orderRow) {
                    orderRow.remove();
                    checkEmptyTable();
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: `Đơn hàng đã được ${actionText}`,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: error.message || `Không thể ${actionText.toLowerCase()} đơn hàng`
            });
        }
    }

    // Xử lý xác nhận đơn hàng
    document.querySelectorAll('.confirm-order').forEach(button => {
        button.addEventListener('click', async function() {
            const orderId = this.dataset.id;
            const orderCode = this.dataset.orderCode;

            const result = await Swal.fire({
                title: 'Xác nhận đơn hàng',
                text: `Bạn muốn xác nhận đơn hàng ${orderCode}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            });

            if (result.isConfirmed) {
                await handleOrderStatusUpdate(orderId, orderCode, 'processing', 'xác nhận');
            }
        });
    });

    // Xử lý hủy đơn hàng
    document.querySelectorAll('.cancel-order').forEach(button => {
        button.addEventListener('click', async function() {
            const orderId = this.dataset.id;
            const orderCode = this.dataset.orderCode;

            const result = await Swal.fire({
                title: 'Hủy đơn hàng',
                text: `Bạn có chắc muốn hủy đơn hàng ${orderCode}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Hủy đơn',
                cancelButtonText: 'Không'
            });

            if (result.isConfirmed) {
                await handleOrderStatusUpdate(orderId, orderCode, 'cancelled', 'hủy');
            }
        });
    });

    // Kiểm tra bảng rỗng
    function checkEmptyTable() {
        const tbody = document.getElementById('pendingOrdersList');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="text-muted">Không có đơn hàng nào chờ xử lý</div>
                    </td>
                </tr>
            `;
        }
    }
});