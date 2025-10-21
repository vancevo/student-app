<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khảo sát AQ - AQCoder</title>
    <link rel="stylesheet" href="/assets/css/style.css"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex min-h-screen font-sans antialiased">
   <?php require 'partials/sidebar.php'; ?>
    
    <div class="content flex-1 p-8 overflow-y-auto">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Khảo sát AQ (Accountability Quotient)</h1>
            
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Hướng dẫn</h2>
                <p class="text-gray-600 mb-4">
                    Vui lòng đánh giá mức độ đồng ý của bạn với các câu hỏi dưới đây theo thang điểm từ 1 đến 5:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li><strong>1</strong> - Hoàn toàn không đồng ý</li>
                    <li><strong>2</strong> - Không đồng ý</li>
                    <li><strong>3</strong> - Trung lập</li>
                    <li><strong>4</strong> - Đồng ý</li>
                    <li><strong>5</strong> - Hoàn toàn đồng ý</li>
                </ul>
            </div>

            <form id="survey-form" class="bg-white rounded-lg shadow-lg p-6">
                <div class="space-y-6">
                    <?php 
                    $questionNumber = 1;
                    foreach ($dummyData as $categoryKey => $categoryData): 
                        $isLastCategory = ($categoryKey === array_key_last($dummyData));
                    ?>
                        <!-- <?php echo ucfirst($categoryKey); ?> Questions -->
                        <div class="mb-6 <?php echo $isLastCategory ? '' : 'border-b pb-4'; ?>">
                            <?php foreach ($categoryData['questions'] as $questionIndex => $questionData): ?>
                                <div class="mb-4 <?php echo $questionIndex < count($categoryData['questions']) - 1 ? 'border-b border-gray-200 pb-4' : ''; ?>">
                                    <label class="block text-gray-700 text-lg font-semibold mb-2">
                                        <?php echo $questionNumber; ?>. <?php echo htmlspecialchars($questionData['question']); ?>
                                    </label>
                                    <div class="space-y-2">
                                        <?php foreach ($questionData['options'] as $optionIndex => $option): ?>
                                        <label class="block">
                                            <input type="radio" name="<?php echo $questionData['id']; ?>" value="<?php echo $optionIndex + 1; ?>" class="mr-2"> 
                                            <?php echo htmlspecialchars($option); ?>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php 
                            $questionNumber++;
                            endforeach; 
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 flex justify-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200">
                        Gửi Khảo sát
                    </button>
                </div>
            </form>

            <!-- Results Modal -->
            <div id="results-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center p-6 border-b">
                        <h2 class="text-2xl font-semibold text-gray-800">Kết quả khảo sát AQ</h2>
                        <button id="close-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Modal Content -->
                    <div class="p-6">
                        <!-- Score Cards -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600" id="control-score">0</div>
                                <div class="text-sm text-gray-600">Control</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600" id="ownership-score">0</div>
                                <div class="text-sm text-gray-600">Ownership</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600" id="reach-score">0</div>
                                <div class="text-sm text-gray-600">Reach</div>
                            </div>
                            <div class="text-center p-4 bg-red-50 rounded-lg">
                                <div class="text-2xl font-bold text-red-600" id="endurance-score">0</div>
                                <div class="text-sm text-gray-600">Endurance</div>
                            </div>
                        </div>
                        
                        <!-- Chart -->
                        <div class="mb-8 flex justify-center">
                            <div class="w-80 h-80">
                                <canvas id="chart-radar"></canvas>
                            </div>
                        </div>

                        <!-- Progress Results Section -->
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">Tiến trình phát triển</h3>
                            <div class="space-y-4">
                                <!-- Control Progress -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium">Control (Kiểm soát)</span>
                                        <span class="text-sm text-gray-600" id="control-progress-text">0/5</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                        <div id="control-progress-bar" class="h-3 rounded-full transition-all duration-500" style="width: 0%; background-color: #ef4444;"></div>
                                    </div>
                                    <p class="text-sm text-gray-600" id="control-message">Bạn có thể cải thiện ở khía cạnh này. Hãy bắt đầu với các thử thách nhỏ để nâng cao sự tự tin.</p>
                                </div>

                                <!-- Ownership Progress -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium">Ownership (Trách nhiệm)</span>
                                        <span class="text-sm text-gray-600" id="ownership-progress-text">0/5</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                        <div id="ownership-progress-bar" class="h-3 rounded-full transition-all duration-500" style="width: 0%; background-color: #ef4444;"></div>
                                    </div>
                                    <p class="text-sm text-gray-600" id="ownership-message">Bạn có thể cải thiện ở khía cạnh này. Hãy bắt đầu với các thử thách nhỏ để nâng cao sự tự tin.</p>
                                </div>

                                <!-- Reach Progress -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium">Reach (Phạm vi)</span>
                                        <span class="text-sm text-gray-600" id="reach-progress-text">0/5</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                        <div id="reach-progress-bar" class="h-3 rounded-full transition-all duration-500" style="width: 0%; background-color: #ef4444;"></div>
                                    </div>
                                    <p class="text-sm text-gray-600" id="reach-message">Bạn có thể cải thiện ở khía cạnh này. Hãy bắt đầu với các thử thách nhỏ để nâng cao sự tự tin.</p>
                                </div>

                                <!-- Endurance Progress -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium">Endurance (Sức chịu đựng)</span>
                                        <span class="text-sm text-gray-600" id="endurance-progress-text">0/5</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                        <div id="endurance-progress-bar" class="h-3 rounded-full transition-all duration-500" style="width: 0%; background-color: #ef4444;"></div>
                                    </div>
                                    <p class="text-sm text-gray-600" id="endurance-message">Bạn có thể cải thiện ở khía cạnh này. Hãy bắt đầu với các thử thách nhỏ để nâng cao sự tự tin.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end p-6 border-t bg-gray-50">
                        <button id="close-modal-btn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to update progress bars and motivational messages
        function updateProgressBar(category, score) {
            const progressBar = document.getElementById(`${category}-progress-bar`);
            const progressText = document.getElementById(`${category}-progress-text`);
            const messageElement = document.getElementById(`${category}-message`);
            
            // Calculate percentage (score out of 5)
            const percentage = (score / 5) * 100;
            const roundedScore = Math.round(score);
            
            // Update progress text
            progressText.textContent = `${roundedScore}/5`;
            
            // Update progress bar width and color
            progressBar.style.width = `${percentage}%`;
            
            // Set color based on score
            if (score <= 2) {
                progressBar.style.backgroundColor = '#ef4444'; // Red
            } else if (score <= 3) {
                progressBar.style.backgroundColor = '#f59e0b'; // Orange
            } else if (score <= 4) {
                progressBar.style.backgroundColor = '#3b82f6'; // Blue
            } else {
                progressBar.style.backgroundColor = '#10b981'; // Green
            }
            
            // Set motivational message based on score
            let message = '';
            if (score <= 2) {
                message = 'Bạn có thể cải thiện ở khía cạnh này. Hãy bắt đầu với các thử thách nhỏ để nâng cao sự tự tin.';
            } else if (score <= 3) {
                message = 'Điểm số của bạn khá tốt. Hãy tiếp tục rèn luyện để đạt đến mức xuất sắc.';
            } else if (score <= 4) {
                message = 'Bạn đang làm rất tốt! Hãy duy trì và phát triển thêm kỹ năng này.';
            } else {
                message = 'Xuất sắc! Bạn đã đạt được mức độ cao trong khía cạnh này.';
            }
            
            messageElement.textContent = message;
        }

        document.getElementById('survey-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Tự động detect tất cả các câu hỏi từ form
            const allAnswers = {};
            const categoryScores = {};
            
            // Thu thập tất cả câu trả lời từ form
            for (let [name, value] of formData.entries()) {
                if (value) {
                    allAnswers[name] = parseInt(value);
                }
            }
            
            // Kiểm tra xem tất cả câu hỏi đã được trả lời chưa
            const allRadioButtons = document.querySelectorAll('input[type="radio"]');
            const answeredQuestions = new Set();
            
            allRadioButtons.forEach(radio => {
                if (radio.checked) {
                    answeredQuestions.add(radio.name);
                }
            });
            
            // Đếm tổng số câu hỏi
            const totalQuestions = new Set();
            allRadioButtons.forEach(radio => {
                totalQuestions.add(radio.name);
            });
            
            if (answeredQuestions.size !== totalQuestions.size) {
                alert('Vui lòng trả lời tất cả các câu hỏi trước khi gửi khảo sát!');
                return;
            }
            
            // Tự động tính điểm cho mỗi category
            const categoryGroups = {};
            
            // Nhóm các câu hỏi theo category
            Object.keys(allAnswers).forEach(questionId => {
                const category = questionId.split('_')[0]; // Lấy phần đầu (control, ownership, etc.)
                if (!categoryGroups[category]) {
                    categoryGroups[category] = [];
                }
                categoryGroups[category].push(allAnswers[questionId]);
            });
            
            // Tính điểm trung bình cho mỗi category
            Object.keys(categoryGroups).forEach(category => {
                const scores = categoryGroups[category];
                categoryScores[category] = scores.reduce((sum, score) => sum + score, 0) / scores.length;
            });
            
            // Display results
            document.getElementById('control-score').textContent = (categoryScores.control || 0).toFixed(1);
            document.getElementById('ownership-score').textContent = (categoryScores.ownership || 0).toFixed(1);
            document.getElementById('reach-score').textContent = (categoryScores.reach || 0).toFixed(1);
            document.getElementById('endurance-score').textContent = (categoryScores.endurance || 0).toFixed(1);
            
            // Update progress bars and messages
            updateProgressBar('control', categoryScores.control || 0);
            updateProgressBar('ownership', categoryScores.ownership || 0);
            updateProgressBar('reach', categoryScores.reach || 0);
            updateProgressBar('endurance', categoryScores.endurance || 0);
            
            // Show results modal
            document.getElementById('results-modal').classList.remove('hidden');
            
            // Create chart
            const ctx = document.getElementById('chart-radar').getContext('2d');
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Control', 'Ownership', 'Reach', 'Endurance'],
                    datasets: [{
                        label: 'AQ Scores',
                        data: [
                            categoryScores.control || 0,
                            categoryScores.ownership || 0,
                            categoryScores.reach || 0,
                            categoryScores.endurance || 0
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
                            max: 5,
                            ticks: {
                                stepSize: 1
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
            
            // Gửi dữ liệu lên server
            try {
                const response = await fetch('/AQCoder/save-survey', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        userId: <?php echo $_SESSION['user_id'] ?? 0; ?>,
                        username: '<?php echo $_SESSION['username'] ?? 'unknown'; ?>',
                        coreScores: categoryScores,
                        detailedAnswers: allAnswers
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Khảo sát đã được lưu thành công!');
                } else {
                    console.error('Lỗi khi lưu khảo sát:', result.message);
                    alert('Có lỗi xảy ra khi lưu khảo sát: ' + result.message);
                }
            } catch (error) {
                console.error('Lỗi kết nối:', error);
                alert('Có lỗi xảy ra khi kết nối đến server!');
            }
        });

        // Modal functionality
        function closeModal() {
            document.getElementById('results-modal').classList.add('hidden');
            window.location.reload();
        }

        // Close modal when clicking close buttons
        document.getElementById('close-modal').addEventListener('click', closeModal);
        document.getElementById('close-modal-btn').addEventListener('click', closeModal);

        // Close modal when clicking outside
        document.getElementById('results-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
                window.location.reload();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('results-modal').classList.contains('hidden')) {
                closeModal();
                window.location.reload();
            }
        });
    </script>
</body>
</html>
