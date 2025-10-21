-- =============================================
-- Script SQL để seed data cho AQCoder
-- Chạy script này trong phpMyAdmin hoặc MySQL
-- =============================================

-- 1. Tạo bảng ranks nếu chưa có
CREATE TABLE IF NOT EXISTS ranks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    min_experience INT NOT NULL,
    max_experience INT NOT NULL,
    color VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Thêm cột experience vào bảng users nếu chưa có
ALTER TABLE users ADD COLUMN IF NOT EXISTS experience INT NOT NULL DEFAULT 0;

-- 3. Insert dữ liệu ranks
INSERT IGNORE INTO ranks (name, min_experience, max_experience, color) VALUES
('Sắt', 0, 29, '#808080'),
('Đồng', 30, 59, '#CD7F32'),
('Bạc', 60, 119, '#C0C0C0'),
('Vàng', 120, 199, '#FFD700'),
('Kim cương', 200, 300, '#B9F2FF');

-- 4. Insert users mẫu (chỉ insert nếu chưa có)
INSERT IGNORE INTO users (fullname, birthday, username, password, experience) VALUES
('Administrator', '1990-01-01', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 150),
('Test User', '1995-05-15', 'testuser', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 80);

-- 5. Insert practices mẫu
INSERT IGNORE INTO practices (id, title, description, difficulty) VALUES
(1, 'Tính tổng hai số A và B', 'Viết một chương trình nhận vào hai số nguyên A và B, sau đó in ra tổng của chúng.', 'easy'),
(2, 'Bài tập 2', 'Bài tập 2 description', 'medium'),
(3, 'Bài tập 3', 'Bài tập 3 description', 'hard');

-- 6. Insert test cases mẫu
INSERT IGNORE INTO test_cases (practice_id, input, expected_output, is_hidden) VALUES
(1, '1 2', '3', 0),
(1, '-5 5', '0', 0);

-- 7. Insert survey results mẫu
INSERT IGNORE INTO aq_survey_results (user_id, username, control_score, ownership_score, reach_score, endurance_score, total_score) VALUES
(1, 'admin', 8, 7, 9, 6, 30),
(2, 'testuser', 6, 8, 7, 9, 30);

-- =============================================
-- Kiểm tra dữ liệu đã được insert
-- =============================================

-- Xem ranks
SELECT * FROM ranks ORDER BY min_experience;

-- Xem users với rank
SELECT u.id, u.username, u.experience, r.name as rank_name, r.color as rank_color 
FROM users u 
LEFT JOIN ranks r ON u.experience >= r.min_experience AND u.experience <= r.max_experience;

-- Xem practices
SELECT * FROM practices;

-- Xem test cases
SELECT * FROM test_cases;

-- Xem survey results
SELECT * FROM aq_survey_results;
