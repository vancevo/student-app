<?php

/**
 * Dummy data cho practices
 * Bao gồm practice_data và practice_detail_data
 */

// Dữ liệu cơ bản của các bài tập
$practice_data = [
    [
        'id' => 1,
        'title' => 'Tính tổng hai số A và B',
        'description' => 'Viết một chương trình nhận vào hai số nguyên A và B, sau đó in ra tổng của chúng.',
        'difficulty' => 'easy',
        'created_at' => '2024-01-15 10:00:00'
    ],
    [
        'id' => 2,
        'title' => 'Tìm số lớn nhất trong mảng',
        'description' => 'Viết chương trình tìm và in ra số lớn nhất trong một mảng các số nguyên.',
        'difficulty' => 'easy',
        'created_at' => '2024-01-16 10:00:00'
    ],
    [
        'id' => 3,
        'title' => 'Kiểm tra số nguyên tố',
        'description' => 'Viết chương trình kiểm tra một số có phải là số nguyên tố hay không.',
        'difficulty' => 'medium',
        'created_at' => '2024-01-17 10:00:00'
    ],
    [
        'id' => 4,
        'title' => 'Sắp xếp mảng theo thứ tự tăng dần',
        'description' => 'Viết chương trình sắp xếp một mảng các số nguyên theo thứ tự tăng dần.',
        'difficulty' => 'medium',
        'created_at' => '2024-01-18 10:00:00'
    ],
    [
        'id' => 5,
        'title' => 'Tìm kiếm nhị phân',
        'description' => 'Viết chương trình tìm kiếm nhị phân trong một mảng đã được sắp xếp.',
        'difficulty' => 'hard',
        'created_at' => '2024-01-19 10:00:00'
    ],
    [
        'id' => 6,
        'title' => 'Tính giai thừa',
        'description' => 'Viết chương trình tính giai thừa của một số nguyên dương.',
        'difficulty' => 'easy',
        'created_at' => '2024-01-20 10:00:00'
    ],
    [
        'id' => 7,
        'title' => 'Fibonacci',
        'description' => 'Viết chương trình tính số Fibonacci thứ n.',
        'difficulty' => 'medium',
        'created_at' => '2024-01-21 10:00:00'
    ],
    [
        'id' => 8,
        'title' => 'Đảo ngược chuỗi',
        'description' => 'Viết chương trình đảo ngược một chuỗi ký tự.',
        'difficulty' => 'easy',
        'created_at' => '2024-01-22 10:00:00'
    ],
    [
        'id' => 9,
        'title' => 'Tìm ước chung lớn nhất',
        'description' => 'Viết chương trình tìm ước chung lớn nhất của hai số nguyên.',
        'difficulty' => 'medium',
        'created_at' => '2024-01-23 10:00:00'
    ],
    [
        'id' => 10,
        'title' => 'Sắp xếp nhanh (Quick Sort)',
        'description' => 'Viết chương trình sắp xếp mảng sử dụng thuật toán Quick Sort.',
        'difficulty' => 'hard',
        'created_at' => '2024-01-24 10:00:00'
    ]
];

// Dữ liệu chi tiết của các bài tập (bao gồm test cases)
$practice_detail_data = [
    // Practice 1: Tính tổng hai số
    1 => [
        'practice' => $practice_data[0],
        'test_cases' => [
            [
                'id' => 1,
                'practice_id' => 1,
                'input' => '1 2',
                'expected_output' => '3',
                'is_hidden' => false
            ],
            [
                'id' => 2,
                'practice_id' => 1,
                'input' => '-5 5',
                'expected_output' => '0',
                'is_hidden' => false
            ],
            [
                'id' => 3,
                'practice_id' => 1,
                'input' => '100 -50',
                'expected_output' => '50',
                'is_hidden' => false
            ],
            [
                'id' => 4,
                'practice_id' => 1,
                'input' => '0 0',
                'expected_output' => '0',
                'is_hidden' => true
            ],
            [
                'id' => 5,
                'practice_id' => 1,
                'input' => '-1000 1000',
                'expected_output' => '0',
                'is_hidden' => true
            ]
        ],
    ],
    
    // Practice 2: Tìm số lớn nhất trong mảng
    2 => [
        'practice' => $practice_data[1],
        'test_cases' => [
            [
                'id' => 6,
                'practice_id' => 2,
                'input' => '5\n3 7 1 9 4',
                'expected_output' => '9',
                'is_hidden' => false
            ],
            [
                'id' => 7,
                'practice_id' => 2,
                'input' => '3\n-1 -5 -3',
                'expected_output' => '-1',
                'is_hidden' => false
            ],
            [
                'id' => 8,
                'practice_id' => 2,
                'input' => '1\n42',
                'expected_output' => '42',
                'is_hidden' => false
            ],
            [
                'id' => 9,
                'practice_id' => 2,
                'input' => '10\n1 2 3 4 5 6 7 8 9 10',
                'expected_output' => '10',
                'is_hidden' => true
            ]
        ],
    ],
    
    // Practice 3: Kiểm tra số nguyên tố
    3 => [
        'practice' => $practice_data[2],
        'test_cases' => [
            [
                'id' => 10,
                'practice_id' => 3,
                'input' => '17',
                'expected_output' => 'YES',
                'is_hidden' => false
            ],
            [
                'id' => 11,
                'practice_id' => 3,
                'input' => '15',
                'expected_output' => 'NO',
                'is_hidden' => false
            ],
            [
                'id' => 12,
                'practice_id' => 3,
                'input' => '2',
                'expected_output' => 'YES',
                'is_hidden' => false
            ],
            [
                'id' => 13,
                'practice_id' => 3,
                'input' => '1',
                'expected_output' => 'NO',
                'is_hidden' => true
            ],
            [
                'id' => 14,
                'practice_id' => 3,
                'input' => '97',
                'expected_output' => 'YES',
                'is_hidden' => true
            ]
        ],
    ],
    
    // Practice 4: Sắp xếp mảng
    4 => [
        'practice' => $practice_data[3],
        'test_cases' => [
            [
                'id' => 15,
                'practice_id' => 4,
                'input' => '5\n3 1 4 1 5',
                'expected_output' => '1 1 3 4 5',
                'is_hidden' => false
            ],
            [
                'id' => 16,
                'practice_id' => 4,
                'input' => '3\n-1 0 1',
                'expected_output' => '-1 0 1',
                'is_hidden' => false
            ],
            [
                'id' => 17,
                'practice_id' => 4,
                'input' => '4\n5 2 8 1',
                'expected_output' => '1 2 5 8',
                'is_hidden' => true
            ]
        ],
    ],
    
    // Practice 5: Tìm kiếm nhị phân
    5 => [
        'practice' => $practice_data[4],
        'test_cases' => [
            [
                'id' => 18,
                'practice_id' => 5,
                'input' => '5 3\n1 2 3 4 5',
                'expected_output' => '2',
                'is_hidden' => false
            ],
            [
                'id' => 19,
                'practice_id' => 5,
                'input' => '4 6\n1 3 5 7',
                'expected_output' => '-1',
                'is_hidden' => false
            ],
            [
                'id' => 20,
                'practice_id' => 5,
                'input' => '3 1\n1 2 3',
                'expected_output' => '0',
                'is_hidden' => true
            ]
        ],
    ],
    
    // Practice 6: Tính giai thừa
    6 => [
        'practice' => $practice_data[5],
        'test_cases' => [
            [
                'id' => 21,
                'practice_id' => 6,
                'input' => '5',
                'expected_output' => '120',
                'is_hidden' => false
            ],
            [
                'id' => 22,
                'practice_id' => 6,
                'input' => '3',
                'expected_output' => '6',
                'is_hidden' => false
            ],
            [
                'id' => 23,
                'practice_id' => 6,
                'input' => '1',
                'expected_output' => '1',
                'is_hidden' => true
            ],
            [
                'id' => 24,
                'practice_id' => 6,
                'input' => '10',
                'expected_output' => '3628800',
                'is_hidden' => true
            ]
        ],
    ],
    
    // Practice 7: Fibonacci
    7 => [
        'practice' => $practice_data[6],
        'test_cases' => [
            [
                'id' => 25,
                'practice_id' => 7,
                'input' => '5',
                'expected_output' => '5',
                'is_hidden' => false
            ],
            [
                'id' => 26,
                'practice_id' => 7,
                'input' => '10',
                'expected_output' => '55',
                'is_hidden' => false
            ],
            [
                'id' => 27,
                'practice_id' => 7,
                'input' => '1',
                'expected_output' => '1',
                'is_hidden' => true
            ],
            [
                'id' => 28,
                'practice_id' => 7,
                'input' => '20',
                'expected_output' => '6765',
                'is_hidden' => true
            ]
        ],
    ],
    
    // Practice 8: Đảo ngược chuỗi
    8 => [
        'practice' => $practice_data[7],
        'test_cases' => [
            [
                'id' => 29,
                'practice_id' => 8,
                'input' => 'hello',
                'expected_output' => 'olleh',
                'is_hidden' => false
            ],
            [
                'id' => 30,
                'practice_id' => 8,
                'input' => 'world',
                'expected_output' => 'dlrow',
                'is_hidden' => false
            ],
            [
                'id' => 31,
                'practice_id' => 8,
                'input' => 'a',
                'expected_output' => 'a',
                'is_hidden' => true
            ],
            [
                'id' => 32,
                'practice_id' => 8,
                'input' => 'programming',
                'expected_output' => 'gnimmargorp',
                'is_hidden' => true
            ]
        ],
    ],
    
    // Practice 9: Tìm ước chung lớn nhất
    9 => [
        'practice' => $practice_data[8],
        'test_cases' => [
            [
                'id' => 33,
                'practice_id' => 9,
                'input' => '12 18',
                'expected_output' => '6',
                'is_hidden' => false
            ],
            [
                'id' => 34,
                'practice_id' => 9,
                'input' => '15 25',
                'expected_output' => '5',
                'is_hidden' => false
            ],
            [
                'id' => 35,
                'practice_id' => 9,
                'input' => '7 13',
                'expected_output' => '1',
                'is_hidden' => true
            ],
            [
                'id' => 36,
                'practice_id' => 9,
                'input' => '100 50',
                'expected_output' => '50',
                'is_hidden' => true
            ]
        ],
    ],
    
    // Practice 10: Quick Sort
    10 => [
        'practice' => $practice_data[9],
        'test_cases' => [
            [
                'id' => 37,
                'practice_id' => 10,
                'input' => '5\n3 1 4 1 5',
                'expected_output' => '1 1 3 4 5',
                'is_hidden' => false
            ],
            [
                'id' => 38,
                'practice_id' => 10,
                'input' => '6\n6 5 4 3 2 1',
                'expected_output' => '1 2 3 4 5 6',
                'is_hidden' => false
            ],
            [
                'id' => 39,
                'practice_id' => 10,
                'input' => '4\n4 2 1 3',
                'expected_output' => '1 2 3 4',
                'is_hidden' => true
            ]
        ],
    ]
];

// Hàm helper để lấy practice data theo ID
function getPracticeData($id) {
    global $practice_data;
    foreach ($practice_data as $practice) {
        if ($practice['id'] == $id) {
            return $practice;
        }
    }
    return null;
}

// Hàm helper để lấy practice detail data theo ID
function getPracticeDetailData($id) {
    global $practice_detail_data;
    return isset($practice_detail_data[$id]) ? $practice_detail_data[$id] : null;
}

// Hàm helper để lấy tất cả practice data
function getAllPracticeData() {
    global $practice_data;
    return $practice_data;
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
