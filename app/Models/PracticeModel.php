<?php 
    class PracticeModel {
        private $db;
        public function __construct(PDO $db) { $this->db = $db; }

        /**
         * Lấy danh sách tất cả practices
         */
        public function getPractices(): array {
            $stmt = $this->db->query("SELECT * FROM practices ORDER BY id ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Lấy thông tin chi tiết practice theo ID
         */
        public function getPracticeById(int $id): array|false {
            $stmt = $this->db->prepare("SELECT * FROM practices WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
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
    }