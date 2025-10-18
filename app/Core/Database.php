<?php 
    class Database {
        private static $instance = null; // Biến lưu instance Singleton
        private $connection;             // Biến lưu trữ kết nối PDO

        // Ngăn chặn tạo instance trực tiếp
        private function __construct() { 
            // Lấy các thông tin từ file connect cũ của bạn
            $host = 'localhost';
            $db   = 'aqcoder';
            $user = 'root';
            $pass = 'root';
            $port = 8889; // Cần thêm port vào DSN nếu khác cổng mặc định (3306)

            // DSN (Data Source Name) cho PDO
            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
            
            $options = [
                // Báo lỗi dưới dạng Exception (Rất quan trọng cho việc debug)
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,        
                // Lấy dữ liệu dưới dạng mảng kết hợp (chuẩn mực)
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   
                // Tắt chế độ giả lập Prepared Statements trong PDO (nên dùng)
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            try {
                // Thiết lập kết nối PDO
                $this->connection = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                // GHI LỖI VÀO LOG thay vì echo ra màn hình (Phù hợp Production)
                error_log("Lỗi kết nối CSDL: " . $e->getMessage()); 
                
                // Dừng ứng dụng và báo lỗi 500
                http_response_code(500);
                die("❌ LỖI HỆ THỐNG: Không thể kết nối đến Database."); 
            }
        }

        // Phương thức tĩnh để lấy instance duy nhất (Singleton Component)
        public static function getInstance(): Database {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        // Phương thức để các Model Component khác sử dụng lại kết nối
        public function getConnection(): PDO {
            return $this->connection;
        }
    }