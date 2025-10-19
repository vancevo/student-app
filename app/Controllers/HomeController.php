<?php 
    class HomeController {
        public function index() {
            // session_start() đã được gọi trong index.php
            if (!isset($_SESSION['user_id'])) {
                header('Location: /AQCoder/login'); 
                exit;
            }

            // Tải View Component home.php (sẽ được tạo ở Bước 4)
            require '../views/home.php'; 
        }
    }
