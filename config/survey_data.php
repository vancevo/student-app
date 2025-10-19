<?php
/**
 * Survey Data Configuration
 * File chứa dữ liệu câu hỏi khảo sát AQ
 */

return [
    'control' => [
        'title' => 'Control (Kiểm soát)',
        'questions' => [
            [
                'id' => 'control_1',
                'question' => 'Khi một đoạn code không chạy, bạn cảm thấy mình có thể làm gì để kiểm soát tình huống này?',
                'options' => [
                    'Tôi thấy mình bất lực và không thể làm gì.',
                    'Tôi chỉ có thể thử một vài cách ngẫu nhiên.',
                    'Tôi tin mình có thể tìm ra vấn đề bằng cách kiểm tra từng dòng code.',
                    'Tôi tin mình có thể kiểm soát hoàn toàn bằng cách chia nhỏ bài toán.',
                    'Tôi luôn tin rằng mình có thể tìm ra giải pháp và khắc phục vấn đề.'
                ]
            ],
            [
                'id' => 'control_2',
                'question' => 'Khi gặp bug phức tạp, bạn thường làm gì đầu tiên?',
                'options' => [
                    'Tôi cảm thấy hoang mang và không biết bắt đầu từ đâu.',
                    'Tôi thử một vài cách ngẫu nhiên mà tôi nghĩ có thể đúng.',
                    'Tôi đọc lại code từng dòng để tìm lỗi.',
                    'Tôi chia nhỏ vấn đề và debug từng phần một.',
                    'Tôi có một quy trình debug có hệ thống và luôn tin sẽ tìm ra nguyên nhân.'
                ]
            ],
            [
                'id' => 'control_3',
                'question' => 'Khi code của bạn chạy không như mong đợi, bạn cảm thấy thế nào?',
                'options' => [
                    'Tôi cảm thấy bất lực và muốn bỏ cuộc.',
                    'Tôi thử một vài cách khác nhau nhưng không có kế hoạch rõ ràng.',
                    'Tôi phân tích từng bước để hiểu tại sao kết quả không đúng.',
                    'Tôi có chiến lược debug cụ thể và tin rằng sẽ giải quyết được.',
                    'Tôi coi đây là cơ hội học hỏi và luôn tìm ra giải pháp.'
                ]
            ],
            [
                'id' => 'control_4',
                'question' => 'Khi phải làm việc với code cũ và phức tạp, bạn xử lý như thế nào?',
                'options' => [
                    'Tôi cảm thấy choáng ngợp và không biết bắt đầu từ đâu.',
                    'Tôi thử đọc qua một cách nhanh chóng và hy vọng hiểu được.',
                    'Tôi đọc từng phần một cách cẩn thận để hiểu logic.',
                    'Tôi chia nhỏ code thành các phần và phân tích từng phần.',
                    'Tôi có phương pháp tiếp cận có hệ thống và luôn tìm ra cách hiểu code.'
                ]
            ]
        ]
    ],
    'ownership' => [
        'title' => 'Ownership (Trách nhiệm)',
        'questions' => [
            [
                'id' => 'ownership_1',
                'question' => 'Khi code của bạn có lỗi, bạn nghĩ nguyên nhân là gì?',
                'options' => [
                    'Lỗi này là do máy tính hoặc trình biên dịch.',
                    'Tôi không chắc chắn, có thể là do môi trường lập trình.',
                    'Có lẽ tôi đã mắc lỗi ở đâu đó.',
                    'Lỗi này chắc chắn là do tôi, và tôi phải chịu trách nhiệm sửa chữa nó.',
                    'Tôi nhận trách nhiệm hoàn toàn cho lỗi này và coi đó là cơ hội để học hỏi.'
                ]
            ]
        ]
    ],
    'reach' => [
        'title' => 'Reach (Tác động)',
        'questions' => [
            [
                'id' => 'reach_1',
                'question' => 'Một bài tập khó khiến bạn thất bại. Điều này ảnh hưởng đến những khía cạnh nào trong việc học của bạn?',
                'options' => [
                    'Tôi cảm thấy mình kém cỏi và nghĩ rằng mình sẽ không bao giờ giỏi lập trình được.',
                    'Tôi cảm thấy mình không giỏi ở một số phần lập trình, nhưng sẽ cố gắng ở những phần khác.',
                    'Việc này chỉ ảnh hưởng đến bài tập này, không liên quan đến khả năng tổng thể của tôi.',
                    'Tôi biết rằng việc này chỉ là tạm thời và không ảnh hưởng đến mục tiêu lớn hơn của mình.',
                    'Tôi coi đây là một thử thách cụ thể và chỉ tập trung vào việc giải quyết nó.'
                ]
            ]
        ]
    ],
    'endurance' => [
        'title' => 'Endurance (Kiên trì)',
        'questions' => [
            [
                'id' => 'endurance_1',
                'question' => 'Khi bạn gặp một vấn đề khó trong code, bạn nghĩ nó sẽ kéo dài bao lâu?',
                'options' => [
                    'Tôi nghĩ nó sẽ kéo dài vô tận, và tôi sẽ không bao giờ giải quyết được.',
                    'Tôi hy vọng nó sẽ sớm kết thúc, nhưng không có kế hoạch cụ thể.',
                    'Tôi tin rằng nếu kiên trì, tôi sẽ tìm ra giải pháp trong một khoảng thời gian hợp lý.',
                    'Tôi biết rằng với sự nỗ lực, tôi sẽ vượt qua nó trong một vài ngày.',
                    'Tôi tin rằng mỗi khó khăn đều có điểm kết thúc và tôi sẽ tìm ra nó.'
                ]
            ]
        ]
    ]
];
