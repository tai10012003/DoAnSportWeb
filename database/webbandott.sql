-- Tạo database
CREATE DATABASE IF NOT EXISTS webbandott
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE webbandott;

-- Bảng danh mục
CREATE TABLE danh_muc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ma_danh_muc VARCHAR(50) NOT NULL UNIQUE,
    ten_danh_muc VARCHAR(255) NOT NULL,
    mo_ta TEXT,
    hinh_anh VARCHAR(255),
    danh_muc_cha_id INT,
    thu_tu INT DEFAULT 0,
    trang_thai TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (danh_muc_cha_id) REFERENCES danh_muc(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Bảng thương hiệu
CREATE TABLE thuong_hieu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ma_thuong_hieu VARCHAR(50) NOT NULL UNIQUE,
    ten_thuong_hieu VARCHAR(255) NOT NULL,
    mo_ta TEXT,
    logo VARCHAR(255),
    website VARCHAR(255),
    thu_tu INT DEFAULT 0,
    trang_thai TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bảng sản phẩm
CREATE TABLE san_pham (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ma_sp VARCHAR(50) NOT NULL UNIQUE,
    ten_sp VARCHAR(255) NOT NULL,
    mo_ta TEXT,
    mo_ta_chi_tiet TEXT,
    gia DECIMAL(15,2) NOT NULL,
    gia_khuyen_mai DECIMAL(15,2),
    so_luong INT DEFAULT 0,
    hinh_anh VARCHAR(255),
    danh_muc_id INT,
    thuong_hieu_id INT,
    tinh_trang TINYINT(1) DEFAULT 1,
    noi_bat TINYINT(1) DEFAULT 0,
    luot_xem INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (danh_muc_id) REFERENCES danh_muc(id) ON DELETE SET NULL,
    FOREIGN KEY (thuong_hieu_id) REFERENCES thuong_hieu(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Thêm dữ liệu mẫu cho danh mục
INSERT INTO danh_muc (ma_danh_muc, ten_danh_muc, mo_ta) VALUES
('gym', 'Thiết bị tập gym', 'Các thiết bị tập luyện chuyên nghiệp'),
('team-sports', 'Thể thao đồng đội', 'Dụng cụ cho các môn thể thao đồng đội'),
('accessories', 'Phụ kiện thể thao', 'Các phụ kiện thể thao đa dạng');

-- Thêm dữ liệu mẫu cho thương hiệu
INSERT INTO thuong_hieu (ma_thuong_hieu, ten_thuong_hieu, website) VALUES
('nike', 'Nike', 'https://nike.com'),
('adidas', 'Adidas', 'https://adidas.com'),
('puma', 'Puma', 'https://puma.com');

-- Thêm dữ liệu mẫu cho sản phẩm
INSERT INTO san_pham (ma_sp, ten_sp, mo_ta, gia, gia_khuyen_mai, so_luong, danh_muc_id, thuong_hieu_id, noi_bat) VALUES
('SP001', 'Giày chạy bộ Nike Air Zoom', 'Giày chạy bộ cao cấp', 2500000, 2000000, 50, 1, 1, 1),
('SP002', 'Áo bóng đá Adidas', 'Áo thi đấu chính hãng', 890000, 750000, 100, 2, 2, 1),
('SP003', 'Găng tay tập gym', 'Găng tay chống trượt', 450000, 380000, 200, 3, 3, 1);

-- Tạo bảng users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    ho_ten VARCHAR(255) NOT NULL,
    so_dien_thoai VARCHAR(20),
    dia_chi TEXT,
    role ENUM('admin', 'user') DEFAULT 'user',
    trang_thai TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tạo bảng đơn hàng
CREATE TABLE don_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ma_don_hang VARCHAR(50) NOT NULL UNIQUE,
    user_id INT,
    tong_tien DECIMAL(15,2) NOT NULL,
    phi_van_chuyen DECIMAL(15,2) DEFAULT 0,
    trang_thai ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    ghi_chu TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tạo bảng chi tiết đơn hàng
CREATE TABLE chi_tiet_don_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_hang_id INT NOT NULL,
    san_pham_id INT NOT NULL,
    so_luong INT NOT NULL,
    gia DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (don_hang_id) REFERENCES don_hang(id) ON DELETE CASCADE,
    FOREIGN KEY (san_pham_id) REFERENCES san_pham(id) ON DELETE NO ACTION
) ENGINE=InnoDB;

-- Thêm admin user mặc định
INSERT INTO users (username, password, email, ho_ten, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Administrator', 'admin');
