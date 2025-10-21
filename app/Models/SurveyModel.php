<?php 
    class SurveyModel {
        private $db;
        public function __construct(PDO $db) { $this->db = $db; }

        /**
         * Lưu kết quả khảo sát AQ mới
         * Cho phép lưu nhiều lần cùng 1 user_id
         */
        public function saveSurveyResults(int $userId, string $username, array $scores): bool {
            $control = $scores['control'];
            $ownership = $scores['ownership'];
            $reach = $scores['reach'];
            $endurance = $scores['endurance'];
            $total_score = $control + $ownership + $reach + $endurance;

            // Luôn tạo bản ghi mới
            $sql = "INSERT INTO aq_survey_results 
                    (`user_id`, `username`, `control_score`, `ownership_score`, `reach_score`, `endurance_score`, `total_score`, `created_at`, `updated_at`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $stmt = $this->db->prepare($sql);

            // Thực thi Prepared Statement
            return $stmt->execute([
                $userId, $username, $control, $ownership, $reach, $endurance, $total_score
            ]);
        }

        /**
         * Lấy kết quả khảo sát mới nhất của user theo user_id
         */
        public function getUserSurveyResults(int $userId): ?array {
            $sql = "SELECT * FROM aq_survey_results WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            
            $result = $stmt->fetch();
            return $result ?: null;
        }

        /**
         * Lấy tất cả lịch sử khảo sát của user theo user_id
         */
        public function getAllUserSurveyResults(int $userId): array {
            $sql = "SELECT * FROM aq_survey_results WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            
            return $stmt->fetchAll();
        }
    }