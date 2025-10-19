<?php
// Load dummy practice data
require_once '../config/practices_data.php';

// Get practice detail data - sử dụng truy cập trực tiếp
$practiceDetail = isset($practice_detail_data[$practice['id']]) ? $practice_detail_data[$practice['id']] : null;
$testCases = $practiceDetail ? $practiceDetail['test_cases'] : [];
$sampleCodes = $practiceDetail ? $practiceDetail['sample_code'] : [];
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
                        <?php echo $practiceDetail['practice']['title']; ?>
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
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Mô tả bài tập</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                <?php echo htmlspecialchars($practice['description']); ?>
                            </p>
                        </div>
                    </div>

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

                    <!-- Submission History -->
                    <?php if (!empty($submissions)): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
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
                                            case 'accepted': echo 'bg-green-100 text-green-800'; break;
                                            case 'wrong_answer': echo 'bg-red-100 text-red-800'; break;
                                            case 'time_limit': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'memory_limit': echo 'bg-orange-100 text-orange-800'; break;
                                            case 'error': echo 'bg-red-100 text-red-800'; break;
                                            case 'pending': echo 'bg-blue-100 text-blue-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $submission['status'])); ?>
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
                </div>
            </div>
        </div>
    </div>

    <script>
        let editor;
        
        // Sample codes from PHP
        const sampleCodes = <?php echo json_encode($sampleCodes); ?>;
        
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
                case 'javascript': return 'javascript';
                case 'python': return 'python';
                case 'cpp': return 'text/x-c++src';
                case 'java': return 'text/x-java';
                default: return 'javascript';
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
                    alert('Nộp bài thành công!');
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
                    defaultCode = '# Nhập code Python của bạn ở đây\n# Ví dụ: Nhập 2 số và tính tổng\nA = int(input("Nhập số nguyên A: "))\nB = int(input("Nhập số nguyên B: "))\nprint(f"Tổng của {A} và {B} là: {A + B}")';
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
