<?php
class SurveyController {
    private $surveyModel;

    public function __construct(SurveyModel $surveyModel) {
        $this->surveyModel = $surveyModel; 
    }

    /**
     * [ACTION] Hiển thị trang khảo sát AQ (GET /survey)
     */
    public function index() {
        // session_start() đã được gọi trong index.php
        if (!isset($_SESSION['user_id'])) {
            header('Location: /AQCoder/login'); 
            exit;
        }

        // Load dummyData từ file config
        $dummyData = require '../config/survey_data.php';

        // Tải View Component survey.php
        require '../views/survey.php'; 
    }

    /**
     * [ACTION] Xử lý POST request API để lưu kết quả khảo sát (Tuyến đường /save-survey).
     */
    public function processSaveSurvey() {
        header('Content-Type: application/json'); 

        $data = json_decode(file_get_contents('php://input'), true);
        // Log để debug - xem toàn bộ dữ liệu đầu vào
        // 1. Xác thực cơ bản
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($data['coreScores'])) {
            http_response_code(400); 
            echo json_encode(['success' => false, 'message' => 'Lỗi: Dữ liệu yêu cầu không hợp lệ hoặc thiếu.']);
            exit();
        }

        // Luôn lấy thông tin người dùng từ session để tránh sai lệch/thiếu user_id từ client
        if (!isset($_SESSION['user_id'], $_SESSION['username'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để lưu khảo sát.']);
            exit();
        }

        $userId = (int)$_SESSION['user_id'];
        $username = (string)$_SESSION['username'];
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

        // Làm tròn điểm về số nguyên vì CSDL đang lưu INT
        $scores = [
            'control' => (int)round((float)$scores['control']),
            'ownership' => (int)round((float)$scores['ownership']),
            'reach' => (int)round((float)$scores['reach']),
            'endurance' => (int)round((float)$scores['endurance']),
        ];

        // 3. Gọi Model Component
        try {
            if ($this->surveyModel->saveSurveyResults($userId, $username, $scores)) {
                echo json_encode(['success' => true, 'message' => 'Dữ liệu khảo sát đã được lưu thành công.']);
            } else {
                http_response_code(500); 
                echo json_encode(['success' => false, 'message' => 'Lỗi máy chủ khi lưu dữ liệu.']);
            }
        } catch (\PDOException $e) {
            // Ghi log chi tiết để dễ debug (ví dụ: lỗi khóa ngoại khi user_id không tồn tại)
            error_log("Survey DB Error: " . $e->getMessage()); 
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi kết nối CSDL.']);
        }
        exit();
    }
}