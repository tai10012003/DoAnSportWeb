// Cart functionality
document.addEventListener('DOMContentLoaded', function () {
    
    // Add to cart functionality
    function addToCart(productId, quantity = 1) {
        const data = new URLSearchParams();
        data.append('product_id', productId);
        data.append('quantity', quantity);

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                const cartBadge = document.querySelector('.nav-link .badge');
                if (cartBadge) {
                    cartBadge.textContent = data.cart_count;
                    cartBadge.classList.add('pulse');
                    setTimeout(() => cartBadge.classList.remove('pulse'), 500);
                }
                
                // Show notification
                showToast('Đã thêm vào giỏ hàng!', 'success');
                showNotification('Đã thêm vào giỏ hàng!');
                updateCartCount(data.cart_count);
            } else {
                showToast('Có lỗi xảy ra!', 'error');
                showNotification('Có lỗi xảy ra!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra!', 'error');
        });
    }
    // Remove from cart functionality
    function removeFromCart(productId) {
        Swal.fire({
            title: 'Xóa sản phẩm?',
            text: "Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#dc3545',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('remove_from_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`[data-product-id="${productId}"]`).closest('.cart-item').remove();
                        updateCartCount(data.cart_count);
                        updateCartTotal(data.cart_total);
                        showNotification('Đã xóa sản phẩm khỏi giỏ hàng');
                        
                        if (data.cart_count === 0) {
                            location.reload(); // Reload if cart is empty
                        }
                    }
                });
            }
        });
    }


    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}-fill"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 10);
        
        // Remove toast after delay
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Show notification
    function showNotification(message, type = 'success') {
        Swal.fire({
            text: message,
            icon: type,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }

    // Add event listeners for add to cart buttons
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            addToCart(productId, 1);
        });
    });

    // Add to cart form in product detail page
    const addToCartForm = document.getElementById('addToCartForm');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const productId = this.querySelector('input[name="product_id"]').value;
            const quantity = parseInt(this.querySelector('input[name="quantity"]').value);
            addToCart(productId, quantity);
        });
    }

    // Update quantity
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateCart(this.dataset.productId, this.value);
        });
    });

    // Remove item
    const removeButtons = document.querySelectorAll('.remove-item');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            removeFromCart(this.dataset.productId);
        });
    });

    // Mobile Menu Handler
    const navbar = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const body = document.querySelector('body');

    // Toggle menu
    navbarToggler.addEventListener('click', function() {
        body.classList.toggle('menu-open');
        
        // Add transition delay to nav items
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach((item, index) => {
            item.style.transitionDelay = navbar.classList.contains('show') ? '0s' : `${0.1 * index}s`;
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navbar.contains(e.target) && 
            !navbarToggler.contains(e.target) && 
            navbar.classList.contains('show')) {
            navbarToggler.click();
        }
    });

    // Close menu when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && navbar.classList.contains('show')) {
            navbarToggler.click();
        }
    });
});

// Enhanced page loader
window.addEventListener('load', function() {
    const loader = document.querySelector('.page-loader');
    loader.style.opacity = '0';
    setTimeout(() => {
        loader.style.display = 'none';
    }, 500);
});

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.product-card, .feature-box').forEach((el) => {
    observer.observe(el);
});

// Parallax effect for hero section
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.carousel-item');
    
    parallaxElements.forEach((el) => {
        const speed = 0.5;
        el.style.transform = `translateY(${scrolled * speed}px)`;
    });
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Navbar Scroll Effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Enhanced Scroll Effect
let lastScroll = 0;
const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    // Thêm class scrolled khi cuộn xuống 50px
    if (currentScroll > 50) {
        navbar.classList.add('scrolled');
        
        // Ẩn navbar khi cuộn xuống, hiện khi cuộn lên
        if (currentScroll > lastScroll && currentScroll > 300) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
            navbar.style.background = 'rgba(255,255,255,0.98)';
        }
    } else {
        navbar.classList.remove('scrolled');
        navbar.style.background = 'rgba(255,255,255,0.95)';
    }
    
    lastScroll = currentScroll;
});

// Sửa lại hiệu ứng parallax cho hero section
document.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const heroImages = document.querySelectorAll('.carousel-item img');
    const heroCaptions = document.querySelectorAll('.carousel-caption');
    
    heroImages.forEach(img => {
        // Giảm độ dịch chuyển của ảnh
        img.style.transform = `translateY(${scrolled * 0.3}px)`;
    });
    
    heroCaptions.forEach(caption => {
        // Giữ nguyên vị trí của caption khi scroll
        caption.style.transform = 'translateY(50%)';
        // Chỉ thay đổi opacity nhẹ nhàng
        caption.style.opacity = 1 - (scrolled * 0.002);
    });
});

// Active Link Highlight
document.querySelectorAll('.nav-link').forEach(link => {
    if (link.href === window.location.href) {
        link.classList.add('active');
    }
});

// Mobile Menu Handler
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navbar.contains(e.target) && 
            !navbarToggler.contains(e.target) && 
            navbar.classList.contains('show')) {
            navbarToggler.click();
        }
    });

    // Handle dropdown menus on mobile
    const dropdowns = document.querySelectorAll('.nav-item.dropdown');
    
    dropdowns.forEach(dropdown => {
        const link = dropdown.querySelector('.nav-link');
        const submenu = dropdown.querySelector('.submenu');
        
        if (window.innerWidth <= 991) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close other open submenus
                dropdowns.forEach(other => {
                    if (other !== dropdown) {
                        other.querySelector('.submenu').classList.remove('show');
                        other.querySelector('.nav-link').classList.remove('active');
                    }
                });
                
                // Toggle current submenu
                submenu.classList.toggle('show');
                link.classList.toggle('active');
            });
        }
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.navbar')) {
            document.querySelector('.navbar-collapse').classList.remove('show');
            dropdowns.forEach(dropdown => {
                dropdown.querySelector('.submenu').classList.remove('show');
                dropdown.querySelector('.nav-link').classList.remove('active');
            });
        }
    });
});
