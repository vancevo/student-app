<?php
/**
 * Survey Data Configuration
 * File chứa dữ liệu câu hỏi khảo sát AQ
 */
$options = [
    'Hoàn toàn không đồng ý.',
    'Không đồng ý.',
    'Phân vân.',
    'Đồng ý.',
    'Hoàn toàn đồng ý.'
];

// Bạn cần định nghĩa các lựa chọn chung tại đây
return [
    'control' => [
        'title' => 'CONTROL – Khả năng kiểm soát tình huống',
        'questions' => [
            [
                'id' => 'control_1',
                'question' => 'Khi chương trình Python báo lỗi, tôi bình tĩnh đọc thông báo để tìm nguyên nhân.',
                'options' => $options
            ],
            [
                'id' => 'control_2',
                'question' => 'Tôi không hoảng sợ hoặc bỏ cuộc khi code không chạy như mong đợi.',
                'options' => $options
            ],
            [
                'id' => 'control_3',
                'question' => 'Tôi có thể tự điều chỉnh kế hoạch học lập trình khi gặp bài khó.',
                'options' => $options
            ],
            [
                'id' => 'control_4',
                'question' => 'Tôi tin rằng mình có thể cải thiện kỹ năng lập trình bằng cách luyện tập thêm.',
                'options' => $options
            ],
            [
                'id' => 'control_5',
                'question' => 'Tôi giữ thái độ tích cực khi phải học các khái niệm mới như hàm, vòng lặp hoặc danh sách.',
                'options' => $options
            ],
        ]
    ],
    'ownership' => [
        'title' => 'OWNERSHIP – Tinh thần trách nhiệm và chủ động',
        'questions' => [
            [
                'id' => 'ownership_1',
                'question' => 'Khi bài tập lập trình của nhóm bị lỗi, tôi sẵn sàng nhận phần trách nhiệm của mình.',
                'options' => $options
            ],
            [
                'id' => 'ownership_2',
                'question' => 'Tôi chủ động tìm kiếm tài liệu hoặc hỏi thầy/cô khi chưa hiểu bài.',
                'options' => $options
            ],
            [
                'id' => 'ownership_3',
                'question' => 'Tôi không đổ lỗi cho máy tính hay phần mềm khi kết quả sai — tôi kiểm tra lại code của mình.',
                'options' => $options
            ],
            [
                'id' => 'ownership_4',
                'question' => 'Tôi thường tự viết lại chương trình để hiểu rõ hơn thay vì chỉ chép code mẫu.',
                'options' => $options
            ],
            [
                'id' => 'ownership_5',
                'question' => 'Tôi đặt mục tiêu rõ ràng cho từng buổi học lập trình (ví dụ: nắm chắc vòng lặp for).',
                'options' => $options
            ],
        ]
    ],
    'reach' => [
        'title' => 'REACH – Khả năng giới hạn ảnh hưởng tiêu cực',
        'questions' => [
            [
                'id' => 'reach_1',
                'question' => 'Khi gặp lỗi khó trong một bài Python, tôi không để nó làm ảnh hưởng đến hứng thú học cả môn.',
                'options' => $options
            ],
            [
                'id' => 'reach_2',
                'question' => 'Nếu một buổi học code không hiệu quả, tôi vẫn giữ tinh thần cho buổi sau.',
                'options' => $options
            ],
            [
                'id' => 'reach_3',
                'question' => 'Tôi hiểu rằng một lần thất bại trong lập trình không có nghĩa là tôi không có năng khiếu.',
                'options' => $options
            ],
            [
                'id' => 'reach_4',
                'question' => 'Tôi biết tách biệt cảm xúc khi gặp lỗi để không bị mất tự tin.',
                'options' => $options
            ],
            [
                'id' => 'reach_5',
                'question' => 'Tôi vẫn vui vẻ chia sẻ và thảo luận với bạn dù bài code của mình chưa hoàn chỉnh.',
                'options' => $options
            ],
        ]
    ],
    'endurance' => [
        'title' => 'ENDURANCE – Sự kiên trì và bền bỉ',
        'questions' => [
            [
                'id' => 'endurance_1',
                'question' => 'Tôi sẵn sàng ngồi hàng giờ để sửa lỗi chương trình cho đến khi chạy đúng.',
                'options' => $options
            ],
            [
                'id' => 'endurance_2',
                'question' => 'Khi học phần khó như “hàm đệ quy” hay “xử lý tệp”, tôi không bỏ cuộc.',
                'options' => $options
            ],
            [
                'id' => 'endurance_3',
                'question' => 'Tôi tin rằng việc học lập trình cần thời gian và nỗ lực liên tục.',
                'options' => $options
            ],
            [
                'id' => 'endurance_4',
                'question' => 'Tôi không nản lòng khi phải thử nhiều cách khác nhau để giải quyết một bài toán.',
                'options' => $options
            ],
            [
                'id' => 'endurance_5',
                'question' => 'Tôi cảm thấy hứng thú khi nhìn lại quá trình mình từng gặp nhiều lỗi nhưng cuối cùng đã hiểu sâu hơn.',
                'options' => $options
            ],
        ]
    ]
];
