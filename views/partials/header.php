<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username = $_SESSION['username'] ?? 'Khách'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Trang Chủ'; ?> | AQ Coder</title> 
    <link rel="stylesheet" href="/assets/css/style.css"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex min-h-screen font-sans antialiased">
    <div class="sidebar bg-white text-gray-800 w-64 p-4 shadow-lg flex flex-col justify-between">
        <div class="flex flex-col">
            <div class="text-3xl font-bold mb-2 flex justify-center text-blue-500">AQ Coder</div>
            <div class="logout-btn-container">
                 <a href="/logout" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors duration-200 shadow-md flex items-center justify-center">
                     <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                 </a>
            </div>
            <a href="/home" class="sidebar-link active flex items-center py-3 px-4 rounded-lg text-lg text-gray-800 hover:bg-gray-200 transition-colors duration-200">
                <i class="fas fa-home mr-3 text-xl"></i> Trang chủ
            </a>
            <a href="/survey" class="sidebar-link flex items-center py-3 px-4 rounded-lg text-lg text-gray-800 hover:bg-gray-200 transition-colors duration-200 mt-2">
                <i class="fas fa-poll-h mr-3 text-xl"></i> Khảo sát AQ
            </a>
            </div>
        </div>
    <div class="content flex-1 p-8 overflow-y-auto">