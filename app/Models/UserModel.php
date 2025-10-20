<?php 
    class UserModel {
        private $db;

        public function __construct(PDO $db) { 
            $this->db = $db;
        }

        // Tái sử dụng: Tìm người dùng và xác thực mật khẩu
        public function findUserByCredentials(string $username, string $password): ?array {
            $stmt = $this->db->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]); 
            $user = $stmt->fetch();

            // Bảo mật: Xác minh mật khẩu thô với mật khẩu BĂM trong DB
            if ($user && password_verify($password, $user['password'] ?? '')) {
                unset($user['password']); 
                return $user;
            }
            return null;
        }

        // Tái sử dụng: Lưu người dùng mới vào DB (Sửa lỗi SQL Injection và Mật khẩu thô)
        public function registerUser(string $fullname, string $birthday, string $username, string $rawPassword, string $class): bool {
            // Bảo mật: Mã hóa mật khẩu trước khi lưu
            $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT); 
            
            $sql = "INSERT INTO users (fullname, birthday, username, password, `class`) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            
            try {
                // Thực thi Prepared Statement
                return $stmt->execute([$fullname, $birthday, $username, $hashedPassword, $class]);
            } catch (\PDOException $e) {
                // Lỗi có thể do trùng username
                error_log("Lỗi khi đăng ký người dùng: " . $e->getMessage()); 
                return false;
            }
        }

        // Lấy thông tin user kèm rank dựa trên experience
        public function getUserWithRank(int $userId): ?array {
            try {
                $sql = "SELECT u.*, r.name as rank_name, r.color as rank_color 
                        FROM users u 
                        LEFT JOIN ranks r ON u.experience >= r.min_experience AND u.experience <= r.max_experience 
                        WHERE u.id = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$userId]);
                $user = $stmt->fetch();
                
                if ($user) {
                    unset($user['password']); // Loại bỏ password khỏi kết quả
                    return $user;
                }
                return null;
            } catch (PDOException $e) {
                // Nếu bảng ranks chưa tồn tại, trả về user không có rank
                if ($e->getCode() == '42S02') { // Table doesn't exist
                    return $this->getUserWithoutRank($userId);
                }
                throw $e;
            }
        }

        // Lấy thông tin user không có rank (fallback khi bảng ranks chưa tồn tại)
        private function getUserWithoutRank(int $userId): ?array {
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user) {
                unset($user['password']);
                // Thêm thông tin rank mặc định
                $user['experience'] = $user['experience'] ?? 0; // Đảm bảo có cột experience
                $user['rank_name'] = 'Chưa xếp hạng';
                $user['rank_color'] = '#808080';
                return $user;
            }
            return null;
        }

        // Cập nhật experience của user
        public function updateExperience(int $userId, int $experience): bool {
            $sql = "UPDATE users SET experience = experience + ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$experience, $userId]);
        }
    }