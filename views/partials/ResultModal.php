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
            <?php if ($surveyResults): ?>
                <!-- Survey Info -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-800">Kết quả khảo sát mới nhất</h3>
                            <p class="text-sm text-blue-600">Thực hiện lúc: <?= date('d/m/Y H:i', strtotime($surveyResults['created_at'])) ?></p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-800"><?= $surveyResults['total_score'] ?></div>
                            <div class="text-sm text-blue-600">Tổng điểm</div>
                        </div>
                    </div>
                </div>

                <!-- Score Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600"><?= number_format($surveyResults['control_score'], 1) ?></div>
                        <div class="text-sm text-gray-600">Control</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600"><?= number_format($surveyResults['ownership_score'], 1) ?></div>
                        <div class="text-sm text-gray-600">Ownership</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600"><?= number_format($surveyResults['reach_score'], 1) ?></div>
                        <div class="text-sm text-gray-600">Reach</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600"><?= number_format($surveyResults['endurance_score'], 1) ?></div>
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
                                <span class="text-sm text-gray-600"><?= number_format($surveyResults['control_score'], 1) ?>/25</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                <div class="h-3 rounded-full transition-all duration-500" style="width: <?= ($surveyResults['control_score'] / 25) * 100 ?>%; background-color: <?= $surveyResults['control_score'] <= 10 ? '#ef4444' : ($surveyResults['control_score'] <= 15 ? '#f59e0b' : ($surveyResults['control_score'] <= 20 ? '#3b82f6' : '#10b981')) ?>;"></div>
                            </div>
                            <p class="text-sm text-gray-600">
                                <?php 
                                if ($surveyResults['control_score'] <= 10) {
                                    echo 'Bạn có thể cải thiện ở khía cạnh này. Hãy bắt đầu với các thử thách nhỏ để nâng cao sự tự tin.';
                                } elseif ($surveyResults['control_score'] <= 15) {
                                    echo 'Điểm số của bạn khá tốt. Hãy tiếp tục rèn luyện để đạt đến mức xuất sắc.';
                                } elseif ($surveyResults['control_score'] <= 20) {
                                    echo 'Bạn đang làm rất tốt! Hãy duy trì và phát triển thêm kỹ năng này.';
                                } else {
                                    echo 'Xuất sắc! Bạn đã đạt được mức độ cao trong khía cạnh này.';
                                }
                                ?>
                            </p>
                        </div>

                        <!-- Ownership Progress -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium">Ownership (Trách nhiệm)</span>
                                <span class="text-sm text-gray-600"><?= number_format($surveyResults['ownership_score'], 1) ?>/25</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                <div class="h-3 rounded-full transition-all duration-500" style="width: <?= ($surveyResults['ownership_score'] / 25) * 100 ?>%; background-color: <?= $surveyResults['ownership_score'] <= 10 ? '#ef4444' : ($surveyResults['ownership_score'] <= 15 ? '#f59e0b' : ($surveyResults['ownership_score'] <= 20 ? '#3b82f6' : '#10b981')) ?>;"></div>
                            </div>
                            <p class="text-sm text-gray-600">
                                <?php 
                                if ($surveyResults['ownership_score'] <= 10) {
                                    echo 'Bạn có thể cải thiện ở khía cạnh này. Hãy bắt đầu với các thử thách nhỏ để nâng cao sự tự tin.';
                                } elseif ($surveyResults['ownership_score'] <= 15) {
                                    echo 'Điểm số của bạn khá tốt. Hãy tiếp tục rèn luyện để đạt đến mức xuất sắc.';
                                } elseif ($surveyResults['ownership_score'] <= 20) {
                                    echo 'Bạn đang làm rất tốt! Hãy duy trì và phát triển thêm kỹ năng này.';
                                } else {
                                    echo 'Xuất sắc! Bạn đã đạt được mức độ cao trong khía cạnh này.';
                                }
                                ?>
                            </p>
                        </div>

                        <!-- Reach Progress -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium">Reach (Phạm vi)</span>
                                <span class="text-sm text-gray-600"><?= number_format($surveyResults['reach_score'], 1) ?>/25</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                <div class="h-3 rounded-full transition-all duration-500" style="width: <?= ($surveyResults['reach_score'] / 25) * 100 ?>%; background-color: <?= $surveyResults['reach_score'] <= 10 ? '#ef4444' : ($surveyResults['reach_score'] <= 15 ? '#f59e0b' : ($surveyResults['reach_score'] <= 20 ? '#3b82f6' : '#10b981')) ?>;"></div>
                            </div>
                            <p class="text-sm text-gray-600">
                                <?php 
                                if ($surveyResults['reach_score'] <= 10) {
                                    echo 'Bạn có thể cải thiện ở khía cạnh này. Hãy bắt đầu với các thử thách nhỏ để nâng cao sự tự tin.';
                                } elseif ($surveyResults['reach_score'] <= 15) {
                                    echo 'Điểm số của bạn khá tốt. Hãy tiếp tục rèn luyện để đạt đến mức xuất sắc.';
                                } elseif ($surveyResults['reach_score'] <= 20) {
                                    echo 'Bạn đang làm rất tốt! Hãy duy trì và phát triển thêm kỹ năng này.';
                                } else {
                                    echo 'Xuất sắc! Bạn đã đạt được mức độ cao trong khía cạnh này.';
                                }
                                ?>
                            </p>
                        </div>

                        <!-- Endurance Progress -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium">Endurance (Sức chịu đựng)</span>
                                <span class="text-sm text-gray-600"><?= number_format($surveyResults['endurance_score'], 1) ?>/25</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                <div class="h-3 rounded-full transition-all duration-500" style="width: <?= ($surveyResults['endurance_score'] / 25) * 100 ?>%; background-color: <?= $surveyResults['endurance_score'] <= 10 ? '#ef4444' : ($surveyResults['endurance_score'] <= 15 ? '#f59e0b' : ($surveyResults['endurance_score'] <= 20 ? '#3b82f6' : '#10b981')) ?>;"></div>
                            </div>
                            <p class="text-sm text-gray-600">
                                <?php 
                                if ($surveyResults['endurance_score'] <= 10) {
                                    echo 'Bạn có thể cải thiện ở khía cạnh này. Hãy bắt đầu với các thử thách nhỏ để nâng cao sự tự tin.';
                                } elseif ($surveyResults['endurance_score'] <= 15) {
                                    echo 'Điểm số của bạn khá tốt. Hãy tiếp tục rèn luyện để đạt đến mức xuất sắc.';
                                } elseif ($surveyResults['endurance_score'] <= 20) {
                                    echo 'Bạn đang làm rất tốt! Hãy duy trì và phát triển thêm kỹ năng này.';
                                } else {
                                    echo 'Xuất sắc! Bạn đã đạt được mức độ cao trong khía cạnh này.';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Survey History Section -->
                <?php if (count($surveyHistory) > 1): ?>
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Lịch sử khảo sát</h3>
                    <div class="space-y-3">
                        <?php foreach ($surveyHistory as $index => $history): ?>
                        <div class="bg-gray-50 p-4 rounded-lg <?= $index === 0 ? 'border-2 border-blue-200' : '' ?>">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-800">
                                        <?= date('d/m/Y H:i', strtotime($history['created_at'])) ?>
                                    </span>
                                    <?php if ($index === 0): ?>
                                    <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Mới nhất</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-gray-800"><?= $history['total_score'] ?></span>
                                    <span class="text-sm text-gray-600">điểm</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-4 gap-2 text-sm">
                                <div class="text-center">
                                    <div class="font-semibold text-blue-600"><?= number_format($history['control_score'], 1) ?></div>
                                    <div class="text-gray-500">Control</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-green-600"><?= number_format($history['ownership_score'], 1) ?></div>
                                    <div class="text-gray-500">Ownership</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-purple-600"><?= number_format($history['reach_score'], 1) ?></div>
                                    <div class="text-gray-500">Reach</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-red-600"><?= number_format($history['endurance_score'], 1) ?></div>
                                    <div class="text-gray-500">Endurance</div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Chưa có kết quả khảo sát</h3>
                    <p class="text-gray-500 mb-6">Bạn chưa thực hiện khảo sát AQ. Hãy bắt đầu khảo sát để xem kết quả!</p>
                    <a href="/AQCoder/survey" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                        <i class="fas fa-poll-h mr-2"></i>Bắt đầu khảo sát
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex justify-end p-6 border-t bg-gray-50">
            <button id="close-modal-btn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                Đóng
            </button>
        </div>
    </div>
</div>