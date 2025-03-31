# Cấu trúc thư mục dự án

```
WebbandoTT/
├── app/                    # Core application
│   ├── Controllers/        # Xử lý logic
│   ├── Models/            # Tương tác với database
│   ├── Services/          # Business logic
│   └── Middleware/        # Middleware functions
├── config/                # Cấu hình ứng dụng
├── database/              # Database migrations & seeds
├── public/               # Public assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   ├── images/           # Image assets
│   └── uploads/          # User uploaded files
├── resources/            # Frontend resources
│   ├── views/            # Template files
│   │   ├── admin/       # Admin panel views
│   │   ├── auth/        # Authentication views
│   │   ├── layouts/     # Layout templates
│   │   └── shop/        # Shop frontend views
│   ├── lang/            # Language files
│   └── assets/          # Raw assets (SCSS, JS)
├── routes/               # Route definitions
├── storage/              # Application storage
│   ├── app/             # Application storage
│   ├── logs/            # Log files
│   └── cache/           # Cache files
├── tests/               # Unit & feature tests
└── vendor/              # Dependencies
```
