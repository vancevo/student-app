<?php 
require 'partials/header.php'; 
?>

<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Chào mừng, <?= htmlspecialchars($fullname) ?> - LỚP: <?= htmlspecialchars($class) ?></h1>
        <p class="text-gray-600">Chào mừng bạn đến với hệ thống đánh giá AQ (Accountability Quotient)</p>
        
        <!-- User Stats -->
        <div class="mt-4 flex flex-wrap gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-star mr-2"></i>
                <span class="font-semibold"><?= $experience ?> XP</span>
            </div>
            <div class="bg-white border-2 border-gray-200 px-4 py-2 rounded-lg flex items-center" style="border-color: <?= $rankColor ?>;">
                <i class="fas fa-trophy mr-2" style="color: <?= $rankColor ?>;"></i>
                <span class="font-semibold" style="color: <?= $rankColor ?>;"><?= htmlspecialchars($rankName) ?></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Rank & Experience Card -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: <?= $rankColor ?>20;">
                    <i class="fas fa-trophy text-2xl" style="color: <?= $rankColor ?>;"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($rankName) ?></h3>
                    <p class="text-gray-600"><?= $experience ?> điểm kinh nghiệm</p>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <!-- <div class="bg-white rounded-lg shadow-lg p-6">
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
        </div> -->

        <div class="bg-white rounded-lg shadow-lg p-6 cursor-pointer hover:shadow-xl transition-shadow duration-200" id="results-card">
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
    <!-- <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Hoạt động gần đây</h2>
        <div class="space-y-3">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Đăng nhập thành công</p>
                    <p class="text-sm text-gray-600"><?= date('d/m/Y') ?></p>
                </div>
            </div>
        </div>
    </div> -->
</div>

<?php require 'partials/ResultModal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart data from PHP
    const surveyData = <?= $surveyResults ? json_encode([
        'control' => $surveyResults['control_score'],
        'ownership' => $surveyResults['ownership_score'],
        'reach' => $surveyResults['reach_score'],
        'endurance' => $surveyResults['endurance_score']
    ]) : 'null' ?>;
    
    const surveyHistory = <?= json_encode($surveyHistory) ?>;

    // Show results modal when clicking on results card
    document.getElementById('results-card').addEventListener('click', function() {
        document.getElementById('results-modal').classList.remove('hidden');
        
        // Create chart if survey data exists
        if (surveyData) {
            const ctx = document.getElementById('chart-radar').getContext('2d');
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Control', 'Ownership', 'Reach', 'Endurance'],
                    datasets: [{
                        label: 'AQ Scores',
                        data: [
                            surveyData.control,
                            surveyData.ownership,
                            surveyData.reach,
                            surveyData.endurance
                        ],
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 25,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });

    // Modal functionality
    function closeModal() {
        document.getElementById('results-modal').classList.add('hidden');
    }

    // Close modal when clicking close buttons
    document.getElementById('close-modal').addEventListener('click', closeModal);
    document.getElementById('close-modal-btn').addEventListener('click', closeModal);

    // Close modal when clicking outside
    document.getElementById('results-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('results-modal').classList.contains('hidden')) {
            closeModal();
        }
    });
</script>

<?php 
require 'partials/footer.php'; 
?>