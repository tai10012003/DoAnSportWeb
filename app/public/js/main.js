// Cart functionality
document.addEventListener("DOMContentLoaded", function () {
    // Add to cart functionality
    function addToCart(productId, quantity = 1) {
      const data = new URLSearchParams();
      data.append("product_id", productId);
      data.append("quantity", quantity);
  
      fetch("../api/carts/add_to_cart.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: data,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Update cart count
            const cartBadge = document.querySelector(".nav-link .badge");
            if (cartBadge) {
              cartBadge.textContent = data.cart_count;
              cartBadge.classList.add("pulse");
              setTimeout(() => cartBadge.classList.remove("pulse"), 500);
            }
  
            // Show notification
            showToast("Đã thêm vào giỏ hàng!", "success");
            showNotification("Đã thêm vào giỏ hàng!");
            updateCartCount(data.cart_count);
          } else {
            showToast("Có lỗi xảy ra!", "error");
            showNotification("Có lỗi xảy ra!", "error");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showToast("Có lỗi xảy ra!", "error");
        });
    }
    function removeFromCart(productId) {
      Swal.fire({
        title: "Xóa sản phẩm?",
        text: "Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy",
        confirmButtonColor: "#dc3545",
      }).then((result) => {
        if (result.isConfirmed) {
          fetch("../api/carts/remove_from_cart.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `product_id=${productId}`,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                document
                  .querySelector(`[data-product-id="${productId}"]`)
                  .closest(".cart-item")
                  .remove();
                updateCartCount(data.cart_count);
                location.reload();
                if (data.cart_count === 0) {
                  document.querySelector(".cart-table").style.display = "none";
                  document.querySelector(".empty-cart-message").style.display =
                    "block";
                }
              }
            });
        }
      });
    }
    // Hàm cập nhật số lượng sản phẩm trong giỏ hàng
    function updateCartCount(cartCount) {
      const cartBadge = document.querySelector(".cart-badge"); // Lấy phần tử hiển thị số lượng
  
      if (cartBadge) {
        if (cartCount > 0) {
          cartBadge.textContent = cartCount;
          cartBadge.style.display = "inline-block"; // Hiển thị số lượng
        } else {
          cartBadge.style.display = "none"; // Ẩn số lượng nếu giỏ hàng trống
        }
      }
    }
    // Show toast notification
    function showToast(message, type = "success") {
      const toast = document.createElement("div");
      toast.className = `toast-notification ${type}`;
      toast.innerHTML = `
              <div class="toast-content">
                  <i class="bi bi-${
                    type === "success" ? "check-circle" : "exclamation-circle"
                  }-fill"></i>
                  <span>${message}</span>
              </div>
          `;
      document.body.appendChild(toast);
  
      // Trigger animation
      setTimeout(() => toast.classList.add("show"), 10);
  
      // Remove toast after delay
      setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }
  
    // Show notification
    function showNotification(message, type = "success") {
      Swal.fire({
        text: message,
        icon: type,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
      });
    }
    // Hàm cập nhật số lượng
    function updateCartQuantity(productId, quantity) {
      fetch("../api/carts/update_cart.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `product_id=${productId}&quantity=${quantity}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Cập nhật số tiền của sản phẩm
            document.getElementById(`total-${productId}`).textContent =
              new Intl.NumberFormat("vi-VN").format(data.item_total) + "₫";
  
            //   // Cập nhật tổng tiền đơn hàng
            //   document.getElementById("subtotal").textContent =
            //     new Intl.NumberFormat("vi-VN").format(data.cart_total) + "₫";
            //   document.getElementById("shipping-fee").textContent =
            //     new Intl.NumberFormat("vi-VN").format(data.shipping) + "₫";
            //   document.getElementById("total-price").textContent =
            //     new Intl.NumberFormat("vi-VN").format(data.total) + "₫";
  
            updateCartCount(data.cart_count);
            // Kiểm tra nếu giỏ hàng trống, đặt phí vận chuyển = 0₫
            const shippingFee =
              data.cart_count === 0 ? 0 : data.cart_total >= 500000 ? 0 : 30000;
  
            // Cập nhật tổng tiền
            updateCartTotal(data.cart_total, shippingFee);
  
            // Xóa sản phẩm nếu số lượng = 0
            if (quantity <= 0) {
              document
                .querySelector(`[data-product-id="${productId}"]`)
                .closest(".cart-item")
                .remove();
            }
            // Hiển thị thông báo xóa sản phẩm
            showNotification("Đã cập nhật giỏ hàng");
          }
        })
        .catch((error) => console.error("Lỗi cập nhật giỏ hàng:", error));
    }
  
    // Xử lý tăng số lượng
    document.querySelectorAll(".btn-increase").forEach((button) => {
      button.addEventListener("click", function () {
        let productId = this.dataset.productId;
        let input = document.querySelector(
          `.quantity-input[data-product-id="${productId}"]`
        );
        let newQuantity = parseInt(input.value) + 1;
        input.value = newQuantity;
        updateCartQuantity(productId, newQuantity);
      });
    });
  
    //chuyen qua dat hang
    var checkoutbutton = document.querySelector(".btn-checkout");
    if (checkoutbutton) {
      checkoutbutton.addEventListener("click", function () {
        window.location.href = "/WebbandoTT/don-hang";
      });
    }
  
    // Xử lý giảm số lượng
    document.querySelectorAll(".btn-decrease").forEach((button) => {
      button.addEventListener("click", function () {
        let productId = this.dataset.productId;
        let input = document.querySelector(
          `.quantity-input[data-product-id="${productId}"]`
        );
        let newQuantity = parseInt(input.value) - 1;
        if (newQuantity < 0) newQuantity = 0;
        input.value = newQuantity;
        updateCartQuantity(productId, newQuantity);
      });
    });
    // Add event listeners for add to cart buttons
    document.querySelectorAll(".add-to-cart").forEach((button) => {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        const productId = this.getAttribute("data-product-id");
        addToCart(productId, 1);
      });
    });
  
    // Add to cart form in product detail page
    const addToCartForm = document.getElementById("addToCartForm");
    if (addToCartForm) {
      addToCartForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const productId = this.querySelector('input[name="product_id"]').value;
        const quantity = parseInt(
          this.querySelector('input[name="quantity"]').value
        );
        addToCart(productId, quantity);
      });
    }
  
    // Update quantity
    const quantityInputs = document.querySelectorAll(".quantity-input");
    quantityInputs.forEach((input) => {
      input.addEventListener("change", function () {
        updateCart(this.dataset.productId, this.value);
      });
    });
  
    // Remove item
    const removeButtons = document.querySelectorAll(".remove-item");
    removeButtons.forEach((button) => {
      button.addEventListener("click", function () {
        removeFromCart(this.dataset.productId);
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
  
  // Enhanced page loader
  window.addEventListener("load", function () {
    const loader = document.querySelector(".page-loader");
    loader.style.opacity = "0";
    setTimeout(() => {
      loader.style.display = "none";
    }, 500);
  });
  
  // Intersection Observer for animations
  const observerOptions = {
    threshold: 0.1,
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("fade-in");
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);
  
  document.querySelectorAll(".product-card, .feature-box").forEach((el) => {
    observer.observe(el);
  });
  
  // Parallax effect for hero section
  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll(".carousel-item");
  
    parallaxElements.forEach((el) => {
      const speed = 0.5;
      el.style.transform = `translateY(${scrolled * speed}px)`;
    });
  });
  
  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      });
    });
  });
  
  // Navbar Scroll Effect
  window.addEventListener("scroll", function () {
    const navbar = document.querySelector(".navbar");
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  });
  
  // Enhanced Scroll Effect
  let lastScroll = 0;
  const navbar = document.querySelector(".navbar");
  
  window.addEventListener("scroll", () => {
    const currentScroll = window.pageYOffset;
  
    // Thêm class scrolled khi cuộn xuống 50px
    if (currentScroll > 50) {
      navbar.classList.add("scrolled");
  
      // Ẩn navbar khi cuộn xuống, hiện khi cuộn lên
      if (currentScroll > lastScroll && currentScroll > 300) {
        navbar.style.transform = "translateY(-100%)";
      } else {
        navbar.style.transform = "translateY(0)";
        navbar.style.background = "rgba(255,255,255,0.98)";
      }
    } else {
      navbar.classList.remove("scrolled");
      navbar.style.background = "rgba(255,255,255,0.95)";
    }
  
    lastScroll = currentScroll;
  });
  
  // Sửa lại hiệu ứng parallax cho hero section
  document.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const heroImages = document.querySelectorAll(".carousel-item img");
    const heroCaptions = document.querySelectorAll(".carousel-caption");
  
    heroImages.forEach((img) => {
      // Giảm độ dịch chuyển của ảnh
      img.style.transform = `translateY(${scrolled * 0.3}px)`;
    });
  
    heroCaptions.forEach((caption) => {
      // Giữ nguyên vị trí của caption khi scroll
      caption.style.transform = "translateY(50%)";
      // Chỉ thay đổi opacity nhẹ nhàng
      caption.style.opacity = 1 - scrolled * 0.002;
    });
  });
  
  // Active Link Highlight
  document.querySelectorAll(".nav-link").forEach((link) => {
    if (link.href === window.location.href) {
      link.classList.add("active");
    }
  });
  
  // Mobile Menu Handler
  document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.querySelector(".navbar-collapse");
    const navbarToggler = document.querySelector(".navbar-toggler");
  
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
  
    // Handle dropdown menus on mobile
    const dropdowns = document.querySelectorAll(".nav-item.dropdown");
  
    dropdowns.forEach((dropdown) => {
      const link = dropdown.querySelector(".nav-link");
      const submenu = dropdown.querySelector(".submenu");
  
      if (window.innerWidth <= 991) {
        link.addEventListener("click", function (e) {
          e.preventDefault();
          e.stopPropagation();
  
          // Close other open submenus
          dropdowns.forEach((other) => {
            if (other !== dropdown) {
              other.querySelector(".submenu").classList.remove("show");
              other.querySelector(".nav-link").classList.remove("active");
            }
          });
  
          // Toggle current submenu
          submenu.classList.toggle("show");
          link.classList.toggle("active");
        });
      }
    });
  
    // Close mobile menu when clicking outside
    document.addEventListener("click", function (e) {
      if (!e.target.closest(".navbar")) {
        document.querySelector(".navbar-collapse").classList.remove("show");
        dropdowns.forEach((dropdown) => {
          dropdown.querySelector(".submenu").classList.remove("show");
          dropdown.querySelector(".nav-link").classList.remove("active");
        });
      }
    });
  });