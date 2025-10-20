<?php

/**
 * Dummy data cho practices
 * File này CHỈ chứa dữ liệu, không chứa hàm logic.
 * Logic để truy vấn dữ liệu này đã được chuyển vào lớp PracticeModel.
 */

// Dữ liệu cơ bản của các bài tập (dùng cho trang danh sách chung)
$practice_data = [
    [
        'id' => 1, 
        'title' => 'Tính tổng hai số A và B', 
        'difficulty' => 'easy',
        'description' => 'Bài tập cơ bản về phép cộng hai số nguyên. Phù hợp cho người mới bắt đầu học lập trình.',
        'created_at' => '2024-01-15 10:30:00'
    ],
    [
        'id' => 2, 
        'title' => 'Tìm số lớn nhất trong mảng', 
        'difficulty' => 'easy',
        'description' => 'Tìm giá trị lớn nhất trong một mảng số nguyên. Bài tập về thuật toán tìm kiếm cơ bản.',
        'created_at' => '2024-01-16 14:20:00'
    ],
    [
        'id' => 3, 
        'title' => 'Kiểm tra số nguyên tố', 
        'difficulty' => 'medium',
        'description' => 'Xác định một số có phải là số nguyên tố hay không. Bài tập về thuật toán kiểm tra số nguyên tố.',
        'created_at' => '2024-01-17 09:15:00'
    ],
    [
        'id' => 4, 
        'title' => 'Sắp xếp mảng tăng dần', 
        'difficulty' => 'medium',
        'description' => 'Sắp xếp các phần tử trong mảng theo thứ tự tăng dần. Bài tập về thuật toán sắp xếp.',
        'created_at' => '2024-01-18 16:45:00'
    ],
    [
        'id' => 5, 
        'title' => 'Tìm kiếm nhị phân', 
        'difficulty' => 'hard',
        'description' => 'Tìm kiếm một phần tử trong mảng đã sắp xếp bằng thuật toán tìm kiếm nhị phân.',
        'created_at' => '2024-01-19 11:30:00'
    ],
    [
        'id' => 6, 
        'title' => 'Tính giai thừa', 
        'difficulty' => 'easy',
        'description' => 'Tính giai thừa của một số nguyên dương. Bài tập về đệ quy và vòng lặp.',
        'created_at' => '2024-01-20 13:20:00'
    ],
    [
        'id' => 7, 
        'title' => 'Fibonacci', 
        'difficulty' => 'medium',
        'description' => 'Tính số Fibonacci thứ n trong dãy Fibonacci. Bài tập về đệ quy và thuật toán động.',
        'created_at' => '2024-01-21 15:10:00'
    ],
    [
        'id' => 8, 
        'title' => 'Đảo ngược chuỗi', 
        'difficulty' => 'easy',
        'description' => 'Đảo ngược thứ tự các ký tự trong một chuỗi. Bài tập về xử lý chuỗi cơ bản.',
        'created_at' => '2024-01-22 08:45:00'
    ],
    [
        'id' => 9, 
        'title' => 'Tìm ước chung lớn nhất', 
        'difficulty' => 'medium',
        'description' => 'Tìm ước chung lớn nhất của hai số nguyên dương bằng thuật toán Euclid.',
        'created_at' => '2024-01-23 12:15:00'
    ],
    [
        'id' => 10, 
        'title' => 'Sắp xếp nhanh (Quick Sort)', 
        'difficulty' => 'hard',
        'description' => 'Triển khai thuật toán sắp xếp nhanh (Quick Sort) để sắp xếp mảng.',
        'created_at' => '2024-01-24 17:30:00'
    ]
];

// Dữ liệu chi tiết của các bài tập
$practice_detail_data = [
    
    // Practice 1: Tính tổng hai số
    1 => [
        'id' => 1,
        'problem_statement' => 'Cho hai số nguyên A và B. Viết chương trình tính và in ra tổng của chúng.',
        'input_format' => 'Dòng đầu tiên chứa số nguyên A.\nDòng thứ hai chứa số nguyên B.',
        'output_format' => 'In ra một số nguyên duy nhất là kết quả của A + B.',
        'constraints' => [
            '-1,000,000,000 ≤ A, B ≤ 1,000,000,000'
        ],
        'sample_code' => [
            'python' => "# Viết code của bạn ở đây\na = int(input())\nb = int(input())\nprint(a + b)\n"
        ],
        'test_cases' => [
            ['id' => 1, 'input' => "1\n2", 'expected_output' => '3', 'is_hidden' => false],
            ['id' => 2, 'input' => "-5\n5", 'expected_output' => '0', 'is_hidden' => false],
            ['id' => 3, 'input' => "100\n-50", 'expected_output' => '50', 'is_hidden' => true],
            ['id' => 4, 'input' => "0\n0", 'expected_output' => '0', 'is_hidden' => true],
        ],
    ],
    
    // Practice 2: Tìm số lớn nhất trong mảng
    2 => [
        'id' => 2,
        'problem_statement' => 'Cho một mảng gồm N số nguyên. Nhiệm vụ của bạn là tìm và in ra giá trị lớn nhất trong mảng đó.',
        'input_format' => "Dòng đầu tiên chứa số nguyên N (số lượng phần tử).\nDòng thứ hai chứa N số nguyên, các số cách nhau bởi một khoảng trắng.",
        'output_format' => 'In ra một số nguyên duy nhất là giá trị lớn nhất tìm được.',
        'constraints' => [
            '1 ≤ N ≤ 100,000',
            '-1,000,000,000 ≤ giá trị phần tử ≤ 1,000,000,000'
        ],
        'sample_code' => [
            'python' => "# Viết code của bạn ở đây\ndef solve():\n    n = int(input())\n    arr = list(map(int, input().split()))\n    print(max(arr))\n\nsolve()\n"
        ],
        'test_cases' => [
            ['id' => 6, 'input' => "5\n3 7 1 9 4", 'expected_output' => '9', 'is_hidden' => false],
            ['id' => 7, 'input' => "3\n-1 -5 -3", 'expected_output' => '-1', 'is_hidden' => false],
            ['id' => 8, 'input' => "1\n42", 'expected_output' => '42', 'is_hidden' => true],
        ],
    ],
    
    // Practice 3: Kiểm tra số nguyên tố
    3 => [
        'id' => 3,
        'problem_statement' => 'Cho một số nguyên dương N. Hãy xác định xem N có phải là số nguyên tố hay không.',
        'input_format' => "Một dòng duy nhất chứa số nguyên N.",
        'output_format' => 'In ra "YES" nếu N là số nguyên tố, ngược lại in ra "NO".',
        'constraints' => [
            '1 ≤ N ≤ 1,000,000,000'
        ],
        'sample_code' => [
            'python' => "import math\n\ndef is_prime(n):\n    if n < 2:\n        return False\n    for i in range(2, int(math.sqrt(n)) + 1):\n        if n % i == 0:\n            return False\n    return True\n\ndef solve():\n    n = int(input())\n    if is_prime(n):\n        print(\"YES\")\n    else:\n        print(\"NO\")\n\nsolve()\n"
        ],
        'test_cases' => [
            ['id' => 10, 'input' => '17', 'expected_output' => 'YES', 'is_hidden' => false],
            ['id' => 11, 'input' => '15', 'expected_output' => 'NO', 'is_hidden' => false],
            ['id' => 12, 'input' => '2', 'expected_output' => 'YES', 'is_hidden' => true],
            ['id' => 13, 'input' => '1', 'expected_output' => 'NO', 'is_hidden' => true],
        ],
    ],
    
    // Practice 4: Sắp xếp mảng tăng dần
    4 => [
        'id' => 4,
        'problem_statement' => 'Cho một mảng gồm N số nguyên. Hãy sắp xếp mảng theo thứ tự tăng dần và in ra kết quả.',
        'input_format' => "Dòng đầu tiên chứa số nguyên N (số lượng phần tử).\nDòng thứ hai chứa N số nguyên, các số cách nhau bởi một khoảng trắng.",
        'output_format' => 'In ra N số nguyên đã được sắp xếp theo thứ tự tăng dần, các số cách nhau bởi một khoảng trắng.',
        'constraints' => [
            '1 ≤ N ≤ 10,000',
            '-1,000,000,000 ≤ giá trị phần tử ≤ 1,000,000,000'
        ],
        'sample_code' => [
            'python' => "# Viết code của bạn ở đây\ndef solve():\n    n = int(input())\n    arr = list(map(int, input().split()))\n    arr.sort()\n    print(' '.join(map(str, arr)))\n\nsolve()\n"
        ],
        'test_cases' => [
            ['id' => 14, 'input' => "5\n3 1 4 1 5", 'expected_output' => '1 1 3 4 5', 'is_hidden' => false],
            ['id' => 15, 'input' => "3\n-1 -5 -3", 'expected_output' => '-5 -3 -1', 'is_hidden' => false],
            ['id' => 16, 'input' => "1\n42", 'expected_output' => '42', 'is_hidden' => true],
        ],
    ],
    
    // Practice 5: Tìm kiếm nhị phân
    5 => [
        'id' => 5,
        'problem_statement' => 'Cho một mảng đã sắp xếp và một giá trị X. Hãy tìm vị trí của X trong mảng bằng thuật toán tìm kiếm nhị phân.',
        'input_format' => "Dòng đầu tiên chứa số nguyên N (số lượng phần tử).\nDòng thứ hai chứa N số nguyên đã sắp xếp tăng dần.\nDòng thứ ba chứa số nguyên X cần tìm.",
        'output_format' => 'In ra vị trí của X trong mảng (bắt đầu từ 0). Nếu không tìm thấy, in ra -1.',
        'constraints' => [
            '1 ≤ N ≤ 100,000',
            '-1,000,000,000 ≤ giá trị phần tử ≤ 1,000,000,000',
            '-1,000,000,000 ≤ X ≤ 1,000,000,000'
        ],
        'sample_code' => [
            'python' => "# Viết code của bạn ở đây\ndef binary_search(arr, x):\n    left, right = 0, len(arr) - 1\n    while left <= right:\n        mid = (left + right) // 2\n        if arr[mid] == x:\n            return mid\n        elif arr[mid] < x:\n            left = mid + 1\n        else:\n            right = mid - 1\n    return -1\n\ndef solve():\n    n = int(input())\n    arr = list(map(int, input().split()))\n    x = int(input())\n    print(binary_search(arr, x))\n\nsolve()\n"
        ],
        'test_cases' => [
            ['id' => 17, 'input' => "5\n1 3 5 7 9\n5", 'expected_output' => '2', 'is_hidden' => false],
            ['id' => 18, 'input' => "4\n1 2 3 4\n6", 'expected_output' => '-1', 'is_hidden' => false],
            ['id' => 19, 'input' => "1\n5\n5", 'expected_output' => '0', 'is_hidden' => true],
        ],
    ],
    
    // Practice 6: Tính giai thừa
    6 => [
        'id' => 6,
        'problem_statement' => 'Cho một số nguyên dương N. Hãy tính và in ra giai thừa của N (N!).',
        'input_format' => 'Một dòng duy nhất chứa số nguyên N.',
        'output_format' => 'In ra một số nguyên là kết quả của N!.',
        'constraints' => [
            '1 ≤ N ≤ 20'
        ],
        'sample_code' => [
            'python' => "# Viết code của bạn ở đây\ndef factorial(n):\n    if n <= 1:\n        return 1\n    return n * factorial(n - 1)\n\ndef solve():\n    n = int(input())\n    print(factorial(n))\n\nsolve()\n"
        ],
        'test_cases' => [
            ['id' => 20, 'input' => '5', 'expected_output' => '120', 'is_hidden' => false],
            ['id' => 21, 'input' => '3', 'expected_output' => '6', 'is_hidden' => false],
            ['id' => 22, 'input' => '10', 'expected_output' => '3628800', 'is_hidden' => true],
        ],
    ],
    
    // Practice 7: Fibonacci
    7 => [
        'id' => 7,
        'problem_statement' => 'Cho một số nguyên N. Hãy tính và in ra số Fibonacci thứ N trong dãy Fibonacci.',
        'input_format' => 'Một dòng duy nhất chứa số nguyên N.',
        'output_format' => 'In ra một số nguyên là số Fibonacci thứ N.',
        'constraints' => [
            '0 ≤ N ≤ 30'
        ],
        'sample_code' => [
            'python' => "# Viết code của bạn ở đây\ndef fibonacci(n):\n    if n <= 1:\n        return n\n    return fibonacci(n - 1) + fibonacci(n - 2)\n\ndef solve():\n    n = int(input())\n    print(fibonacci(n))\n\nsolve()\n"
        ],
        'test_cases' => [
            ['id' => 23, 'input' => '5', 'expected_output' => '5', 'is_hidden' => false],
            ['id' => 24, 'input' => '8', 'expected_output' => '21', 'is_hidden' => false],
            ['id' => 25, 'input' => '0', 'expected_output' => '0', 'is_hidden' => true],
        ],
    ],
    
    // Practice 8: Đảo ngược chuỗi
    8 => [
        'id' => 8,
        'problem_statement' => 'Cho một chuỗi S. Hãy đảo ngược thứ tự các ký tự trong chuỗi và in ra kết quả.',
        'input_format' => 'Một dòng duy nhất chứa chuỗi S.',
        'output_format' => 'In ra chuỗi đã được đảo ngược.',
        'constraints' => [
            '1 ≤ độ dài chuỗi S ≤ 1000'
        ],
        'sample_code' => [
            'python' => "# Viết code của bạn ở đây\ndef solve():\n    s = input().strip()\n    print(s[::-1])\n\nsolve()\n"
        ],
        'test_cases' => [
            ['id' => 26, 'input' => 'hello', 'expected_output' => 'olleh', 'is_hidden' => false],
            ['id' => 27, 'input' => 'abc', 'expected_output' => 'cba', 'is_hidden' => false],
            ['id' => 28, 'input' => 'racecar', 'expected_output' => 'racecar', 'is_hidden' => true],
        ],
    ],
    
    // Practice 9: Tìm ước chung lớn nhất
    9 => [
        'id' => 9,
        'problem_statement' => 'Cho hai số nguyên dương A và B. Hãy tìm và in ra ước chung lớn nhất (GCD) của chúng.',
        'input_format' => 'Một dòng duy nhất chứa hai số nguyên dương A và B, cách nhau bởi một khoảng trắng.',
        'output_format' => 'In ra một số nguyên là ước chung lớn nhất của A và B.',
        'constraints' => [
            '1 ≤ A, B ≤ 1,000,000,000'
        ],
        'sample_code' => [
            'python' => "# Viết code của bạn ở đây\ndef gcd(a, b):\n    while b:\n        a, b = b, a % b\n    return a\n\ndef solve():\n    a, b = map(int, input().split())\n    print(gcd(a, b))\n\nsolve()\n"
        ],
        'test_cases' => [
            ['id' => 29, 'input' => '12 18', 'expected_output' => '6', 'is_hidden' => false],
            ['id' => 30, 'input' => '7 13', 'expected_output' => '1', 'is_hidden' => false],
            ['id' => 31, 'input' => '100 25', 'expected_output' => '25', 'is_hidden' => true],
        ],
    ],
    
    // Practice 10: Sắp xếp nhanh (Quick Sort)
    10 => [
        'id' => 10,
        'problem_statement' => 'Cho một mảng gồm N số nguyên. Hãy sắp xếp mảng bằng thuật toán Quick Sort và in ra kết quả.',
        'input_format' => "Dòng đầu tiên chứa số nguyên N (số lượng phần tử).\nDòng thứ hai chứa N số nguyên, các số cách nhau bởi một khoảng trắng.",
        'output_format' => 'In ra N số nguyên đã được sắp xếp theo thứ tự tăng dần, các số cách nhau bởi một khoảng trắng.',
        'constraints' => [
            '1 ≤ N ≤ 10,000',
            '-1,000,000,000 ≤ giá trị phần tử ≤ 1,000,000,000'
        ],
        'sample_code' => [
            'python' => "# Viết code của bạn ở đây\ndef quick_sort(arr):\n    if len(arr) <= 1:\n        return arr\n    pivot = arr[len(arr) // 2]\n    left = [x for x in arr if x < pivot]\n    middle = [x for x in arr if x == pivot]\n    right = [x for x in arr if x > pivot]\n    return quick_sort(left) + middle + quick_sort(right)\n\ndef solve():\n    n = int(input())\n    arr = list(map(int, input().split()))\n    sorted_arr = quick_sort(arr)\n    print(' '.join(map(str, sorted_arr)))\n\nsolve()\n"
        ],
        'test_cases' => [
            ['id' => 32, 'input' => "5\n3 1 4 1 5", 'expected_output' => '1 1 3 4 5', 'is_hidden' => false],
            ['id' => 33, 'input' => "3\n-1 -5 -3", 'expected_output' => '-5 -3 -1', 'is_hidden' => false],
            ['id' => 34, 'input' => "1\n42", 'expected_output' => '42', 'is_hidden' => true],
        ],
    ],
    
];

// Hàm helper để lấy practice data theo ID
function getPracticeData($id) {
    global $practice_data;
    if (!is_array($practice_data)) {
        return false;
    }
    foreach ($practice_data as $practice) {
        if ($practice['id'] == $id) {
            return $practice;
        }
    }
    return false;
}

// Hàm helper để lấy practice detail data theo ID
function getPracticeDetailData($id) {
    global $practice_detail_data;
    return isset($practice_detail_data[$id]) ? $practice_detail_data[$id] : null;
}

// Hàm helper để lấy tất cả practice data
function getAllPracticeData() {
    global $practice_data;
    return is_array($practice_data) ? $practice_data : [];
}

// Hàm helper để lấy test cases của một practice
function getTestCases($practiceId) {
    global $practice_detail_data;
    if (isset($practice_detail_data[$practiceId])) {
        return $practice_detail_data[$practiceId]['test_cases'];
    }
    return [];
}

// Hàm helper để lấy sample code của một practice
// function getSampleCode($practiceId, $language = 'python') {
//     global $practice_detail_data;
//     if (isset($practice_detail_data[$practiceId]['sample_code'][$language])) {
//         return $practice_detail_data[$practiceId]['sample_code'][$language];
//     }
//     return '';
// }

?>
