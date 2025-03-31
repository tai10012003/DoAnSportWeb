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

-- Bổ sung thông số kỹ thuật cho bảng san_pham
ALTER TABLE san_pham
ADD COLUMN kich_thuoc VARCHAR(50) COMMENT 'Size: S,M,L,XL,XXL hoặc 38,39,40,41,42' AFTER mo_ta_chi_tiet,
ADD COLUMN mau_sac VARCHAR(50) COMMENT 'Các màu sắc của sản phẩm' AFTER kich_thuoc,
ADD COLUMN chat_lieu VARCHAR(100) COMMENT 'Chất liệu sản phẩm' AFTER mau_sac,
ADD COLUMN xuat_xu VARCHAR(100) COMMENT 'Xuất xứ sản phẩm' AFTER chat_lieu,
ADD COLUMN bao_hanh VARCHAR(50) COMMENT 'Thời gian bảo hành' AFTER xuat_xu;

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

INSERT INTO san_pham (
    ma_sp, 
    ten_sp,
    mo_ta,
    gia,
    gia_khuyen_mai,
    so_luong,
    danh_muc_id,
    thuong_hieu_id,
    noi_bat,
    kich_thuoc,
    mau_sac,
    chat_lieu,
    xuat_xu,
    bao_hanh
) VALUES
-- Giày thể thao
('SP004', 'Giày chạy bộ Nike Air Max', 'Giày chạy bộ cao cấp', 3200000, 2800000, 30, 1, 1, 1,
'39,40,41,42,43', 'Đen,Trắng,Xám', '95% vải mesh, 5% cao su tổng hợp', 'Việt Nam', '12 tháng'),

-- Áo tập gym
('SP005', 'Áo thun tập gym Adidas', 'Áo thun thể thao nam', 450000, 380000, 100, 1, 2, 1,
'S,M,L,XL', 'Đen,Xám,Xanh navy', '85% polyester, 15% spandex', 'Thái Lan', '1 tháng'),

-- Quần thể thao
('SP006', 'Quần short tập gym Puma', 'Quần short thể thao nam', 350000, 299000, 80, 1, 3, 1,
'M,L,XL,XXL', 'Đen,Xám,Xanh đậm', '92% polyester, 8% spandex', 'Indonesia', '1 tháng'),

-- Dụng cụ tập gym
('SP007', 'Tạ tay Adidas 10kg', 'Tạ tay cao cấp', 890000, 799000, 40, 1, 2, 1,
'10kg', 'Đen', 'Thép carbon, cao su tổng hợp', 'Trung Quốc', '24 tháng'),

-- Bóng đá
('SP008', 'Bóng đá Nike Strike', 'Bóng đá chính hãng', 750000, 699000, 50, 2, 1, 1,
'Size 5', 'Trắng-Đen', 'Da tổng hợp cao cấp', 'Pakistan', '6 tháng');

-- Thêm admin user mặc định
INSERT INTO users (username, password, email, ho_ten, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Administrator', 'admin');
