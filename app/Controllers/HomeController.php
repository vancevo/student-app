<?php 
    class HomeController {
        private $userModel;

        public function __construct() {
            $database = Database::getInstance();
            $this->userModel = new UserModel($database->getConnection());
        }

        public function index() {
            // session_start() đã được gọi trong index.php
            if (!isset($_SESSION['user_id'])) {
                header('Location: /AQCoder/login'); 
                exit;
            }

            // Lấy thông tin user kèm rank
            $user = $this->userModel->getUserWithRank($_SESSION['user_id']);
            
            if (!$user) {
                header('Location: /AQCoder/login');
                exit;
            }

            // Truyền dữ liệu cho view
            $username = $user['username'];
            $fullname = $user['fullname'];
            $class = $user['class'] ?? '';
            $experience = $user['experience'] ?? 0; // Sử dụng giá trị mặc định nếu chưa có cột experience
            $rankName = $user['rank_name'] ?? 'Chưa xếp hạng';
            $rankColor = $user['rank_color'] ?? '#808080';

            // Tải View Component home.php
            require '../views/home.php'; 
        }
    }
