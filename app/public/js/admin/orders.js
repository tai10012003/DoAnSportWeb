document.addEventListener("DOMContentLoaded", function () {
    // Handle create order
    const createOrderForm = document.getElementById("createOrderForm");
    if (createOrderForm) {
      createOrderForm.addEventListener("submit", async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
  
        try {
          const response = await fetch("/WebbandoTT/app/api/orders/create.php", {
            method: "POST",
            body: formData,
          });
  
          const result = await response.json();
          if (result.success) {
            Swal.fire({
              icon: "success",
              title: "Thành công",
              text: "Tạo đơn hàng thành công!",
              showConfirmButton: false,
              timer: 1500,
            }).then(() => {
              window.location.href = "/WebbandoTT/admin/orders";
            });
          } else {
            throw new Error(result.message || "Có lỗi xảy ra");
          }
        } catch (error) {
          console.error("Error:", error);
          Swal.fire({
            icon: "error",
            title: "Lỗi",
            text: error.message || "Không thể tạo đơn hàng",
          });
        }
      });
    }
  
    // Handle delete order
    const deleteOrderButtons = document.querySelectorAll(".delete-order");
    deleteOrderButtons.forEach((button) => {
      button.addEventListener("click", handleDeleteOrder);
    });
  
    async function handleDeleteOrder(e) {
      e.preventDefault();
      const orderId = this.dataset.id;
  
      const result = await Swal.fire({
        title: "Xác nhận xoá?",
        text: "Bạn có chắc chắn muốn xoá đơn hàng này? Hành động này không thể hoàn tác!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6B7280",
        confirmButtonText: "Xoá",
        cancelButtonText: "Huỷ",
      });
  
      if (result.isConfirmed) {
        try {
          const response = await fetch(
            `/WebbandoTT/app/api/orders/delete.php?id=${orderId}`,
            {
              method: "POST",
            }
          );
  
          const data = await response.json();
  
          if (data.success) {
            Swal.fire({
              icon: "success",
              title: "Đã xoá!",
              text: "Đơn hàng đã được xoá thành công.",
              showConfirmButton: false,
              timer: 1500,
            }).then(() => {
              window.location.reload();
            });
          } else {
            throw new Error(data.message || "Có lỗi xảy ra");
          }
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Lỗi!",
            text: error.message || "Không thể xoá đơn hàng",
          });
        }
      }
    }
  });