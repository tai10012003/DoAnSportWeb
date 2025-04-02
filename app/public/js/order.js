document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("confirm-order")?.addEventListener("click", function (e) {
        e.preventDefault();
        
        const receiverName = document.getElementById("receiver_name").value;
        const receiverPhone = document.getElementById("receiver_phone").value;
        const BaseAddress = document.getElementById("receiver_address").value;
        const thanhPho = document.getElementById("city_select").value;
        const cityText = document.getElementById("city_select").options[
            document.getElementById("city_select").selectedIndex
        ].text;
        
        const shippingFee = document.querySelector('input[name="phi_van_chuyen"]').value;
        const totalAmount = document.querySelector('input[name="tong_tien"]').value;
        
        const receiverAddress = BaseAddress + ", " + cityText;
        const orderNote = document.getElementById("order_note").value;
        const paymentMethod = document.querySelector(
          'input[name="payment_method"]:checked'
        ).value;

        if (!receiverName || !receiverPhone || !receiverAddress) {
          Swal.fire("Lỗi!", "Vui lòng nhập đầy đủ thông tin nhận hàng", "error");
          return;
        }

        const formData = new FormData();
        formData.append('receiver_name', receiverName);
        formData.append('receiver_phone', receiverPhone);
        formData.append('receiver_address', receiverAddress);
        formData.append('order_note', orderNote);
        formData.append('payment_method', paymentMethod);
        formData.append('shipping_fee', shippingFee);
        formData.append('total_amount', totalAmount);

        Swal.fire({
            title: "Xác nhận đặt hàng?",
            text: "Bạn có chắc chắn muốn đặt hàng không?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Đặt hàng",
            cancelButtonText: "Hủy",
            confirmButtonColor: "#28a745",
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch("/WebbandoTT/app/api/carts/checkout.php", {
                    method: "POST",
                    body: formData
                })
                .then(async response => {
                    const text = await response.text();
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Raw server response:', text);
                        throw new Error('Server response was not valid JSON. Please check server logs.');
                    }
                })
                .then(data => {
                    if (data.success) {
                        if (data.payment_type === 'momo') {
                            // Chuyển hướng đến trang thanh toán MoMo
                            window.location.href = data.payment_url;
                        } else {
                            Swal.fire({
                                icon: "success",
                                title: "Thành công!",
                                text: data.message,
                                confirmButtonText: "Xem đơn hàng"
                            }).then(() => {
                                window.location.href = "/WebbandoTT/don-hang";
                            });
                        }
                    } else {
                        throw new Error(data.message || 'Đặt hàng không thành công');
                    }
                })
                .catch(error => {
                    console.error("Error details:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi!",
                        text: error.message || "Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại sau."
                    });
                });
            }
        });
    });

    // Mobile Menu Handler
    const navbar = document.querySelector(".navbar-collapse");
    const navbarToggler = document.querySelector(".navbar-toggler");
    const body = document.querySelector("body");

    // Toggle menu
    navbarToggler.addEventListener("click", function () {
      body.classList.toggle("menu-open");

      // Add transition delay to nav items
      const navItems = document.querySelectorAll(".nav-item");
      navItems.forEach((item, index) => {
        item.style.transitionDelay = navbar.classList.contains("show")
          ? "0s"
          : `${0.1 * index}s`;
      });
    });

    // Close menu when clicking outside
    document.addEventListener("click", function (e) {
      if (
        !navbar.contains(e.target) &&
        !navbarToggler.contains(e.target) &&
        navbar.classList.contains("show")
      ) {
        navbarToggler.click();
      }
    });

    // Close menu when pressing Escape key
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && navbar.classList.contains("show")) {
        navbarToggler.click();
      }
    });
  });