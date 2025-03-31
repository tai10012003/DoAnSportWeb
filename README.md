# Website Bán Hàng

## Giới thiệu
Đây là một dự án website bán đồ dùng thể thao sử dụng HTML, CSS, Bootstrap, JavaScript, PHP và MySQL.

## Cấu trúc thư mục

### Core Application (`app/`)
- `Controllers/`: Chứa các controller xử lý logic
- `Models/`: Chứa các model tương tác với database
- `Services/`: Chứa business logic
- `Middleware/`: Chứa các middleware

### Frontend (`resources/`)
- `views/`: Chứa các template
  - `admin/`: Giao diện quản trị
  - `shop/`: Giao diện cửa hàng
  - `auth/`: Giao diện đăng nhập/đăng ký
  - `layouts/`: Template layouts

### Public Assets (`public/`)
- `css/`: Stylesheet đã biên dịch
- `js/`: JavaScript đã biên dịch
- `images/`: Hình ảnh tĩnh
- `uploads/`: File người dùng tải lên

### Configuration (`config/`)
Chứa các file cấu hình của ứng dụng

### Database (`database/`)
Chứa migrations và seeds

### Storage (`storage/`)
Chứa logs, cache và các file tạm

## Cài đặt
1. Cài đặt XAMPP hoặc WAMP.
2. Tạo cơ sở dữ liệu tên là `sportshop`.
3. Import file `sportshop.sql` vào cơ sở dữ liệu.
4. Đặt dự án vào thư mục `htdocs` (XAMPP) hoặc `www` (WAMP).
5. Mở trình duyệt và truy cập `http://localhost/sportshop`.

## Sử dụng
- **Trang chủ**: Hiển thị thông tin chung và giới thiệu các sản phẩm nổi bật.
- **Sản phẩm**: Hiển thị danh sách các sản phẩm.
- **Chi tiết sản phẩm**: Hiển thị chi tiết thông tin sản phẩm.
- **Liên hệ**: Form liên hệ.

## Công nghệ sử dụng
- HTML, CSS, Bootstrap
- JavaScript
- PHP
- MySQL

## Phát triển

[Hướng dẫn phát triển]