<?php

class DatabaseSeeder {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Tự động tạo database và seed data
     * Gọi hàm này để khởi tạo toàn bộ database từ đầu
     */
    public function seed(): bool {
        try {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn
            $this->pdo->beginTransaction();
            
            // 1. Tạo bảng users
            $this->createUsersTable();
            
            // 2. Tạo bảng aq_survey_results
            $this->createSurveyResultsTable();
            
            // 3. Tạo bảng practices và các bảng liên quan
            $this->createPracticesTable();
            $this->createTestCasesTable();
            $this->createSubmissionsTable();
            $this->createSubmissionResultsTable();
            
            // 4. Seed dữ liệu mẫu
            $this->seedSampleData();
            
            // Commit transaction
            $this->pdo->commit();
            
            return true;
            
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            $this->pdo->rollBack();
            error_log("Lỗi khi seed database: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo bảng users
     */
    private function createUsersTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fullname VARCHAR(255) NOT NULL,
            birthday DATE NOT NULL,
            username VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->pdo->exec($sql);
    }
    
    /**
     * Tạo bảng aq_survey_results
     */
    private function createSurveyResultsTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS aq_survey_results (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->pdo->exec($sql);
    }

    /**
     * Tạo bảng practices (bài tập)
     */
    private function createPracticesTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS practices (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            difficulty ENUM('easy', 'medium', 'hard') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->pdo->exec($sql);
    }

    /**
     * Tạo bảng test_cases
     */
    private function createTestCasesTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS test_cases (
            id INT AUTO_INCREMENT PRIMARY KEY,
            practice_id INT NOT NULL,
            input TEXT,
            expected_output TEXT NOT NULL,
            is_hidden BOOLEAN NOT NULL DEFAULT 0, -- 0 = false, 1 = true
            FOREIGN KEY (practice_id) REFERENCES practices(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->pdo->exec($sql);
    }

    /**
     * Tạo bảng submissions (lịch sử nộp bài)
     */
    private function createSubmissionsTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS submissions (
            id BIGINT AUTO_INCREMENT PRIMARY KEY, -- Dùng BIGINT vì bảng này sẽ rất lớn
            user_id INT NOT NULL,
            exercise_id INT NOT NULL,
            language VARCHAR(20) NOT NULL,
            code MEDIUMTEXT NOT NULL, -- Dùng MEDIUMTEXT để lưu code dài hơn
            status ENUM('pending', 'accepted', 'wrong_answer', 'time_limit', 'memory_limit', 'error') NOT NULL DEFAULT 'pending',
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (exercise_id) REFERENCES practices(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->pdo->exec($sql);
    }

    /**
     * Tạo bảng submission_results (kết quả chi tiết của từng test case)
     */
    private function createSubmissionResultsTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS submission_results (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            submission_id BIGINT NOT NULL,
            test_case_id INT NOT NULL,
            status ENUM('passed', 'failed') NOT NULL,
            actual_output TEXT,
            execution_time_ms INT,
            memory_usage_kb INT,
            FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE,
            FOREIGN KEY (test_case_id) REFERENCES test_cases(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->pdo->exec($sql);
    }
    
    /**
     * Seed dữ liệu mẫu
     */
    private function seedSampleData(): void {
        // Kiểm tra xem đã có dữ liệu chưa
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $stmt->fetch()['count'];
        
        if ($count > 0) {
            return; // Đã có dữ liệu, không seed nữa
        }
        
        // Tạo user admin mẫu
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (fullname, birthday, username, password) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Administrator', '1990-01-01', 'admin', $adminPassword]);
        
        // Tạo user test mẫu
        $testPassword = password_hash('test123', PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (fullname, birthday, username, password) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Test User', '1995-05-15', 'testuser', $testPassword]);
        


        // Tạo bài tập mẫu
        $stmt = $this->pdo->prepare("INSERT INTO practices (title, description, difficulty) VALUES (?, ?, ?)");
        $stmt->execute(['Tính tổng hai số A và B', // title
                'Viết một chương trình nhận vào hai số nguyên A và B, sau đó in ra tổng của chúng.', // description
                'easy']);
        $stmt->execute(['Bài tập 2', 'Bài tập 2 description', 'medium']);
        $stmt->execute(['Bài tập 3', 'Bài tập 3 description', 'hard']);

        // Tạo test case mẫu
        $stmt = $this->pdo->prepare("INSERT INTO test_cases (practice_id, input, expected_output, is_hidden) VALUES (?, ?, ?, ?)");
        $stmt->execute([1, '1 2', '3', 0]);
        $stmt->execute([1, '-5 5', '0', 0]);

        // Tạo kết quả khảo sát mẫu
        $stmt = $this->pdo->prepare("INSERT INTO aq_survey_results (user_id, username, control_score, ownership_score, reach_score, endurance_score, total_score) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // Kết quả mẫu cho admin
        $stmt->execute([1, 'admin', 8, 7, 9, 6, 30]);
        // Kết quả mẫu cho testuser
        $stmt->execute([2, 'testuser', 6, 8, 7, 9, 30]);
    }
    
    /**
     * Reset database (xóa tất cả dữ liệu và tạo lại)
     */
    public function reset(): bool {
        try {
            $this->pdo->beginTransaction();
            
            // Xóa các bảng
            $this->pdo->exec("DROP TABLE IF EXISTS aq_survey_results");
            $this->pdo->exec("DROP TABLE IF EXISTS users");
            $this->pdo->exec("DROP TABLE IF EXISTS practices");
            $this->pdo->exec("DROP TABLE IF EXISTS test_cases");
            $this->pdo->exec("DROP TABLE IF EXISTS submissions");
            $this->pdo->exec("DROP TABLE IF EXISTS submission_results");
            
            // Tạo lại và seed
            $this->createUsersTable();
            $this->createSurveyResultsTable();
            $this->createPracticesTable();
            $this->createTestCasesTable();
            $this->createSubmissionsTable();
            $this->createSubmissionResultsTable();
            $this->seedSampleData();
            
            $this->pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Lỗi khi reset database: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra database đã được khởi tạo chưa
     */
    public function isInitialized(): bool {
        try {
            $stmt = $this->pdo->query("SHOW TABLES LIKE 'users'");
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
}
