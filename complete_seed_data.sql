-- =============================================
-- Script SQL hoàn chỉnh để seed data cho AQCoder
-- Chạy script này trong phpMyAdmin hoặc MySQL
-- =============================================

-- =============================================
-- 1. TẠO TẤT CẢ CÁC BẢNG
-- =============================================

-- 1.1. Tạo bảng users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    `class` VARCHAR(100) NOT NULL DEFAULT '',
    experience INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1.2. Tạo bảng ranks (xếp hạng)
CREATE TABLE IF NOT EXISTS ranks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    min_experience INT NOT NULL,
    max_experience INT NOT NULL,
    color VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1.3. Tạo bảng practices (bài tập)
CREATE TABLE IF NOT EXISTS practices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    difficulty ENUM('easy', 'medium', 'hard') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1.4. Tạo bảng test_cases
CREATE TABLE IF NOT EXISTS test_cases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    practice_id INT NOT NULL,
    input TEXT,
    expected_output TEXT NOT NULL,
    is_hidden BOOLEAN NOT NULL DEFAULT 0,
    FOREIGN KEY (practice_id) REFERENCES practices(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1.5. Tạo bảng submissions (lịch sử nộp bài)
CREATE TABLE IF NOT EXISTS submissions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    language VARCHAR(20) NOT NULL,
    code MEDIUMTEXT NOT NULL,
    status ENUM('pending', 'accepted', 'excellent', 'good', 'wrong_answer', 'time_limit', 'memory_limit', 'error') NOT NULL DEFAULT 'pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES practices(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1.6. Tạo bảng submission_results (kết quả chi tiết của từng test case)
CREATE TABLE IF NOT EXISTS submission_results (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    submission_id BIGINT NOT NULL,
    test_case_id INT NOT NULL,
    status ENUM('passed', 'failed') NOT NULL,
    actual_output TEXT,
    execution_time_ms INT,
    memory_usage_kb INT,
    FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE,
    FOREIGN KEY (test_case_id) REFERENCES test_cases(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1.7. Tạo bảng aq_survey_results
CREATE TABLE IF NOT EXISTS aq_survey_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    control_score INT NOT NULL DEFAULT 0,
    ownership_score INT NOT NULL DEFAULT 0,
    reach_score INT NOT NULL DEFAULT 0,
    endurance_score INT NOT NULL DEFAULT 0,
    total_score INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 2. SEED DỮ LIỆU CHO TẤT CẢ CÁC BẢNG
-- =============================================

-- 2.1. Insert dữ liệu ranks
INSERT IGNORE INTO ranks (name, min_experience, max_experience, color) VALUES
('Sắt', 0, 29, '#808080'),
('Đồng', 30, 59, '#CD7F32'),
('Bạc', 60, 119, '#C0C0C0'),
('Vàng', 120, 199, '#FFD700'),
('Kim cương', 200, 300, '#B9F2FF'),
('Bạch kim', 301, 500, '#E5E4E2'),
('Thạch anh', 501, 1000, '#00FFFF');

-- 2.2. Insert users mẫu
INSERT IGNORE INTO users (fullname, birthday, username, password, `class`, experience) VALUES
('Administrator', '1990-01-01', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '12A1', 150),
('Test User', '1995-05-15', 'testuser', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '12A2', 80),
('Nguyễn Văn A', '1992-03-20', 'nguyenvana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '11B1', 45),
('Trần Thị B', '1998-07-10', 'tranthib', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '10C3', 120),
('Lê Văn C', '1995-12-05', 'levanc', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '12A3', 200),
('Phạm Thị D', '1993-09-18', 'phamthid', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '11B2', 75),
('Hoàng Văn E', '1997-04-25', 'hoangvane', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '12A4', 300),
('Vũ Thị F', '1994-11-12', 'vuthif', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '10C2', 95);

-- 2.3. Insert practices mẫu
INSERT IGNORE INTO practices (id, title, description, difficulty) VALUES
(1, 'Tính tổng hai số A và B', 'Viết một chương trình nhận vào hai số nguyên A và B, sau đó in ra tổng của chúng. Đây là bài tập cơ bản về phép cộng hai số nguyên, phù hợp cho người mới bắt đầu học lập trình.', 'easy'),
(2, 'Tìm số lớn nhất trong mảng', 'Tìm giá trị lớn nhất trong một mảng số nguyên. Bài tập về thuật toán tìm kiếm cơ bản, giúp hiểu về cách duyệt mảng và so sánh giá trị.', 'easy'),
(3, 'Kiểm tra số nguyên tố', 'Xác định một số có phải là số nguyên tố hay không. Bài tập về thuật toán kiểm tra số nguyên tố, yêu cầu hiểu về vòng lặp và điều kiện.', 'medium'),
(4, 'Sắp xếp mảng tăng dần', 'Sắp xếp các phần tử trong mảng theo thứ tự tăng dần. Bài tập về thuật toán sắp xếp, có thể sử dụng bubble sort hoặc các thuật toán khác.', 'medium'),
(5, 'Tìm kiếm nhị phân', 'Tìm kiếm một phần tử trong mảng đã sắp xếp bằng thuật toán tìm kiếm nhị phân. Bài tập nâng cao về thuật toán tìm kiếm hiệu quả.', 'hard'),
(6, 'Tính giai thừa', 'Tính giai thừa của một số nguyên dương n. Bài tập về đệ quy và vòng lặp, giúp hiểu về cách tính toán phức tạp.', 'medium'),
(7, 'Fibonacci', 'Tính số Fibonacci thứ n. Bài tập về đệ quy và thuật toán động, giúp hiểu về tối ưu hóa thuật toán.', 'hard'),
(8, 'Kiểm tra chuỗi palindrome', 'Kiểm tra xem một chuỗi có phải là palindrome hay không. Bài tập về xử lý chuỗi và thuật toán so sánh.', 'easy'),
(9, 'Tìm ước số chung lớn nhất', 'Tìm ước số chung lớn nhất của hai số nguyên dương. Bài tập về thuật toán Euclid và toán học.', 'medium'),
(10, 'Sắp xếp nhanh (Quick Sort)', 'Triển khai thuật toán sắp xếp nhanh để sắp xếp một mảng số nguyên.', 'hard');

-- 2.4. Insert test cases mẫu
INSERT IGNORE INTO test_cases (practice_id, input, expected_output, is_hidden) VALUES
-- Test cases cho bài tập 1 (Tính tổng)
(1, '1 2', '3', 0),
(1, '-5 5', '0', 0),
(1, '100 200', '300', 0),
(1, '-10 -20', '-30', 1),
(1, '0 0', '0', 1),

-- Test cases cho bài tập 2 (Tìm số lớn nhất)
(2, '3 1 4 1 5', '5', 0),
(2, '10 20 30', '30', 0),
(2, '-1 -5 -3', '-1', 0),
(2, '5', '5', 1),
(2, '1 1 1 1', '1', 1),

-- Test cases cho bài tập 3 (Kiểm tra số nguyên tố)
(3, '7', 'true', 0),
(3, '4', 'false', 0),
(3, '2', 'true', 0),
(3, '1', 'false', 1),
(3, '17', 'true', 1),

-- Test cases cho bài tập 4 (Sắp xếp mảng)
(4, '3 1 4 1 5', '1 1 3 4 5', 0),
(4, '5 2 8 1', '1 2 5 8', 0),
(4, '1', '1', 1),
(4, '3 3 3', '3 3 3', 1),

-- Test cases cho bài tập 5 (Tìm kiếm nhị phân)
(5, '1 2 3 4 5 3', '2', 0),
(5, '1 3 5 7 9 5', '2', 0),
(5, '2 4 6 8 10 6', '2', 1),
(5, '1 2 3 4 5 1', '0', 1),

-- Test cases cho bài tập 6 (Tính giai thừa)
(6, '5', '120', 0),
(6, '3', '6', 0),
(6, '1', '1', 1),
(6, '0', '1', 1),

-- Test cases cho bài tập 7 (Fibonacci)
(7, '5', '5', 0),
(7, '10', '55', 0),
(7, '1', '1', 1),
(7, '2', '1', 1),

-- Test cases cho bài tập 8 (Palindrome)
(8, 'racecar', 'true', 0),
(8, 'hello', 'false', 0),
(8, 'a', 'true', 1),
(8, 'ab', 'false', 1),

-- Test cases cho bài tập 9 (Ước số chung lớn nhất)
(9, '12 18', '6', 0),
(9, '15 25', '5', 0),
(9, '7 13', '1', 1),
(9, '100 50', '50', 1),

-- Test cases cho bài tập 10 (Quick Sort)
(10, '3 1 4 1 5', '1 1 3 4 5', 0),
(10, '5 2 8 1 9', '1 2 5 8 9', 0),
(10, '1', '1', 1),
(10, '2 2 2', '2 2 2', 1);

-- 2.5. Insert submissions mẫu
INSERT IGNORE INTO submissions (user_id, exercise_id, language, code, status, submitted_at) VALUES
(1, 1, 'python', 'a, b = map(int, input().split())\nprint(a + b)', 'accepted', '2024-01-15 10:30:00'),
(2, 1, 'python', 'a, b = map(int, input().split())\nprint(a + b)', 'accepted', '2024-01-15 11:15:00'),
(3, 1, 'python', 'a, b = map(int, input().split())\nprint(a + b)', 'accepted', '2024-01-15 12:00:00'),
(1, 2, 'python', 'arr = list(map(int, input().split()))\nprint(max(arr))', 'accepted', '2024-01-16 09:20:00'),
(2, 2, 'python', 'arr = list(map(int, input().split()))\nmax_val = arr[0]\nfor num in arr:\n    if num > max_val:\n        max_val = num\nprint(max_val)', 'accepted', '2024-01-16 10:45:00'),
(4, 3, 'python', 'def is_prime(n):\n    if n < 2:\n        return False\n    for i in range(2, int(n**0.5) + 1):\n        if n % i == 0:\n            return False\n    return True\n\nn = int(input())\nprint("true" if is_prime(n) else "false")', 'accepted', '2024-01-17 14:30:00'),
(5, 4, 'python', 'arr = list(map(int, input().split()))\narr.sort()\nprint(" ".join(map(str, arr)))', 'accepted', '2024-01-18 16:20:00'),
(6, 5, 'python', 'def binary_search(arr, target):\n    left, right = 0, len(arr) - 1\n    while left <= right:\n        mid = (left + right) // 2\n        if arr[mid] == target:\n            return mid\n        elif arr[mid] < target:\n            left = mid + 1\n        else:\n            right = mid - 1\n    return -1\n\narr = list(map(int, input().split()))\ntarget = arr[-1]\narr = arr[:-1]\nprint(binary_search(arr, target))', 'accepted', '2024-01-19 11:45:00'),
(7, 6, 'python', 'def factorial(n):\n    if n <= 1:\n        return 1\n    return n * factorial(n - 1)\n\nn = int(input())\nprint(factorial(n))', 'accepted', '2024-01-20 13:15:00'),
(8, 7, 'python', 'def fibonacci(n):\n    if n <= 1:\n        return n\n    return fibonacci(n - 1) + fibonacci(n - 2)\n\nn = int(input())\nprint(fibonacci(n))', 'accepted', '2024-01-21 15:30:00');

-- 2.6. Insert submission_results mẫu
INSERT IGNORE INTO submission_results (submission_id, test_case_id, status, actual_output, execution_time_ms, memory_usage_kb) VALUES
-- Kết quả cho submission 1 (bài tập 1)
(1, 1, 'passed', '3', 15, 1024),
(1, 2, 'passed', '0', 12, 1024),
(1, 3, 'passed', '300', 18, 1024),
(1, 4, 'passed', '-30', 14, 1024),
(1, 5, 'passed', '0', 10, 1024),

-- Kết quả cho submission 2 (bài tập 1)
(2, 1, 'passed', '3', 16, 1024),
(2, 2, 'passed', '0', 13, 1024),
(2, 3, 'passed', '300', 19, 1024),
(2, 4, 'passed', '-30', 15, 1024),
(2, 5, 'passed', '0', 11, 1024),

-- Kết quả cho submission 3 (bài tập 1)
(3, 1, 'passed', '3', 14, 1024),
(3, 2, 'passed', '0', 11, 1024),
(3, 3, 'passed', '300', 17, 1024),
(3, 4, 'passed', '-30', 13, 1024),
(3, 5, 'passed', '0', 9, 1024),

-- Kết quả cho submission 4 (bài tập 2)
(4, 6, 'passed', '5', 20, 1024),
(4, 7, 'passed', '30', 18, 1024),
(4, 8, 'passed', '-1', 16, 1024),
(4, 9, 'passed', '5', 14, 1024),
(4, 10, 'passed', '1', 12, 1024),

-- Kết quả cho submission 5 (bài tập 2)
(5, 6, 'passed', '5', 25, 1024),
(5, 7, 'passed', '30', 22, 1024),
(5, 8, 'passed', '-1', 20, 1024),
(5, 9, 'passed', '5', 18, 1024),
(5, 10, 'passed', '1', 16, 1024);

-- 2.7. Insert survey results mẫu
INSERT IGNORE INTO aq_survey_results (user_id, username, control_score, ownership_score, reach_score, endurance_score, total_score, created_at, updated_at) VALUES
(1, 'admin', 8, 7, 9, 6, 30, '2024-01-15 10:00:00', '2024-01-15 10:00:00'),
(2, 'testuser', 6, 8, 7, 9, 30, '2024-01-15 11:00:00', '2024-01-15 11:00:00'),
(3, 'nguyenvana', 5, 6, 7, 8, 26, '2024-01-16 09:00:00', '2024-01-16 09:00:00'),
(4, 'tranthib', 7, 8, 6, 7, 28, '2024-01-16 14:00:00', '2024-01-16 14:00:00'),
(5, 'levanc', 9, 8, 9, 8, 34, '2024-01-17 10:00:00', '2024-01-17 10:00:00'),
(6, 'phamthid', 6, 7, 8, 6, 27, '2024-01-17 15:00:00', '2024-01-17 15:00:00'),
(7, 'hoangvane', 8, 9, 8, 9, 34, '2024-01-18 09:00:00', '2024-01-18 09:00:00'),
(8, 'vuthif', 7, 6, 7, 8, 28, '2024-01-18 16:00:00', '2024-01-18 16:00:00');

-- =============================================
-- 3. KIỂM TRA DỮ LIỆU ĐÃ ĐƯỢC INSERT
-- =============================================

-- 3.1. Xem ranks
SELECT '=== RANKS ===' as info;
SELECT * FROM ranks ORDER BY min_experience;

-- 3.2. Xem users với rank
SELECT '=== USERS WITH RANKS ===' as info;
SELECT u.id, u.username, u.fullname, u.experience, r.name as rank_name, r.color as rank_color 
FROM users u 
LEFT JOIN ranks r ON u.experience >= r.min_experience AND u.experience <= r.max_experience
ORDER BY u.experience DESC;

-- 3.3. Xem practices
SELECT '=== PRACTICES ===' as info;
SELECT id, title, difficulty, LEFT(description, 50) as description_preview 
FROM practices 
ORDER BY difficulty, id;

-- 3.4. Xem test cases
SELECT '=== TEST CASES ===' as info;
SELECT tc.id, p.title as practice_title, tc.input, tc.expected_output, tc.is_hidden
FROM test_cases tc
JOIN practices p ON tc.practice_id = p.id
ORDER BY tc.practice_id, tc.id;

-- 3.5. Xem submissions
SELECT '=== SUBMISSIONS ===' as info;
SELECT s.id, u.username, p.title as practice_title, s.language, s.status, s.submitted_at
FROM submissions s
JOIN users u ON s.user_id = u.id
JOIN practices p ON s.exercise_id = p.id
ORDER BY s.submitted_at DESC;

-- 3.6. Xem submission results
SELECT '=== SUBMISSION RESULTS ===' as info;
SELECT sr.id, s.id as submission_id, u.username, p.title as practice_title, sr.status, sr.execution_time_ms
FROM submission_results sr
JOIN submissions s ON sr.submission_id = s.id
JOIN users u ON s.user_id = u.id
JOIN practices p ON s.exercise_id = p.id
ORDER BY sr.id;

-- 3.7. Xem survey results
SELECT '=== SURVEY RESULTS ===' as info;
SELECT sr.id, u.username, sr.control_score, sr.ownership_score, sr.reach_score, sr.endurance_score, sr.total_score, sr.created_at
FROM aq_survey_results sr
JOIN users u ON sr.user_id = u.id
ORDER BY sr.total_score DESC;

-- 3.8. Thống kê tổng quan
SELECT '=== STATISTICS ===' as info;
SELECT 
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM practices) as total_practices,
    (SELECT COUNT(*) FROM test_cases) as total_test_cases,
    (SELECT COUNT(*) FROM submissions) as total_submissions,
    (SELECT COUNT(*) FROM submission_results) as total_submission_results,
    (SELECT COUNT(*) FROM aq_survey_results) as total_survey_results,
    (SELECT COUNT(*) FROM ranks) as total_ranks;

-- =============================================
-- 4. CÁC CÂU LỆNH HỮU ÍCH KHÁC
-- =============================================

-- 4.1. Xem top users theo experience
SELECT '=== TOP USERS BY EXPERIENCE ===' as info;
SELECT u.username, u.fullname, u.experience, r.name as rank_name, r.color as rank_color
FROM users u
LEFT JOIN ranks r ON u.experience >= r.min_experience AND u.experience <= r.max_experience
ORDER BY u.experience DESC
LIMIT 10;

-- 4.2. Xem practices theo difficulty
SELECT '=== PRACTICES BY DIFFICULTY ===' as info;
SELECT difficulty, COUNT(*) as count
FROM practices
GROUP BY difficulty
ORDER BY FIELD(difficulty, 'easy', 'medium', 'hard');

-- 4.3. Xem submissions theo status
SELECT '=== SUBMISSIONS BY STATUS ===' as info;
SELECT status, COUNT(*) as count
FROM submissions
GROUP BY status
ORDER BY count DESC;

-- 4.4. Xem users có nhiều submissions nhất
SELECT '=== TOP USERS BY SUBMISSIONS ===' as info;
SELECT u.username, u.fullname, COUNT(s.id) as submission_count
FROM users u
LEFT JOIN submissions s ON u.id = s.user_id
GROUP BY u.id, u.username, u.fullname
ORDER BY submission_count DESC;

-- 4.5. Xem practices có nhiều submissions nhất
SELECT '=== TOP PRACTICES BY SUBMISSIONS ===' as info;
SELECT p.title, p.difficulty, COUNT(s.id) as submission_count
FROM practices p
LEFT JOIN submissions s ON p.id = s.exercise_id
GROUP BY p.id, p.title, p.difficulty
ORDER BY submission_count DESC;

-- =============================================
-- KẾT THÚC SCRIPT
-- =============================================
