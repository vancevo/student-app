<?php
// Test script để kiểm tra logic phân tích input

function isInputCompatible($code, $testInput) {
    // Nếu code có input().split() thì cần input có nhiều giá trị
    if (strpos($code, 'input().split()') !== false) {
        return strpos($testInput, ' ') !== false; // Input có dấu cách
    }
    
    // Nếu code chỉ có input() đơn thì cần input đơn
    if (preg_match('/input\([^)]*\)(?!\s*\.\s*split)/', $code)) {
        return strpos($testInput, ' ') === false; // Input không có dấu cách
    }
    
    return true; // Mặc định cho phép
}

function generateDefaultInput($code) {
    // Nếu code có input().split() thì tạo input có nhiều giá trị
    if (strpos($code, 'input().split()') !== false) {
        return '1 2'; // Input mặc định cho bài tính tổng
    }
    
    // Nếu code chỉ có input() đơn thì tạo input đơn
    if (preg_match('/input\([^)]*\)(?!\s*\.\s*split)/', $code)) {
        return '5'; // Input mặc định cho bài kiểm tra số chẵn/lẻ
    }
    
    return '1'; // Input mặc định chung
}

// Test cases
$code1 = 'a, b = map(int, input().split())\nprint(a + b)';
$code2 = 'so = int(input("Nhập một số nguyên: "))\nif so % 2 == 0:\n  print(f"Số {so} là số chẵn.")\nelse:\n  print(f"Số {so} là số lẻ.")';

$testInput1 = '1 2';
$testInput2 = '5';

echo "Test 1 - Code tính tổng:\n";
echo "Code: " . $code1 . "\n";
echo "Test input: " . $testInput1 . "\n";
echo "Compatible: " . (isInputCompatible($code1, $testInput1) ? 'YES' : 'NO') . "\n";
echo "Default input: " . generateDefaultInput($code1) . "\n\n";

echo "Test 2 - Code kiểm tra chẵn/lẻ:\n";
echo "Code: " . $code2 . "\n";
echo "Test input: " . $testInput1 . "\n";
echo "Compatible: " . (isInputCompatible($code2, $testInput1) ? 'YES' : 'NO') . "\n";
echo "Default input: " . generateDefaultInput($code2) . "\n\n";

echo "Test 3 - Code kiểm tra chẵn/lẻ với input đúng:\n";
echo "Code: " . $code2 . "\n";
echo "Test input: " . $testInput2 . "\n";
echo "Compatible: " . (isInputCompatible($code2, $testInput2) ? 'YES' : 'NO') . "\n";
echo "Default input: " . generateDefaultInput($code2) . "\n";
?>
