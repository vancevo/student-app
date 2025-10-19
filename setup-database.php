<?php
// Script để kiểm tra và tạo database
require 'app/Core/Database.php';
require 'app/Core/DatabaseSeeder.php';

echo "🔍 Kiểm tra kết nối database...\n";

try {
    $pdo = Database::getInstance()->getConnection();
    echo "✅ Kết nối database thành công!\n";
    
    // Kiểm tra xem database đã được khởi tạo chưa
    $seeder = new DatabaseSeeder($pdo);
    
    if ($seeder->isInitialized()) {
        echo "📋 Database đã được khởi tạo.\n";
        
        // Kiểm tra xem có bảng practices chưa
        $stmt = $pdo->query("SHOW TABLES LIKE 'practices'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Bảng 'practices' đã tồn tại.\n";
        } else {
            echo "⚠️ Bảng 'practices' chưa tồn tại. Đang tạo...\n";
            $seeder->seed();
            echo "✅ Đã tạo bảng 'practices' và các bảng khác.\n";
        }
    } else {
        echo "⚠️ Database chưa được khởi tạo. Đang khởi tạo...\n";
        if ($seeder->seed()) {
            echo "✅ Database đã được khởi tạo thành công!\n";
            echo "📋 Các bảng đã tạo: users, aq_survey_results, practices, test_cases, submissions, submission_results\n";
            echo "👤 User mẫu: admin (admin123), testuser (test123)\n";
        } else {
            echo "❌ Có lỗi xảy ra khi khởi tạo database!\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "\n💡 Hướng dẫn:\n";
    echo "1. Đảm bảo MAMP đang chạy\n";
    echo "2. Kiểm tra port MySQL trong MAMP (thường là 8889)\n";
    echo "3. Kiểm tra username/password trong app/Core/Database.php\n";
    echo "4. Truy cập http://localhost:8889/phpMyAdmin để kiểm tra database 'aqcoder'\n";
}
?>
