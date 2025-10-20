<?php
// Dữ liệu $practice, $submissions được truyền từ Controller
// Kiểm tra an toàn để tránh lỗi "Trying to access array offset on false"
if (!$practice || !is_array($practice) || !isset($practice['id'])) {
    header('Location: /AQCoder/practices');
    exit;
}

// Fallback: nếu không có test cases trong DB, lấy từ dummy data để hiển thị
// require_once '../config/practices_data.php';
// $practiceDetail = isset($practice_detail_data[$practice['id']]) ? $practice_detail_data[$practice['id']] : null;
$practiceDetail = $practice;
$testCases = $practiceDetail ? ($practiceDetail['test_cases'] ?? []) : [];
?>
<!DOCTYPE html>
<html lang="vi"></html>
<head></head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($practice['title']); ?> - AQCoder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/python/python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
</head>
<body class="bg-gray-100 flex min-h-screen font-sans antialiased">
    <?php require 'partials/sidebar.php'; ?>
    <div class="content flex-1 p-8 overflow-y-auto">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        <?php echo htmlspecialchars($practice['title']); ?>
                    </h1>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            <?php 
                            switch($practice['difficulty']) {
                                case 'easy': echo 'bg-green-100 text-green-800'; break;
                                case 'medium': echo 'bg-yellow-100 text-yellow-800'; break;
                                case 'hard': echo 'bg-red-100 text-red-800'; break;
                                default: echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <?php echo ucfirst($practice['difficulty']); ?>
                        </span>
                        <span class="text-gray-500 text-sm">ID: <?php echo $practice['id']; ?></span>
                    </div>
                </div>
                <a href="/AQCoder/practices" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    ← Quay lại
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Problem Description -->
                <div class="space-y-6">
                    <!-- Problem Statement -->
                    <?php if ($practiceDetail && isset($practiceDetail['problem_statement'])): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Đề bài</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                <?php echo htmlspecialchars($practiceDetail['problem_statement']); ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Input Format -->
                    <?php if ($practiceDetail && isset($practiceDetail['input_format'])): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Định dạng Input</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                <?php echo htmlspecialchars($practiceDetail['input_format']); ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Output Format -->
                    <?php if ($practiceDetail && isset($practiceDetail['output_format'])): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Định dạng Output</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                <?php echo htmlspecialchars($practiceDetail['output_format']); ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Constraints -->
                    <?php if ($practiceDetail && isset($practiceDetail['constraints']) && !empty($practiceDetail['constraints'])): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ràng buộc</h2>
                        <div class="space-y-2">
                            <?php foreach ($practiceDetail['constraints'] as $constraint): ?>
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <p class="text-gray-700"><?php echo htmlspecialchars($constraint); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Fallback Description -->
                    <?php if (!$practiceDetail || !isset($practiceDetail['problem_statement'])): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Mô tả bài tập</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                <?php echo htmlspecialchars($practice['description']); ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Test Cases -->
                    <?php if (!empty($testCases)): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Test Cases</h2>
                        <div class="space-y-4">
                            <?php foreach ($testCases as $index => $testCase): ?>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-medium text-gray-800">Test Case <?php echo $index + 1; ?></h3>
                                    <?php if ($testCase['is_hidden']): ?>
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Hidden</span>
                                    <?php else: ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Visible</span>
                                    <?php endif; ?>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Input:</label>
                                        <div class="bg-gray-50 border border-gray-200 rounded p-3 font-mono text-sm">
                                            <pre><?php echo htmlspecialchars($testCase['input']); ?></pre>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Expected Output:</label>
                                        <div class="bg-gray-50 border border-gray-200 rounded p-3 font-mono text-sm">
                                            <pre><?php echo htmlspecialchars($testCase['expected_output']); ?></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Right Column: Code Editor -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Workspace</h2>
                    
                    <!-- Language Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngôn ngữ lập trình:</label>
                        <select id="language-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <!-- <option value="javascript">JavaScript</option> -->
                            <option value="python">Python</option>
                            <!-- <option value="cpp">C++</option> -->
                            <!-- <option value="java">Java</option> -->
                        </select>
                    </div>

                    <!-- Code Editor -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Code:</label>
                        <textarea id="code-editor" class="w-full h-96 border border-gray-300 rounded-lg font-mono text-sm"></textarea>
                    </div>

                    <!-- Input Field -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Input (cho hàm input()):</label>
                        <textarea id="custom-input" class="w-full h-20 border border-gray-300 rounded-lg font-mono text-sm p-3" placeholder="Nhập input cho code Python &#10;Ví dụ:&#10;5&#10;-10&#10;hoặc&#10;3&#10;1 2 3"></textarea>
                        <div class="text-xs text-gray-500 mt-1 space-y-1">
                            <p><strong>Quan trọng:</strong> Mỗi lần gọi input() sẽ đọc một dòng riêng biệt</p>
                            <p>• Nếu để trống, hệ thống sẽ sử dụng input mặc định từ test case</p>
                            <p>• Ví dụ: Để nhập 2 số 5 và -10, nhập:</p>
                            <div class="bg-gray-100 p-2 rounded font-mono text-xs">
                                5<br>-10
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <!-- <div class="flex space-x-3 mb-4">
                        <button id="load-sample" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            Load Sample Code
                        </button>
                    </div> -->

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button id="run-code" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            Chạy thử
                        </button>
                        <button id="submit-code" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            Nộp bài
                        </button>
                    </div>

                    <!-- Output Area -->
                    <div id="output-area" class="mt-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kết quả:</label>
                        <div id="output-content" class="bg-gray-100 border border-gray-300 rounded-lg p-4 min-h-32 font-mono text-sm whitespace-pre-wrap"></div>
                    </div>

                    <!-- Submission History -->
                    <?php if (!empty($submissions)): ?>
                    <div class="rounded-lg p-6 mt-10">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Lịch sử nộp bài</h2>
                        <div class="space-y-3">
                            <?php foreach ($submissions as $submission): ?>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="text-sm text-gray-600">
                                        <?php echo date('d/m/Y H:i:s', strtotime($submission['submitted_at'])); ?>
                                    </div>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        <?php 
                                        switch($submission['status']) {
                                            case 'excellent': echo 'bg-green-100 text-green-800'; break;
                                            case 'accepted': echo 'bg-green-100 text-green-800'; break;
                                            case 'good': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'wrong_answer': echo 'bg-red-100 text-red-800'; break;
                                            case 'time_limit': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'memory_limit': echo 'bg-orange-100 text-orange-800'; break;
                                            case 'error': echo 'bg-red-100 text-red-800'; break;
                                            case 'pending': echo 'bg-gray-100 text-gray-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?php 
                                        $statusText = $submission['status'];
                                        switch($submission['status']) {
                                            case 'excellent': $statusText = 'Xuất sắc'; break;
                                            case 'accepted': $statusText = 'Chấp nhận'; break;
                                            case 'good': $statusText = 'Tốt'; break;
                                            case 'wrong_answer': $statusText = 'Sai đáp án'; break;
                                            case 'time_limit': $statusText = 'Quá thời gian'; break;
                                            case 'memory_limit': $statusText = 'Quá bộ nhớ'; break;
                                            case 'error': $statusText = 'Lỗi'; break;
                                            case 'pending': $statusText = 'Đang chờ'; break;
                                        }
                                        echo $statusText;
                                        ?>
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 mb-2">
                                    Ngôn ngữ: <?php echo htmlspecialchars($submission['language']); ?>
                                </div>
                                <details class="text-sm">
                                    <summary class="cursor-pointer text-blue-600 hover:text-blue-800">Xem code</summary>
                                    <pre class="mt-2 p-3 bg-gray-100 rounded text-xs overflow-x-auto"><code><?php echo htmlspecialchars($submission['code']); ?></code></pre>
                                </details>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>

    <script>
        let editor;
        
        
        // Initialize CodeMirror
        function initEditor() {
            const textarea = document.getElementById('code-editor');
            const language = "python" //document.getElementById('language-select').value;
            
            if (editor) {
                editor.toTextArea();
            }
            
            editor = CodeMirror.fromTextArea(textarea, {
                mode: getModeForLanguage(language),
                theme: 'monokai',
                lineNumbers: true,
                indentUnit: 4,
                tabSize: 4,
                lineWrapping: true,
                autoCloseBrackets: true,
                matchBrackets: true
            });
        }
        
        function getModeForLanguage(lang) {
            switch(lang) {
                // case 'javascript': return 'javascript';
                case 'python': return 'python';
                // case 'cpp': return 'text/x-c++src';
                // case 'java': return 'text/x-java';
                default: return 'python';
            }
        }
        
        // Language change handler
        document.getElementById('language-select').addEventListener('change', function() {
            const language = "python" //this.value;
            editor.setOption('mode', getModeForLanguage(language));
        });
        
        // Load sample code handler
        // document.getElementById('load-sample').addEventListener('click', function() {
        //     const language = "python" //document.getElementById('language-select').value;
        //     const sampleCode = sampleCodes[language];
            
        //     console.log(sampleCodes);
        //     console.log(language);

        //     if (sampleCode) {
        //         editor.setValue(sampleCode);
        //     } else {
        //         alert('Không có sample code cho ngôn ngữ ' + language);
        //     }
        // });
        
        // Run code handler
        document.getElementById('run-code').addEventListener('click', async function() {
            const code = editor.getValue();
            const language = document.getElementById('language-select').value;
            const customInput = document.getElementById('custom-input').value.trim();
            
            if (!code.trim()) {
                alert('Vui lòng nhập code trước khi chạy!');
                return;
            }
            
            // Show output area
            document.getElementById('output-area').classList.remove('hidden');
            document.getElementById('output-content').innerHTML = 'Đang chạy code...';
            
            try {
                const response = await fetch('/AQCoder/run-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        practice_id: <?php echo $practice['id']; ?>,
                        language: language,
                        code: code,
                        custom_input: customInput
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let outputText = '';
                    
                    if (result.output) {
                        outputText += result.output;
                    }
                    
                    if (result.error) {
                        outputText += (outputText ? '\n\n' : '') + 'Lỗi: ' + result.error;
                    }
                    
                    if (result.execution_time) {
                        outputText += (outputText ? '\n\n' : '') + `Thời gian thực thi: ${result.execution_time}ms`;
                    }
                    
                    document.getElementById('output-content').innerHTML = (outputText || 'Code chạy thành công nhưng không có output.').replace(/\n/g, '<br>');
                } else {
                    document.getElementById('output-content').innerHTML = 'Lỗi: ' + result.message;
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('output-content').innerHTML = 'Có lỗi xảy ra khi chạy code!';
            }
        });
        
        // Submit code handler
        document.getElementById('submit-code').addEventListener('click', async function() {
            const code = editor.getValue();
            const language = document.getElementById('language-select').value;
            
            if (!code.trim()) {
                alert('Vui lòng nhập code trước khi nộp bài!');
                return;
            }
            
            try {
                const response = await fetch('/AQCoder/submit-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        practice_id: <?php echo $practice['id']; ?>,
                        language: language,
                        code: code
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let message = `Nộp bài thành công!\n\n`;
                    message += `Kết quả: ${result.passed}/${result.total} test cases passed\n`;
                    message += `Trạng thái: ${result.status}\n`;
                    message += `Thông báo: ${result.message}`;
                    
                    alert(message);
                    // Reload page to show updated submission history
                    window.location.reload();
                } else {
                    alert('Lỗi khi nộp bài: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi nộp bài!');
            }
        });
        
        // Initialize editor on page load
        document.addEventListener('DOMContentLoaded', function() {
            initEditor();
            
            // Set default code based on language and sample codes
            const language = document.getElementById('language-select').value;
            let defaultCode = '';
            
            switch(language) {
                // case 'javascript':
                //     defaultCode = '// Nhập code JavaScript của bạn ở đây\nconsole.log("Hello World");';
                //     break;
                case 'python':
                    defaultCode = '# Viết code của bạn ở đây\n# Ví dụ: đọc 2 số nguyên\n# a = int(input())\n# b = int(input())\n# print(a + b)';
                    break;
                // case 'cpp':
                //     defaultCode = '#include <iostream>\nusing namespace std;\n\nint main() {\n    // Nhập code C++ của bạn ở đây\n    cout << "Hello World" << endl;\n    return 0;\n}';
                //     break;
                // case 'java':
                //     defaultCode = 'public class Main {\n    public static void main(String[] args) {\n        // Nhập code Java của bạn ở đây\n        System.out.println("Hello World");\n    }\n}';
                //     break;
            }
            
            editor.setValue(defaultCode);
        });
    </script>
</body>
</html>
