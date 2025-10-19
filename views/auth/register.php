<?php
// File: views/auth/register.php

// Biến $error được truyền từ AuthController Component 
// Kiểm tra và lấy thông báo lỗi từ session (nếu có)
$error = $_SESSION['error'] ?? null; 
unset($_SESSION['error']); // Xóa khỏi session sau khi lấy ra
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Đăng Ký</title>
    <link rel="stylesheet" href="/assets/css/style.css"> 
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body class="bg-blue-50 flex items-center justify-center min-h-screen">
    <div class="login-container bg-white p-8 sm:p-12 rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md">
        
        <div id="register-section">
            <h1 class="text-center text-3xl font-bold text-gray-800 mb-8">
                Đăng ký<br>
                <span class="text-blue-500">Tạo tài khoản mới</span>
            </h1>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="/AQCoder/register" method="POST" id="register-form">
                <div class="mb-6">
                    <label for="full-name" class="block text-gray-700 text-sm font-semibold mb-2">Họ và tên</label>
                    <input type="text" id="full-name" name="fullname" placeholder="Nhập họ và tên của bạn" required
                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-blue-400 transition-colors duration-200">
                </div>
                <div class="mb-6">
                    <label for="dob" class="block text-gray-700 text-sm font-semibold mb-2">Ngày tháng năm sinh</label>
                    <input type="date" id="birthday" name="birthday" required
                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-blue-400 transition-colors duration-200">
                </div>
                <div class="mb-6">
                    <label for="register-username" class="block text-gray-700 text-sm font-semibold mb-2">Tên đăng nhập</label>
                    <input type="text" id="register-username" name="username" placeholder="Tên đăng nhập của bạn" required
                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-blue-400 transition-colors duration-200">
                </div>
                <div class="mb-6">
                    <label for="register-password" class="block text-gray-700 text-sm font-semibold mb-2">Mật khẩu</label>
                    <input type="password" id="register-password" name="password" placeholder="Tạo mật khẩu" required
                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-blue-400 transition-colors duration-200">
                </div>
                <button type="submit" id="register-button"
                        class="w-full bg-blue-400 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-500 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 mb-4">
                    Đăng Ký
                </button><br>
                
                <div align="center"><a href="/login">Quay lại trang đăng nhập?</a></div>
            </form>
        </div>
        
        </div>
</body>
</html>