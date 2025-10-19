# 📋 Survey Data Configuration

## 📁 Cấu trúc file

```
config/
└── survey_data.php    # File chứa dữ liệu câu hỏi khảo sát
```

## 🔧 Cách sử dụng

### 1. **Thêm câu hỏi mới vào category hiện có**
Ví dụ: Thêm câu hỏi thứ 2 vào category "control":

```php
'control' => [
    'title' => 'Control (Kiểm soát)',
    'questions' => [
        [
            'id' => 'control_1',
            'question' => 'Câu hỏi hiện tại...',
            'options' => [...]
        ],
        // Thêm câu hỏi mới
        [
            'id' => 'control_2',
            'question' => 'Câu hỏi mới của bạn...',
            'options' => [
                'Lựa chọn 1',
                'Lựa chọn 2',
                'Lựa chọn 3',
                'Lựa chọn 4',
                'Lựa chọn 5'
            ]
        ]
    ]
]
```

### 2. **Thêm category mới**
```php
return [
    'control' => [...],
    'ownership' => [...],
    'reach' => [...],
    'endurance' => [...],
    // Thêm category mới
    'new_category' => [
        'title' => 'New Category',
        'questions' => [
            [
                'id' => 'new_category_1',
                'question' => 'Câu hỏi đầu tiên...',
                'options' => [...]
            ]
        ]
    ]
];
```

### 3. **Import trong Controller**
```php
// Load dummyData từ file config
$dummyData = require '../config/survey_data.php';
```

## ✅ Lợi ích

1. **Tách biệt dữ liệu và logic**: Controller chỉ xử lý logic, không chứa dữ liệu
2. **Dễ bảo trì**: Chỉ cần sửa 1 file để thay đổi câu hỏi
3. **Hỗ trợ nhiều câu hỏi**: Có thể có nhiều câu hỏi trong cùng 1 category
4. **Tính điểm tự động**: JavaScript tự động tính điểm trung bình cho các câu hỏi trong cùng category
5. **Tái sử dụng**: Có thể import file này ở nhiều nơi khác
6. **Version control**: Dễ dàng track changes của dữ liệu
7. **Team work**: Designer có thể sửa câu hỏi mà không cần động vào code

## 🔄 Workflow

1. **Designer/Content**: Sửa file `config/survey_data.php`
2. **Developer**: Controller tự động load dữ liệu mới
3. **View**: Tự động render theo cấu trúc mới
4. **JavaScript**: Tự động tính điểm và xử lý form

## 📝 Cấu trúc dữ liệu mới

```php
[
    'category_key' => [
        'title' => 'Tên hiển thị',
        'questions' => [
            [
                'id' => 'category_1',  // ID duy nhất cho câu hỏi
                'question' => 'Nội dung câu hỏi',
                'options' => [
                    'Lựa chọn 1',
                    'Lựa chọn 2',
                    'Lựa chọn 3',
                    'Lựa chọn 4',
                    'Lựa chọn 5'
                ]
            ],
            [
                'id' => 'category_2',  // Câu hỏi thứ 2
                'question' => 'Câu hỏi khác...',
                'options' => [...]
            ]
        ]
    ]
]
```

## 🎯 Tính điểm tự động

- **Tự động detect**: JavaScript tự động phát hiện tất cả câu hỏi từ form
- **Nhóm theo category**: Tự động nhóm câu hỏi theo prefix (control_, ownership_, etc.)
- **Tính điểm trung bình**: Tự động tính điểm trung bình cho mỗi category
- **Không cần hardcode**: Không cần chỉnh sửa JavaScript khi thêm câu hỏi

### Ví dụ:
- **Control có 4 câu hỏi**: `control_1`, `control_2`, `control_3`, `control_4`
- **Tự động tính**: `(control_1 + control_2 + control_3 + control_4) / 4`
- **Ownership có 1 câu hỏi**: `ownership_1`
- **Tự động tính**: `ownership_1`

## ⚠️ Lưu ý

- File config phải return array
- Mỗi category phải có: `title`, `questions`
- Mỗi question phải có: `id`, `question`, `options`
- ID phải unique và theo format: `category_number` (ví dụ: `control_1`, `control_2`)
- Options phải có đúng 5 lựa chọn (tương ứng với thang điểm 1-5)
- Sử dụng UTF-8 encoding để hiển thị tiếng Việt đúng
- **KHÔNG CẦN** cập nhật JavaScript khi thêm câu hỏi mới
- Hệ thống tự động scale với bất kỳ số lượng câu hỏi nào
