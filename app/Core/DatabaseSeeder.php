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
            
            // 2. Tạo bảng ranks
            $this->createRanksTable();
            
            // 3. Tạo bảng aq_survey_results
            $this->createSurveyResultsTable();
            
            // 4. Tạo bảng practices và các bảng liên quan
            $this->createPracticesTable();
            $this->createTestCasesTable();
            $this->createSubmissionsTable();
            $this->createSubmissionResultsTable();
            $this->createRanksTable();
            $this->seedRanksData();
            
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
            `class` VARCHAR(100) NOT NULL DEFAULT '',
            experience INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->pdo->exec($sql);
    }
    
    /**
     * Tạo bảng ranks (xếp hạng)
     */
    private function createRanksTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS ranks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            min_experience INT NOT NULL,
            max_experience INT NOT NULL,
            color VARCHAR(20) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
            status ENUM('pending', 'accepted', 'excellent', 'good', 'wrong_answer', 'time_limit', 'memory_limit', 'error') NOT NULL DEFAULT 'pending',
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
     * Seed dữ liệu ranks
     */
    private function seedRanksData(): void {
        // Kiểm tra xem đã có dữ liệu ranks chưa
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM ranks");
        $count = $stmt->fetch()['count'];
        
        if ($count > 0) {
            return; // Đã có dữ liệu ranks, không seed nữa
        }
        
        // Tạo dữ liệu ranks
        $stmt = $this->pdo->prepare("INSERT INTO ranks (name, min_experience, max_experience, color) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Sắt', 0, 29, '#808080']);
        $stmt->execute(['Đồng', 30, 59, '#CD7F32']);
        $stmt->execute(['Bạc', 60, 119, '#C0C0C0']);
        $stmt->execute(['Vàng', 120, 199, '#FFD700']);
        $stmt->execute(['Kim cương', 200, 300, '#B9F2FF']);
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
        
        // Seed ranks data trước
        $this->seedRanksData();

        // Tạo user admin mẫu
        $adminPassword = password_hash('1234', PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (fullname, birthday, username, password, `class`, experience) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Administrator', '1990-01-01', 'admin', $adminPassword, '12A1', 150]);
        
        // Tạo user test mẫu
        $testPassword = password_hash('1234', PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (fullname, birthday, username, password, `class`, experience) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Test User', '1995-05-15', 'testuser', $testPassword, '12A2', 80]);
        


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
            $this->pdo->exec("DROP TABLE IF EXISTS submission_results");
            $this->pdo->exec("DROP TABLE IF EXISTS submissions");
            $this->pdo->exec("DROP TABLE IF EXISTS test_cases");
            $this->pdo->exec("DROP TABLE IF EXISTS practices");
            $this->pdo->exec("DROP TABLE IF EXISTS ranks");
            $this->pdo->exec("DROP TABLE IF EXISTS users");
            
            // Tạo lại và seed
            $this->createUsersTable();
            $this->createRanksTable();
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
    
    /**
     * Seed data cho database đã có (chỉ thêm ranks và cập nhật users)
     */
    public function seedExistingDatabase(): bool {
        try {
            $this->pdo->beginTransaction();
            
            // 1. Seed ranks data
            $this->seedRanksData();
            
            // 2. Thêm cột experience nếu chưa có
            $this->addExperienceColumnIfNotExists();
            // 2b. Thêm cột class nếu chưa có
            $this->addClassColumnIfNotExists();
            
            // 3. Seed thêm users mẫu nếu cần
            $this->seedAdditionalUsers();
            
            // 4. Seed practices và test cases nếu chưa có
            $this->seedPracticesData();
            
            $this->pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Lỗi khi seed existing database: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Thêm cột experience vào bảng users nếu chưa có
     */
    private function addExperienceColumnIfNotExists(): void {
        try {
            // Kiểm tra xem cột experience đã tồn tại chưa
            $stmt = $this->pdo->query("SHOW COLUMNS FROM users LIKE 'experience'");
            if ($stmt->rowCount() == 0) {
                $this->pdo->exec("ALTER TABLE users ADD COLUMN experience INT NOT NULL DEFAULT 0");
            }
        } catch (Exception $e) {
            error_log("Lỗi khi thêm cột experience: " . $e->getMessage());
        }
    }

    /**
     * Thêm cột class vào bảng users nếu chưa có
     */
    private function addClassColumnIfNotExists(): void {
        try {
            $stmt = $this->pdo->query("SHOW COLUMNS FROM users LIKE 'class'");
            if ($stmt->rowCount() == 0) {
                $this->pdo->exec("ALTER TABLE users ADD COLUMN `class` VARCHAR(100) NOT NULL DEFAULT '' AFTER password");
            }
        } catch (Exception $e) {
            error_log("Lỗi khi thêm cột class: " . $e->getMessage());
        }
    }
    
    /**
     * Seed thêm users mẫu
     */
    private function seedAdditionalUsers(): void {
        // Kiểm tra xem đã có user admin chưa
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
        $stmt->execute(['admin']);
        $count = $stmt->fetch()['count'];
        
        if ($count == 0) {
            $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO users (fullname, birthday, username, password, experience) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(['Administrator', '1990-01-01', 'admin', $adminPassword, 150]);
        }
        
        // Kiểm tra xem đã có user test chưa
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
        $stmt->execute(['testuser']);
        $count = $stmt->fetch()['count'];
        
        if ($count == 0) {
            $testPassword = password_hash('test123', PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO users (fullname, birthday, username, password, experience) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(['Test User', '1995-05-15', 'testuser', $testPassword, 80]);
        }
    }
    
    /**
     * Seed practices data
     */
    private function seedPracticesData(): void {
        // Kiểm tra xem đã có practices chưa
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM practices");
        $count = $stmt->fetch()['count'];
        
        if ($count == 0) {
            // Tạo bài tập mẫu
            $stmt = $this->pdo->prepare("INSERT INTO practices (title, description, difficulty) VALUES (?, ?, ?)");
            $stmt->execute(['Tính tổng hai số A và B', 'Viết một chương trình nhận vào hai số nguyên A và B, sau đó in ra tổng của chúng.', 'easy']);
            $stmt->execute(['Bài tập 2', 'Bài tập 2 description', 'medium']);
            $stmt->execute(['Bài tập 3', 'Bài tập 3 description', 'hard']);

            // Tạo test case mẫu
            $stmt = $this->pdo->prepare("INSERT INTO test_cases (practice_id, input, expected_output, is_hidden) VALUES (?, ?, ?, ?)");
            $stmt->execute([1, '1 2', '3', 0]);
            $stmt->execute([1, '-5 5', '0', 0]);
        }
    }
}
