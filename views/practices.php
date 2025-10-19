<?php
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
    <script src="https://cdn.tailwindcss.com"></script>
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($practices as $practice): ?>
                        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800 line-clamp-2">
                                        <?php echo htmlspecialchars($practice['title']); ?>
                                    </h3>
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
                                    <?php if (isset($practice['created_at'])): ?>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <?php echo date('d/m/Y', strtotime($practice['created_at'])); ?>
                                    </div>
                                    <?php endif; ?>
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
                                    <a href="/AQCoder/practice/<?php echo $practice['id']; ?>" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        Làm bài
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
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</body>
</html>