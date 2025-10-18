<?php 
    class SurveyModel {
        private $db;
        public function __construct(PDO $db) { $this->db = $db; }

        /**
         * Tái sử dụng: Lưu kết quả khảo sát AQ (Đã sửa lỗi SQL Injection)
         */
        public function saveSurveyResults(int $userId, string $username, array $scores): bool {
            $control = $scores['control'];
            $ownership = $scores['ownership'];
            $reach = $scores['reach'];
            $endurance = $scores['endurance'];
            $total_score = $control + $ownership + $reach + $endurance;

            // SỬA LỖI BẢO MẬT: Dùng dấu hỏi (?)
            $sql = "INSERT INTO aq_survey_results 
                    (`user_id`, `username`, `control_score`, `ownership_score`, `reach_score`, `endurance_score`, `total_score`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);

            // Thực thi Prepared Statement
            return $stmt->execute([
                $userId, $username, $control, $ownership, $reach, $endurance, $total_score
            ]);
        }
    }