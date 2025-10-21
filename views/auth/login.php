<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Đăng Nhập - AQCoder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen">

    <!-- Login Form and Main Content Container -->
    <div class="login-container bg-white p-8 sm:p-12 rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md">
        
        <!-- Login Form Section -->
        <div id="login-section">
            <h1 class="text-center text-3xl font-bold text-gray-800 mb-8">
                Đăng nhập<br>
                <span class="text-blue-500">AQ - Coder with Python</span>
            </h1>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/AQCoder/login" id="login-form">
                <div class="mb-6">
                    <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập của bạn" required
                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-blue-400 transition-colors duration-200">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu của bạn" required
                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-blue-400 transition-colors duration-200">
                </div>
                <div class="flex items-center justify-between mb-6 text-sm text-gray-600">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember-me" name="remember-me" class="rounded text-blue-400 focus:ring-blue-300">
                        <label for="remember-me" class="ml-2">Ghi nhớ tài khoản</label>
                    </div>
                    <a href="/AQCoder/forgot-password" class="text-blue-500 hover:text-blue-700 font-medium">Quên mật khẩu?</a>
                </div>
                <button type="submit" id="login-button"
                        class="w-full bg-blue-400 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-500 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 mb-4">
                    Đăng Nhập
                </button>
                <a href="/AQCoder/register">
                    <button type="button" id="register-button"
                            class="w-full bg-white text-blue-500 border-2 border-blue-400 font-bold py-3 px-4 rounded-lg hover:bg-blue-50 hover:border-blue-500 transition-colors duration-200">
                        Tạo tài khoản mới
                    </button>
                </a>
            </form>
        </div>
    </div>
</body>
</html>
