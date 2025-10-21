<?php 
    class PracticeModel {
        private $db;
        public function __construct(PDO $db) { $this->db = $db; }

        /**
         * Lấy danh sách tất cả practices từ database (nếu có), fallback sang dummy
         */
        public function getPractices(): array {
            // try {
            //     $stmt = $this->db->query("SELECT id, title, description, difficulty, created_at FROM practices ORDER BY id ASC");
            //     $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            //     if (!empty($rows)) {
            //         return $rows;
            //     }
            // } catch (Exception $e) {
            //     // ignore and fallback
            // }
            return $this->getPracticesFromDummyData();
        }

        /**
         * Lấy danh sách tất cả practices từ dummy data
         */
        public function getPracticesFromDummyData(): array {
            // Load dummy data
            require_once __DIR__ . '/../../config/practices_data.php';
            
            // Trả về trực tiếp biến $practice_data thay vì dùng hàm helper
            return isset($practice_data) && is_array($practice_data) ? $practice_data : [];
        }

        /**
         * Lấy thông tin practice từ dummy data theo ID
         */
        public function getPracticeFromDummyData(int $id): array|false {
            // Load dummy data
            require_once __DIR__ . '/../../config/practices_data.php';
            
            // Lấy dữ liệu cơ bản từ practice_data
            $basicData = null;
            foreach ($practice_data as $practice) {
                if ($practice['id'] == $id) {
                    $basicData = $practice;
                    break;
                }
            }
            
            if (!$basicData) {
                return false;
            }
            
            // Lấy dữ liệu chi tiết từ practice_detail_data
            $detailData = isset($practice_detail_data[$id]) ? $practice_detail_data[$id] : [];
            
            // Merge hai loại dữ liệu
            return array_merge($basicData, $detailData);
        }

        /**
         * Lấy practice theo ID từ database
         */
        public function getPracticeById(int $id): array|false {
            try {
                $stmt = $this->db->prepare("SELECT id, title, description, difficulty, created_at FROM practices WHERE id = ? LIMIT 1");
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row ?: false;
            } catch (Exception $e) {
                return false;
            }
        }

        /**
         * Lấy lịch sử submissions của user cho một practice
         */
        public function getUserSubmissions(int $userId, int $practiceId): array {
            $stmt = $this->db->prepare("
                SELECT s.*, sr.status as test_status, sr.actual_output, sr.execution_time_ms, sr.memory_usage_kb
                FROM submissions s 
                LEFT JOIN submission_results sr ON s.id = sr.submission_id 
                WHERE s.user_id = ? AND s.exercise_id = ? 
                ORDER BY s.submitted_at DESC
            ");
            $stmt->execute([$userId, $practiceId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Lưu submission mới
         */
        public function saveSubmission(int $userId, int $practiceId, string $language, string $code): int {
            $stmt = $this->db->prepare("
                INSERT INTO submissions (user_id, exercise_id, language, code, status) 
                VALUES (?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$userId, $practiceId, $language, $code]);
            return $this->db->lastInsertId();
        }

        /**
         * Cập nhật trạng thái submission
         */
        public function updateSubmissionStatus(int $submissionId, string $status): bool {
            $stmt = $this->db->prepare("UPDATE submissions SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $submissionId]);
        }

        /**
         * Lấy test cases của một practice
         */
        public function getTestCases(int $practiceId): array {
            $stmt = $this->db->prepare("SELECT * FROM test_cases WHERE practice_id = ? ORDER BY id ASC");
            $stmt->execute([$practiceId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Lấy test cases từ dummy data cho một practice
         */
        public function getTestCasesForPractice(int $practiceId): array {
            static $practice_detail_data = null;
            
            // Load dummy data chỉ một lần
            if ($practice_detail_data === null) {
                $configPath = __DIR__ . '/../../config/practices_data.php';
                if (file_exists($configPath)) {
                    // Tạo một scope riêng để load data
                    $practice_detail_data = [];
                    $loadData = function() use ($configPath, &$practice_detail_data) {
                        ob_start();
                        include $configPath;
                        ob_end_clean();
                    };
                    $loadData();
                } else {
                    $practice_detail_data = [];
                }
            }
            
            if (isset($practice_detail_data[$practiceId]['test_cases'])) {
                return $practice_detail_data[$practiceId]['test_cases'];
            }
            
            return [];
        }

        /**
         * Lấy trạng thái hoàn thành của user cho tất cả practices
         */
        public function getCompletionStatus(int $userId): array {
            $sql = "SELECT practice_id, 'excellent' as best_status 
                    FROM completed_practices 
                    WHERE user_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $completionStatus = [];
            foreach ($results as $result) {
                $completionStatus[$result['practice_id']] = $result['best_status'];
            }
            
            return $completionStatus;
        }

        /**
         * Lấy trạng thái hoàn thành của user cho một practice cụ thể
         */
        public function getPracticeCompletionStatus(int $userId, int $practiceId): string {
            $sql = "SELECT MAX(status) as best_status 
                    FROM submissions 
                    WHERE user_id = ? AND exercise_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $practiceId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['best_status'] ?? 'not_attempted';
        }
    }