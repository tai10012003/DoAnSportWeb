document.addEventListener("DOMContentLoaded", function () {
    document
      .getElementById("confirm-order")
      ?.addEventListener("click", function () {
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

        console.log("receiverAddress: khi gui di", receiverAddress);
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
            fetch("/WebbandoTT/app/api/carts/checkout.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded",
              },
              body: `receiver_name=${receiverName}&receiver_phone=${receiverPhone}&receiver_address=${receiverAddress}&order_note=${orderNote}&payment_method=${paymentMethod}&shipping_fee=${shippingFee}&total_amount=${totalAmount}`,
            })
              .then((response) => response.json())
              .then((data) => {
                if (data.success) {
                  Swal.fire(
                    "Thành công",
                    "Đơn hàng của bạn đã được đặt!",
                    "success"
                  ).then(() => {
                    window.location.href = "/WebbandoTT/don-hang";
                  });
                } else {
                  Swal.fire("Lỗi!", data.message || "Đặt hàng thất bại", "error");
                }
              })
              .catch((error) => {
                console.error("Lỗi:", error);
                Swal.fire("Lỗi!", "Có lỗi xảy ra khi đặt hàng", "error");
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