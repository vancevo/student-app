<?php

class DatabaseController {
    private $seeder;
    
    public function __construct(DatabaseSeeder $seeder) {
        $this->seeder = $seeder;
    }
    
    /**
     * Hiển thị trang quản lý database
     */
    public function index(): void {
        $isInitialized = $this->seeder->isInitialized();
        $counts = $this->getCounts();
        
        echo "<!DOCTYPE html>
        <html lang='vi'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Database Management - AQCoder</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
                .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .status { padding: 15px; border-radius: 5px; margin: 20px 0; }
                .status.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
                .status.warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
                .status.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
                .btn { padding: 12px 24px; margin: 10px 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 16px; }
                .btn-primary { background: #007bff; color: white; }
                .btn-success { background: #28a745; color: white; }
                .btn-danger { background: #dc3545; color: white; }
                .btn-warning { background: #ffc107; color: #212529; }
                .btn:hover { opacity: 0.8; }
                .info-box { background: #e9ecef; padding: 20px; border-radius: 5px; margin: 20px 0; }
                h1 { color: #333; text-align: center; }
                h2 { color: #666; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { padding: 10px 12px; border-bottom: 1px solid #e0e0e0; text-align: left; }
                th { background: #f1f3f5; color: #333; }
                .muted { color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>🗄️ Database Management</h1>
                
                <div class='status " . ($isInitialized ? 'success' : 'warning') . "'>
                    <strong>Trạng thái:</strong> " . ($isInitialized ? 'Database đã được khởi tạo' : 'Database chưa được khởi tạo') . "
                </div>
                
                <h2>📋 Thông tin Database</h2>
                <div class='info-box'>
                    <p><strong>Database:</strong> aqcoder</p>
                    <p><strong>Host:</strong> localhost:8889</p>
                    <p class='muted'><strong>Bảng:</strong> users, ranks, practices, test_cases, submissions, submission_results, aq_survey_results</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Bảng</th>
                                <th>Số dòng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>users</td><td>" . $counts['users'] . "</td></tr>
                            <tr><td>ranks</td><td>" . $counts['ranks'] . "</td></tr>
                            <tr><td>practices</td><td>" . $counts['practices'] . "</td></tr>
                            <tr><td>test_cases</td><td>" . $counts['test_cases'] . "</td></tr>
                            <tr><td>submissions</td><td>" . $counts['submissions'] . "</td></tr>
                            <tr><td>submission_results</td><td>" . $counts['submission_results'] . "</td></tr>
                            <tr><td>aq_survey_results</td><td>" . $counts['aq_survey_results'] . "</td></tr>
                        </tbody>
                    </table>
                </div>
                
                <h2>⚙️ Thao tác</h2>
                <div style='text-align: center;'>
                    <a href='?action=seed' class='btn btn-primary'>🌱 Khởi tạo Database</a>
                    <a href='?action=seed-existing' class='btn btn-warning'>➕ Seed Existing DB</a>
                    <a href='?action=reset' class='btn btn-danger' onclick='return confirm(\"Bạn có chắc muốn reset toàn bộ database?\")'>🔄 Reset Database</a>
                    <a href='../' class='btn btn-success'>🏠 Về trang chủ</a>
                </div>
                
                <h2>📝 Hướng dẫn</h2>
                <div class='info-box'>
                    <p><strong>Khởi tạo Database:</strong> Tạo các bảng và dữ liệu mẫu nếu chưa có</p>
                    <p><strong>Reset Database:</strong> Xóa toàn bộ dữ liệu và tạo lại từ đầu</p>
                    <p><strong>Seed Existing DB:</strong> Bổ sung <em>ranks</em>, thêm cột <em>experience</em> cho <em>users</em> nếu thiếu, thêm dữ liệu <em>practices</em> và <em>test_cases</em> nếu chưa có</p>
                    <p><strong>Dữ liệu mẫu:</strong> Tạo user 'admin' (mật khẩu: admin123) và 'testuser' (mật khẩu: test123)</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Khởi tạo database
     */
    public function seed(): void {
        if ($this->seeder->seed()) {
            $this->showMessage("✅ Database đã được khởi tạo thành công!", "success");
        } else {
            $this->showMessage("❌ Có lỗi xảy ra khi khởi tạo database!", "error");
        }
    }
    
    /**
     * Reset database
     */
    public function reset(): void {
        if ($this->seeder->reset()) {
            $this->showMessage("🔄 Database đã được reset thành công!", "success");
        } else {
            $this->showMessage("❌ Có lỗi xảy ra khi reset database!", "error");
        }
    }
    
    /**
     * Seed dữ liệu cho database hiện hữu (không phá dữ liệu cũ)
     */
    public function seedExisting(): void {
        if ($this->seeder->seedExistingDatabase()) {
            $this->showMessage("➕ Đã seed bổ sung cho database hiện có!", "success");
        } else {
            $this->showMessage("❌ Có lỗi xảy ra khi seed bổ sung!", "error");
        }
    }
    
    /**
     * Hiển thị thông báo
     */
    private function showMessage(string $message, string $type): void {
        echo "<!DOCTYPE html>
        <html lang='vi'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Database Management - AQCoder</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
                .message { padding: 20px; border-radius: 5px; margin: 20px 0; font-size: 18px; }
                .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
                .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
                .btn { padding: 12px 24px; margin: 10px 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 16px; background: #007bff; color: white; }
                .btn:hover { opacity: 0.8; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='message $type'>$message</div>
                <a href='?' class='btn'>🔙 Quay lại</a>
                <a href='../' class='btn'>🏠 Về trang chủ</a>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Lấy thống kê số dòng của các bảng
     */
    private function getCounts(): array {
        try {
            // Lấy PDO trực tiếp từ singleton Database
            $pdo = Database::getInstance()->getConnection();
        } catch (\Throwable $e) {
            return [
                'users' => 0,
                'ranks' => 0,
                'practices' => 0,
                'test_cases' => 0,
                'submissions' => 0,
                'submission_results' => 0,
                'aq_survey_results' => 0,
            ];
        }
        
        $tables = ['users','ranks','practices','test_cases','submissions','submission_results','aq_survey_results'];
        $counts = [];
        foreach ($tables as $table) {
            try {
                $check = $pdo->query("SHOW TABLES LIKE '" . $table . "'");
                if ($check && $check->rowCount() > 0) {
                    $row = $pdo->query("SELECT COUNT(*) AS c FROM `" . $table . "`")->fetch();
                    $counts[$table] = (int)$row['c'];
                } else {
                    $counts[$table] = 0;
                }
            } catch (\Throwable $e) {
                $counts[$table] = 0;
            }
        }
        return $counts;
    }
}
