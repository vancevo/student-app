<?php 
require 'partials/header.php'; 
?>

<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Chào mừng, <?= htmlspecialchars($username) ?>!</h1>
        <p class="text-gray-600">Chào mừng bạn đến với hệ thống đánh giá AQ (Accountability Quotient)</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Dashboard Cards -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-user text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Thông tin cá nhân</h3>
                    <p class="text-gray-600">Xem và cập nhật thông tin</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-poll-h text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Khảo sát AQ</h3>
                    <p class="text-gray-600">Thực hiện đánh giá AQ</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Kết quả</h3>
                    <p class="text-gray-600">Xem báo cáo chi tiết</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Thao tác nhanh</h2>
        <div class="flex flex-wrap gap-4">
            <a href="/AQCoder/survey" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                <i class="fas fa-poll-h mr-2"></i>Bắt đầu khảo sát
            </a>
            <a href="/AQCoder/practices" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                <i class="fas fa-poll-h mr-2"></i>Bài tập rèn luyện
            </a>
            <a href="/AQCoder/logout" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Hoạt động gần đây</h2>
        <div class="space-y-3">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Đăng nhập thành công</p>
                    <p class="text-sm text-gray-600"><?= date('d/m/Y H:i') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
require 'partials/footer.php'; 
?>