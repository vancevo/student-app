<?php
// Dữ liệu đã được truyền từ Controller
// $practices và $completionStatus đã có sẵn
// Load dummy practice data
require_once '../config/practices_data.php';

// Get all practice data from dummy data
$dummyPractices = getAllPracticeData();

// Use dummy data if no practices from database
if (empty($practices)) {
    $practices = $dummyPractices;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài tập rèn luyện - AQCoder</title>
    <link rel="stylesheet" href="/assets/css/style.css"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex min-h-screen font-sans antialiased">
    <?php require 'partials/sidebar.php'; ?>
    
    <div class="content flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Bài tập rèn luyện</h1>
                <div class="text-sm text-gray-600">
                    Xin chào, <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                </div>
            </div>
            
            <?php if (empty($practices)): ?>
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="text-gray-500 text-lg">
                        <i class="fas fa-code text-4xl mb-4 block"></i>
                        Chưa có bài tập nào được tạo.
                    </div>
                </div>
            <?php else: ?>
                <!-- Practice Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- <div class="bg-white rounded-lg shadow-lg p-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Hoàn thành</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    <?php echo count(array_filter($completionStatus, fn($status) => $status === 'excellent')); ?>
                                </p>
                            </div>
                        </div>
                    </div> -->
                    <div class="bg-white rounded-lg shadow-lg p-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Easy</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    <?php echo count(array_filter($practices, fn($p) => $p['difficulty'] === 'easy')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-lg p-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Medium</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    <?php echo count(array_filter($practices, fn($p) => $p['difficulty'] === 'medium')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-lg p-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Hard</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    <?php echo count(array_filter($practices, fn($p) => $p['difficulty'] === 'hard')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Difficulty Filter -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <h3 class="text-lg font-semibold text-gray-800 mr-4">Lọc theo độ khó:</h3>
                        <div class="flex flex-wrap gap-2">
                            <button id="filter-all" class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 bg-blue-500 text-white hover:bg-blue-600">
                                Tất cả
                            </button>
                            <button id="filter-easy" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 bg-gray-100 text-gray-700 hover:bg-green-100 hover:text-green-800" data-difficulty="easy">
                                <i class="fas fa-check-circle mr-1"></i>
                                Easy
                            </button>
                            <button id="filter-medium" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 bg-gray-100 text-gray-700 hover:bg-yellow-100 hover:text-yellow-800" data-difficulty="medium">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Medium
                            </button>
                            <button id="filter-hard" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 bg-gray-100 text-gray-700 hover:bg-red-100 hover:text-red-800" data-difficulty="hard">
                                <i class="fas fa-fire mr-1"></i>
                                Hard
                            </button>
                        </div>
                        <div class="ml-auto text-sm text-gray-500">
                            <span id="filter-count"><?php echo count($practices); ?></span> bài tập
                        </div>
                    </div>
                </div>
                
                <div id="practices-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($practices as $practice): ?>
                        <div class="practice-card bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300" data-difficulty="<?php echo $practice['difficulty']; ?>">
                            <div class="p-6">
                                <div class="flex flex-col justify-between items-start mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800 line-clamp-2">
                                        <?php echo htmlspecialchars($practice['title']); ?>
                                    </h3>
                                    <div class="flex gap-2 items-end space-y-1">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            <?php 
                                            switch($practice['difficulty']) {
                                                case 'easy': echo 'bg-green-100 text-green-800'; break;
                                                case 'medium': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'hard': echo 'bg-red-100 text-red-800'; break;
                                                default: echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?php echo ucfirst($practice['difficulty']); ?>
                                        </span>
                                        
                                        <!-- Trạng thái Hoàn thành -->
                                        <?php 
                                        $status = $completionStatus[$practice['id']] ?? 'not_attempted';
                                        $statusConfig = [
                                            'excellent' => ['text' => 'Hoàn thành', 'class' => 'bg-green-100 text-green-800'],
                                            'good' => ['text' => 'Tốt', 'class' => 'bg-blue-100 text-blue-800'],
                                            'wrong_answer' => ['text' => 'Sai', 'class' => 'bg-red-100 text-red-800'],
                                            'time_limit' => ['text' => 'Timeout', 'class' => 'bg-orange-100 text-orange-800'],
                                            'error' => ['text' => 'Lỗi', 'class' => 'bg-red-100 text-red-800'],
                                            'not_attempted' => ['text' => 'Chưa làm', 'class' => 'bg-gray-100 text-gray-600']
                                        ];
                                        $config = $statusConfig[$status] ?? $statusConfig['not_attempted'];
                                        ?>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $config['class']; ?>">
                                            <?php echo $config['text']; ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    <?php echo htmlspecialchars($practice['description']); ?>
                                </p>
                                
                                <!-- Additional Info -->
                                <div class="mb-4">
                                    <div class="flex items-center text-xs text-gray-500 mb-2">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        ID: <?php echo $practice['id']; ?>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <?php echo isset($practice['created_at']) ? date('d/m/Y', strtotime($practice['created_at'])) : date('d/m/Y'); ?>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            Python
                                        </span>
                                        <!-- <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                            Java
                                        </span>
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                            C++
                                        </span> -->
                                    </div>
                                    <?php 
                                    $buttonText = 'Làm bài';
                                    $buttonClass = 'bg-blue-500 hover:bg-blue-600 text-white';
                                    
                                    if ($status === 'excellent') {
                                        $buttonText = 'Xem lại';
                                        $buttonClass = 'bg-green-500 hover:bg-green-600 text-white';
                                    } elseif ($status === 'good') {
                                        $buttonText = 'Cải thiện';
                                        $buttonClass = 'bg-blue-500 hover:bg-blue-600 text-white';
                                    } elseif (in_array($status, ['wrong_answer', 'time_limit', 'error'])) {
                                        $buttonText = 'Thử lại';
                                        $buttonClass = 'bg-orange-500 hover:bg-orange-600 text-white';
                                    }
                                    ?>
                                    <a href="/AQCoder/practice/<?php echo $practice['id']; ?>" 
                                       class="<?php echo $buttonClass; ?> px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <?php echo $buttonText; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .filter-btn.active {
            transform: scale(1.05);
        }
        
        .practice-card {
            transition: all 0.3s ease;
        }
        
        .practice-card.hidden {
            display: none;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const practiceCards = document.querySelectorAll('.practice-card');
            const filterCount = document.getElementById('filter-count');
            
            // Initialize filter count
            updateFilterCount();
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.classList.remove('bg-blue-500', 'text-white');
                        btn.classList.add('bg-gray-100', 'text-gray-700');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    this.classList.remove('bg-gray-100', 'text-gray-700');
                    this.classList.add('bg-blue-500', 'text-white');
                    
                    // Get filter value
                    const filterValue = this.getAttribute('data-difficulty');
                    
                    // Filter practice cards
                    filterPracticeCards(filterValue);
                });
            });
            
            function filterPracticeCards(difficulty) {
                let visibleCount = 0;
                
                practiceCards.forEach(card => {
                    if (difficulty === null || card.getAttribute('data-difficulty') === difficulty) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });
                
                updateFilterCount(visibleCount);
            }
            
            function updateFilterCount(count = null) {
                if (count === null) {
                    count = practiceCards.length;
                }
                filterCount.textContent = count;
            }
        });
    </script>
</body>
</html>