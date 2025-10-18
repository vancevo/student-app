<?php
class SurveyController {
    private $surveyModel;

    public function __construct(SurveyModel $surveyModel) {
        $this->surveyModel = $surveyModel; 
    }

    /**
     * [ACTION] Xử lý POST request API để lưu kết quả khảo sát (Tuyến đường /save-survey).
     */
    public function processSaveSurvey() {
        header('Content-Type: application/json'); 

        $data = json_decode(file_get_contents('php://input'), true);

        // 1. Xác thực cơ bản
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($data['userId'], $data['username'], $data['coreScores'])) {
            http_response_code(400); 
            echo json_encode(['success' => false, 'message' => 'Lỗi: Dữ liệu yêu cầu không hợp lệ hoặc thiếu.']);
            exit();
        }

        $userId = (int)$data['userId'];
        $username = $data['username'];
        $scores = $data['coreScores'];

        // 2. Xác thực điểm số
        foreach (['control', 'ownership', 'reach', 'endurance'] as $scoreName) {
            $score = $scores[$scoreName] ?? null;
            if (!is_numeric($score) || $score < 1 || $score > 5) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => "Lỗi xác thực: Điểm số '$scoreName' không hợp lệ."]);
                exit();
            }
        }

        // 3. Gọi Model Component
        try {
            if ($this->surveyModel->saveSurveyResults($userId, $username, $scores)) {
                echo json_encode(['success' => true, 'message' => 'Dữ liệu khảo sát đã được lưu thành công.']);
            } else {
                http_response_code(500); 
                echo json_encode(['success' => false, 'message' => 'Lỗi máy chủ khi lưu dữ liệu.']);
            }
        } catch (\PDOException $e) {
            error_log("Survey DB Error: " . $e->getMessage()); 
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi kết nối CSDL.']);
        }
        exit();
    }
}