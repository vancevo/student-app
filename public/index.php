<?php

// Bắt buộc khởi động session trước mọi output
session_start(); 

// --- 1. AUTOLOADING/REQUIRE CÁC COMPONENT ---
// Tạm thời require thủ công tất cả các Class đã tạo (Nếu bạn dùng Composer thì không cần bước này)
require '../app/Core/Database.php'; 
require '../app/Models/UserModel.php';
require '../app/Models/SurveyModel.php';
require '../app/Controllers/AuthController.php';
require '../app/Controllers/HomeController.php';
require '../app/Controllers/SurveyController.php';

// --- 2. ĐỊNH NGHĨA CÁC TUYẾN ĐƯỜNG (URL -> Controller::Method) ---
// Lấy URL yêu cầu
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); 
$routes = [
    ''            => ['AuthController', 'showLogin'],       // URL gốc (/)
    'login'       => ['AuthController', 'login'],          // POST Đăng nhập
    'register'    => ['AuthController', 'register'],       // POST/GET Đăng ký
    'logout'      => ['AuthController', 'logout'],         // Đăng xuất
    'home'        => ['HomeController', 'index'],          // Trang Chủ
    'save-survey' => ['SurveyController', 'processSaveSurvey'], // API Khảo sát (AJAX POST)
];

// --- 3. PHÂN PHỐI YÊU CẦU (DISPATCHING) ---
if (array_key_exists($uri, $routes)) {
    [$controllerClass, $method] = $routes[$uri]; 

    // Dependency Injection: Khởi tạo các Component phụ thuộc
    $pdo = Database::getInstance()->getConnection();
    $controller = null;
    
    if ($controllerClass === 'AuthController') {
        $userModel = new UserModel($pdo); // AuthController cần UserModel
        $controller = new AuthController($userModel);
    } elseif ($controllerClass === 'SurveyController') {
        $surveyModel = new SurveyModel($pdo); // SurveyController cần SurveyModel
        $controller = new SurveyController($surveyModel);
    } elseif ($controllerClass === 'HomeController') {
        $controller = new HomeController();
    }

    // Gọi phương thức (Action) tương ứng trong Controller
    if ($controller && method_exists($controller, $method)) {
        $controller->$method();
    } else {
        http_response_code(500);
        die("Lỗi server: Controller $controllerClass hoặc Method $method không tìm thấy.");
    }

} else {
    // 404 Not Found
    http_response_code(404);
    echo "404 Not Found.";
}