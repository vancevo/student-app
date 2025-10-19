<?php
// Script đơn giản để kiểm tra kết nối database
$host = 'localhost';
$db   = 'aqcoder';
$user = 'root';
$pass = 'root';
$port = 8889;

echo "🔍 Kiểm tra kết nối database...\n";
echo "Host: $host:$port\n";
echo "Database: $db\n";
echo "User: $user\n\n";

try {
    // Thử kết nối không có database trước
    $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    echo "✅ Kết nối MySQL thành công!\n";
    
    // Kiểm tra xem database có tồn tại không
    $stmt = $pdo->query("SHOW DATABASES LIKE '$db'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Database '$db' đã tồn tại.\n";
        
        // Kết nối đến database cụ thể
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass);
        
        // Kiểm tra các bảng
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "📋 Các bảng hiện có: " . implode(', ', $tables) . "\n";
        
        if (in_array('practices', $tables)) {
            echo "✅ Bảng 'practices' đã tồn tại.\n";
        } else {
            echo "⚠️ Bảng 'practices' chưa tồn tại.\n";
            echo "💡 Hãy truy cập http://localhost:8080/AQCoder/database và nhấn 'Khởi tạo Database'\n";
        }
    } else {
        echo "⚠️ Database '$db' chưa tồn tại.\n";
        echo "💡 Hãy tạo database '$db' trong phpMyAdmin trước.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi kết nối: " . $e->getMessage() . "\n";
    echo "\n💡 Hướng dẫn:\n";
    echo "1. Đảm bảo MAMP đang chạy trên port 8080\n";
    echo "2. Kiểm tra username/password trong MAMP\n";
    echo "3. Truy cập http://localhost:8080/phpMyAdmin để kiểm tra\n";
    echo "4. Tạo database 'aqcoder' nếu chưa có\n";
}
?>
