<div class="dashboard-sidebar">
    <div class="sidebar-header">
        <h4>
            <i class='bx bxs-shopping-bag'></i>
            <span>Sport Elite</span>
        </h4>
    </div>

    <div class="sidebar-menu">
        <div class="sidebar-section">
            <div class="section-title">Quản lý chung</div>
            <div class="nav-item">
                <a href="/WebbandoTT/admin/dashboard" class="nav-link <?php echo $route === '/admin/dashboard' ? 'active' : ''; ?>">
                    <i class='bx bxs-dashboard'></i>
                    <span>Tổng quan</span>
                </a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="section-title">Quản lý sản phẩm</div>
            <div class="nav-item">
                <a href="/WebbandoTT/admin/categories" class="nav-link <?php echo $route === '/admin/categories' ? 'active' : ''; ?>">
                    <i class='bx bxs-category'></i>
                    <span>Danh mục</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="/WebbandoTT/admin/products" class="nav-link <?php echo $route === '/admin/products' ? 'active' : ''; ?>">
                    <i class='bx bxs-shopping-bag'></i>
                    <span>Sản phẩm</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="/WebbandoTT/admin/brands" class="nav-link <?php echo $route === '/admin/brands' ? 'active' : ''; ?>">
                    <i class='bx bxs-medal'></i>
                    <span>Thương hiệu</span>
                </a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="section-title">Quản lý đơn hàng</div>
            <div class="nav-item">
                <a href="/WebbandoTT/admin/orders" class="nav-link <?php echo $route === '/admin/orders' ? 'active' : ''; ?>">
                    <i class='bx bxs-cart'></i>
                    <span>Đơn hàng</span>
                </a>
            </div>
        </div>
        <div class="sidebar-section">
            <div class="section-title">Quản lý bài viết</div>
            <div class="nav-item">
                <a href="/WebbandoTT/admin/blogs" class="nav-link <?php echo $route === '/admin/blogs' ? 'active' : ''; ?>">
                    <i class='bx bxs-file'></i>
                    <span>Bài viết</span>
                </a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="section-title">Quản lý người dùng</div>
            <div class="nav-item">
                <a href="/WebbandoTT/admin/users" class="nav-link <?php echo $route === '/admin/users' ? 'active' : ''; ?>">
                    <i class='bx bxs-user-detail'></i>
                    <span>Người dùng</span>
                </a>
            </div>
        </div>
    </div>

    <div class="sidebar-footer">
        <a href="javascript:void(0)" onclick="handleAdminLogout()" class="logout-link">
            <i class='bx bx-log-out'></i>
            <span style="margin-left: 10px;">Đăng xuất</span>
        </a>
    </div>
</div>

<script>
function handleAdminLogout() {
    if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
        fetch('/WebbandoTT/dang-xuat', {
            method: 'POST',
            credentials: 'include'
        })
        .then(() => {
            window.location.href = '/WebbandoTT/dang-nhap';
        })
        .catch(error => {
            console.error('Lỗi khi đăng xuất:', error);
        });
    }
}
</script>
