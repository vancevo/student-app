# 🗄️ Database Seeder - AQCoder

## 📋 Mô tả
Hệ thống tự động tạo database và seed dữ liệu mẫu cho ứng dụng AQCoder. Khi gọi hàm hoặc truy cập URL, sẽ tự động tạo các bảng cần thiết và thêm dữ liệu mẫu.

## 🚀 Cách sử dụng

### 1. Truy cập qua Web Interface
```
http://localhost:8889/AQCoder/public/database
```

### 2. Các chức năng có sẵn

#### 🌱 Khởi tạo Database
- Tạo các bảng `users` và `aq_survey_results` nếu chưa có
- Thêm dữ liệu mẫu nếu chưa có
- URL: `http://localhost:8889/AQCoder/public/database?action=seed`

#### 🔄 Reset Database  
- Xóa toàn bộ dữ liệu và tạo lại từ đầu
- URL: `http://localhost:8889/AQCoder/public/database?action=reset`

### 3. Sử dụng trong Code

```php
// Khởi tạo seeder
$pdo = Database::getInstance()->getConnection();
$seeder = new DatabaseSeeder($pdo);

// Khởi tạo database và seed dữ liệu
if ($seeder->seed()) {
    echo "Database đã được khởi tạo thành công!";
}

// Reset database
if ($seeder->reset()) {
    echo "Database đã được reset thành công!";
}

// Kiểm tra database đã khởi tạo chưa
if ($seeder->isInitialized()) {
    echo "Database đã được khởi tạo";
}
```

## 📊 Cấu trúc Database

### Bảng `users`
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Bảng `aq_survey_results`
```sql
CREATE TABLE aq_survey_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    control_score INT NOT NULL DEFAULT 0,
    ownership_score INT NOT NULL DEFAULT 0,
    reach_score INT NOT NULL DEFAULT 0,
    endurance_score INT NOT NULL DEFAULT 0,
    total_score INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## 👥 Dữ liệu mẫu

### User Admin
- **Username:** admin
- **Password:** admin123
- **Fullname:** Administrator
- **Birthday:** 1990-01-01

### User Test
- **Username:** testuser  
- **Password:** test123
- **Fullname:** Test User
- **Birthday:** 1995-05-15

### Kết quả khảo sát mẫu
- Admin: Control(8), Ownership(7), Reach(9), Endurance(6), Total(30)
- Test User: Control(6), Ownership(8), Reach(7), Endurance(9), Total(30)

## 🔧 Cấu hình Database

Cấu hình trong `app/Core/Database.php`:
```php
$host = 'localhost';
$db   = 'aqcoder';
$user = 'root';
$pass = 'root';
$port = 8889;
```

## ⚠️ Lưu ý

1. **Bảo mật:** Chỉ sử dụng trong môi trường development
2. **Backup:** Luôn backup dữ liệu trước khi reset
3. **Transaction:** Tất cả operations đều sử dụng transaction để đảm bảo tính toàn vẹn
4. **Kiểm tra:** Hệ thống sẽ kiểm tra dữ liệu đã tồn tại trước khi seed

## 🛠️ Troubleshooting

### Lỗi kết nối database
- Kiểm tra MAMP đã chạy chưa
- Kiểm tra port 8889
- Kiểm tra database `aqcoder` đã tồn tại chưa

### Lỗi permission
- Đảm bảo user `root` có quyền tạo bảng
- Kiểm tra charset utf8mb4 được hỗ trợ

### Lỗi foreign key
- Đảm bảo bảng `users` được tạo trước `aq_survey_results`
- Kiểm tra engine InnoDB được sử dụng
