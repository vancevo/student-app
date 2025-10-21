<?php 
    class AuthController {
        private $userModel;

        // Nhận UserModel Component đã khởi tạo
        public function __construct(UserModel $userModel) {
            $this->userModel = $userModel; 
        }

        // [ACTION 1] Hiển thị form đăng nhập (GET /login)
        public function showLogin() {
            // Biến $error và $message được hiển thị trong views/auth/login.php
            // session_start() đã được gọi trong index.php
            $error = $_SESSION['error'] ?? null; 
            $message = $_SESSION['message'] ?? null;
            unset($_SESSION['error']);
            unset($_SESSION['message']);
            
            require '../views/auth/login.php'; 
        }

        // [ACTION 2] Xử lý POST request Đăng nhập (Thay thế logic log.php)
        public function login() {
            // session_start() đã được gọi trong index.php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                
                if (empty($username) || empty($password)) {
                    $_SESSION['error'] = "Vui lòng nhập đầy đủ Tên đăng nhập và Mật khẩu.";
                } else {
                    // Gọi UserModel Component
                    $user = $this->userModel->findUserByCredentials($username, $password); 
                    
                    if ($user) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username']; 
                        header('Location: /AQCoder/home'); // Chuyển hướng thành công
                        exit;
                    } else {
                        $_SESSION['error'] = "Tên đăng nhập hoặc Mật khẩu không đúng.";
                    }
                }
            }
            header('Location: /AQCoder'); 
            exit;
        }
        
        // [ACTION 3] Xử lý POST request Đăng ký (Thay thế logic reg.php)
        public function register() {
            // session_start() đã được gọi trong index.php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                $fullname = trim($_POST['fullname'] ?? '');
                $birthday = $_POST['birthday'] ?? '';
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                $class = trim($_POST['class'] ?? '');
                
                if (empty($username) || empty($password) || empty($fullname) || empty($class)) {
                    $_SESSION['error'] = "Vui lòng điền đầy đủ các trường bắt buộc.";
                } else {
                    // Gọi UserModel Component (đã sửa lỗi bảo mật)
                    $success = $this->userModel->registerUser($fullname, $birthday, $username, $password, $class);
                    
                    if ($success) {
                        $_SESSION['message'] = "Đăng ký thành công! Vui lòng Đăng nhập.";
                        header('Location: /AQCoder'); 
                        exit;
                    } else {
                        $_SESSION['error'] = "Đăng ký thất bại. Tên đăng nhập có thể đã tồn tại.";
                    }
                }
            }
            
            // Hiển thị lại form đăng ký
            $error = $_SESSION['error'] ?? null; 
            unset($_SESSION['error']);
            require '../views/auth/register.php';
        }
        
        // [ACTION 4] Xử lý Đăng xuất
        public function logout() {
            // session_start() đã được gọi trong index.php
            session_unset(); // Xóa tất cả biến session
            session_destroy(); // Hủy session
            header('Location: /AQCoder/login');
            exit;
        }

        // [ACTION 5] Hiển thị form quên mật khẩu (GET /forgot-password)
        public function showForgotPassword() {
            $error = $_SESSION['error'] ?? null; 
            $message = $_SESSION['message'] ?? null;
            $newPassword = $_SESSION['new_password'] ?? null;
            unset($_SESSION['error']);
            unset($_SESSION['message']);
            unset($_SESSION['new_password']);
            
            require '../views/auth/forgot-password.php'; 
        }

        // [ACTION 6] Xử lý POST request quên mật khẩu
        public function processForgotPassword() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = trim($_POST['username'] ?? '');
                
                if (empty($username)) {
                    $_SESSION['error'] = "Vui lòng nhập tên đăng nhập.";
                } else {
                    // Kiểm tra user có tồn tại không
                    $user = $this->userModel->findUserByUsername($username);
                    
                    if ($user) {
                        // Reset password về 5 số ngẫu nhiên
                        $newPassword = $this->userModel->resetPasswordToRandom($username);
                        
                        if ($newPassword) {
                            $_SESSION['message'] = "Mật khẩu đã được reset thành công!";
                            $_SESSION['new_password'] = $newPassword;
                        } else {
                            $_SESSION['error'] = "Có lỗi xảy ra khi reset mật khẩu.";
                        }
                    } else {
                        $_SESSION['error'] = "Tên đăng nhập không tồn tại.";
                    }
                }
            }
            
            header('Location: /AQCoder/forgot-password');
            exit;
        }
    }