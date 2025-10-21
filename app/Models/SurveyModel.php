<?php 
    class SurveyModel {
        private $db;
        public function __construct(PDO $db) { $this->db = $db; }

        /**
         * Lưu hoặc cập nhật kết quả khảo sát AQ
         * Nếu user_id đã tồn tại thì cập nhật, nếu chưa có thì tạo mới
         */
        public function saveSurveyResults(int $userId, string $username, array $scores): bool {
            $control = $scores['control'];
            $ownership = $scores['ownership'];
            $reach = $scores['reach'];
            $endurance = $scores['endurance'];
            $total_score = $control + $ownership + $reach + $endurance;

            // Sử dụng INSERT ... ON DUPLICATE KEY UPDATE để upsert
            $sql = "INSERT INTO aq_survey_results 
                    (`user_id`, `username`, `control_score`, `ownership_score`, `reach_score`, `endurance_score`, `total_score`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    `username` = VALUES(`username`),
                    `control_score` = VALUES(`control_score`),
                    `ownership_score` = VALUES(`ownership_score`),
                    `reach_score` = VALUES(`reach_score`),
                    `endurance_score` = VALUES(`endurance_score`),
                    `total_score` = VALUES(`total_score`),
                    `updated_at` = CURRENT_TIMESTAMP";
            
            $stmt = $this->db->prepare($sql);

            // Thực thi Prepared Statement
            return $stmt->execute([
                $userId, $username, $control, $ownership, $reach, $endurance, $total_score
            ]);
        }

        /**
         * Lấy kết quả khảo sát của user theo user_id
         */
        public function getUserSurveyResults(int $userId): ?array {
            $sql = "SELECT * FROM aq_survey_results WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            
            $result = $stmt->fetch();
            return $result ?: null;
        }
    }