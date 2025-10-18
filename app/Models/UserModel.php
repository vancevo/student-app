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
        public function registerUser(string $fullname, string $birthday, string $username, string $rawPassword): bool {
            // Bảo mật: Mã hóa mật khẩu trước khi lưu
            $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT); 
            
            $sql = "INSERT INTO users (fullname, birthday, username, password) 
                    VALUES (?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            
            try {
                // Thực thi Prepared Statement
                return $stmt->execute([$fullname, $birthday, $username, $hashedPassword]);
            } catch (\PDOException $e) {
                // Lỗi có thể do trùng username
                error_log("Lỗi khi đăng ký người dùng: " . $e->getMessage()); 
                return false;
            }
        }
    }