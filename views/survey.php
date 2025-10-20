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
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">
                            <?php echo htmlspecialchars($categoryData['title']); ?>
                        </h3>
                        
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

            <!-- Results Section -->
            <div id="results" class="bg-white rounded-lg shadow-lg p-6 mt-6 hidden">
                <h2 class="text-xl font-semibold mb-4">Kết quả khảo sát</h2>
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
                <canvas id="chart-radar" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <script>
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
            
            // Show results section
            document.getElementById('results').classList.remove('hidden');
            
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
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 5
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
    </script>
</body>
</html>
