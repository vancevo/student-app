<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu - AQCoder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen">

    <!-- Forgot Password Form Container -->
    <div class="forgot-password-container bg-white p-8 sm:p-12 rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                Quên Mật Khẩu
            </h1>
            <p class="text-gray-600">
                Nhập tên đăng nhập để reset mật khẩu
            </p>
        </div>
        
        <!-- Error Message -->
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <!-- Success Message -->
        <?php if (isset($message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <!-- New Password Display -->
        <?php if (isset($newPassword)): ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-6">
                <div class="text-center">
                    <p class="font-semibold mb-2">Mật khẩu mới của bạn:</p>
                    <div class="bg-white border-2 border-blue-300 rounded-lg p-3 mb-3">
                        <span class="text-2xl font-bold text-blue-600"><?= htmlspecialchars($newPassword) ?></span>
                    </div>
                    <p class="text-sm text-blue-600">Vui lòng ghi nhớ mật khẩu này để đăng nhập!</p>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Forgot Password Form -->
        <form method="POST" action="/AQCoder/forgot-password" id="forgot-password-form">
            <div class="mb-6">
                <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập của bạn" required
                       class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-blue-400 transition-colors duration-200">
            </div>
            
            <button type="submit" id="reset-button"
                    class="w-full bg-blue-400 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-500 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 mb-4">
                Reset Mật Khẩu
            </button>
            
            <div class="text-center">
                <a href="/AQCoder" class="text-blue-500 hover:text-blue-700 font-medium">
                    ← Quay lại đăng nhập
                </a>
            </div>
        </form>
    </div>
</body>
</html>
