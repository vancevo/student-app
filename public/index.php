<?php

// Bắt buộc khởi động session trước mọi output
session_start(); 

// --- 1. AUTOLOADING/REQUIRE CÁC COMPONENT ---
// Tạm thời require thủ công tất cả các Class đã tạo (Nếu bạn dùng Composer thì không cần bước này)
require '../app/Core/Database.php'; 
require '../app/Core/DatabaseSeeder.php';
require '../app/Models/UserModel.php';
require '../app/Models/SurveyModel.php';
require '../app/Models/PracticeModel.php';
require '../app/Controllers/AuthController.php';
require '../app/Controllers/HomeController.php';
require '../app/Controllers/SurveyController.php';
require '../app/Controllers/DatabaseController.php';
require '../app/Controllers/PracticesController.php';

// --- 2. ĐỊNH NGHĨA CÁC TUYẾN ĐƯỜNG (URL -> Controller::Method) ---
// Lấy URL yêu cầu
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); 

// Loại bỏ prefix AQCoder/public nếu có
if (strpos($uri, 'AQCoder/public/') === 0) {
    $uri = substr($uri, 15); // Bỏ 'AQCoder/public/'
} elseif (strpos($uri, 'AQCoder/public') === 0) {
    $uri = substr($uri, 14); // Bỏ 'AQCoder/public'
} elseif (strpos($uri, 'AQCoder/') === 0) {
    $uri = substr($uri, 8); // Bỏ 'AQCoder/'
}

// Xử lý trường hợp truy cập trực tiếp vào index.php
if ($uri === 'index.php') {
    $uri = ''; // Chuyển về route gốc
}

$routes = [
    ''            => ['AuthController', 'showLogin'],       // URL gốc (/)
    'login'       => ['AuthController', 'login'],          // POST Đăng nhập
    'register'    => ['AuthController', 'register'],       // POST/GET Đăng ký
    'logout'      => ['AuthController', 'logout'],         // Đăng xuất
    'home'        => ['HomeController', 'index'],          // Trang Chủ
    'survey'      => ['SurveyController', 'index'],        // Trang Khảo sát AQ
    'practices'   => ['PracticesController', 'index'],    // Trang Bài tập rèn luyện
    'save-survey' => ['SurveyController', 'processSaveSurvey'], // API Khảo sát (AJAX POST)
    'run-code'    => ['PracticesController', 'runCode'],  // API Chạy thử Code (AJAX POST)
    'submit-code' => ['PracticesController', 'submitCode'], // API Submit Code (AJAX POST)
    'database'    => ['DatabaseController', 'index'],     // Quản lý Database
];

// --- 3. PHÂN PHỐI YÊU CẦU (DISPATCHING) ---
// Xử lý dynamic routes trước
if (preg_match('/^practice\/(\d+)$/', $uri, $matches)) {
    $practiceId = (int)$matches[1];
    $controllerClass = 'PracticesController';
    $method = 'showPractice';
} elseif (array_key_exists($uri, $routes)) {
    [$controllerClass, $method] = $routes[$uri]; 
} else {
    // 404 Not Found
    http_response_code(404);
    echo "404 Not Found. URI: " . $uri;
    return;
}

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
} elseif ($controllerClass === 'DatabaseController') {
    $seeder = new DatabaseSeeder($pdo); // DatabaseController cần DatabaseSeeder
    $controller = new DatabaseController($seeder);
    
    // Xử lý các action đặc biệt cho DatabaseController
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        if ($action === 'seed') {
            $controller->seed();
            return;
        } elseif ($action === 'reset') {
            $controller->reset();
            return;
        } elseif ($action === 'seed-existing') {
            $controller->seedExisting();
            return;
        }
    }
} elseif ($controllerClass === 'PracticesController') {
    $practiceModel = new PracticeModel($pdo); // PracticesController cần PracticeModel
    $controller = new PracticesController($practiceModel);
}

// Gọi phương thức (Action) tương ứng trong Controller
if ($controller && method_exists($controller, $method)) {
    // Xử lý dynamic route cho practice detail
    if (isset($practiceId)) {
        $controller->$method($practiceId);
    } else {
        $controller->$method();
    }
} else {
    http_response_code(500);
    die("Lỗi server: Controller $controllerClass hoặc Method $method không tìm thấy.");
}