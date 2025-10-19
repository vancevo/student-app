<?php
class PracticesController {
    private $practiceModel;

    public function __construct(PracticeModel $practiceModel) {
        $this->practiceModel = $practiceModel;
    }

    /**
     * Hiển thị danh sách practices
     */
    public function index() {
        // session_start() đã được gọi trong index.php
        if (!isset($_SESSION['user_id'])) {
            header('Location: /AQCoder/login'); 
            exit;
        }

        $practices = $this->practiceModel->getPractices();
        // Tải View Component practices.php
        require '../views/practices.php'; 
    }

    /**
     * Hiển thị chi tiết practice và workspace để làm bài
     */
    public function showPractice(int $id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /AQCoder/login'); 
            exit;
        }

        $practice = $this->practiceModel->getPracticeById($id);
        
        if (!$practice) {
            header('Location: /AQCoder/practices'); 
            exit;
        }

        // Lấy lịch sử submissions của user cho practice này
        $submissions = $this->practiceModel->getUserSubmissions($_SESSION['user_id'], $id);
        
        // Tải View Component practice-detail.php
        require '../views/practice-detail.php';
    }

    /**
     * Xử lý chạy thử code (không lưu vào database)
     */
    public function runCode() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['language']) || !isset($input['code'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        try {
            $practiceId = isset($input['practice_id']) ? $input['practice_id'] : 1;
            $customInput = isset($input['custom_input']) ? $input['custom_input'] : '';
            $result = $this->executeCode($input['language'], $input['code'], $practiceId, $customInput);
            
            echo json_encode([
                'success' => true,
                'output' => $result['output'],
                'error' => $result['error'],
                'execution_time' => $result['execution_time']
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error executing code: ' . $e->getMessage()]);
        }
    }

    /**
     * Thực thi command với timeout (tương thích macOS)
     */
    private function executeWithTimeout($command, $timeoutSeconds) {
        // Sử dụng PHP's proc_open để implement timeout
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w")   // stderr
        );
        
        $process = proc_open($command, $descriptorspec, $pipes);
        
        if (!is_resource($process)) {
            return "EXIT_CODE:1";
        }
        
        // Set non-blocking mode
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);
        
        $output = '';
        $startTime = time();
        
        while (true) {
            $status = proc_get_status($process);
            
            if (!$status['running']) {
                // Process finished
                $output .= stream_get_contents($pipes[1]);
                $output .= stream_get_contents($pipes[2]);
                $exitCode = $status['exitcode'];
                break;
            }
            
            if ((time() - $startTime) >= $timeoutSeconds) {
                // Timeout reached
                proc_terminate($process);
                proc_close($process);
                return "EXIT_CODE:124"; // Timeout exit code
            }
            
            // Read available output
            $output .= stream_get_contents($pipes[1]);
            $output .= stream_get_contents($pipes[2]);
            
            usleep(100000); // Sleep 100ms
        }
        
        // Close pipes
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        
        proc_close($process);
        
        return $output . "EXIT_CODE:" . $exitCode;
    }

    /**
     * Thực thi code theo ngôn ngữ
     */
    private function executeCode($language, $code, $practiceId = 1, $customInput = '') {
        $tempDir = sys_get_temp_dir();
        $tempFile = tempnam($tempDir, 'code_exec_');
        
        try {
            switch ($language) {
                case 'python':
                    return $this->executePython($code, $tempFile, $practiceId, $customInput);
                // case 'javascript':
                //     return $this->executeJavaScript($code, $tempFile);
                // case 'cpp':
                //     return $this->executeCpp($code, $tempFile);
                // case 'java':
                //     return $this->executeJava($code, $tempFile);
                default:
                    throw new Exception('Unsupported language: ' . $language);
            }
        } finally {
            // Cleanup temp files
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    /**
     * Thực thi Python code
     */
    private function executePython($code, $tempFile, $practiceId = 1, $customInput = '') {
        // Load practice data to get test cases
        require_once __DIR__ . '/../../config/practices_data.php';
        
        // Use custom input if provided, otherwise use test case or default
        $testInput = '';
        if (!empty($customInput)) {
            $testInput = $customInput;
        } else {
            // Get the first visible test case for input
            if (isset($practice_detail_data[$practiceId]['test_cases'][0]['input'])) {
                $testInput = $practice_detail_data[$practiceId]['test_cases'][0]['input'];
            }
            
            // If no test input available or code doesn't match expected format, 
            // try to provide appropriate default input based on code analysis
            if (empty($testInput) || !$this->isInputCompatible($code, $testInput)) {
                $testInput = $this->generateDefaultInput($code);
            }
        }
        
        file_put_contents($tempFile . '.py', $code);
        
        // Tạo file input riêng để đảm bảo format được giữ nguyên
        $inputFile = $tempFile . '_input.txt';
        file_put_contents($inputFile, $testInput);
        
        $startTime = microtime(true);
        
        // Execute Python với timeout 10 giây và cung cấp input từ file
        // Sử dụng python3 -u để unbuffered output
        $command = "python3 -u " . escapeshellarg($tempFile . '.py') . " < " . escapeshellarg($inputFile) . " 2>&1";
        $output = $this->executeWithTimeout($command, 10);
        
        // Extract exit code from output
        $exitCode = 0;
        if (preg_match('/EXIT_CODE:(\d+)$/', $output, $matches)) {
            $exitCode = (int)$matches[1];
            $output = preg_replace('/EXIT_CODE:\d+$/', '', $output);
        }
        
        $executionTime = round((microtime(true) - $startTime) * 1000, 2); // milliseconds
        
        // Cleanup
        if (file_exists($tempFile . '.py')) {
            unlink($tempFile . '.py');
        }
        if (file_exists($inputFile)) {
            unlink($inputFile);
        }
        
        if ($exitCode === 124) { // timeout
            return [
                'output' => '',
                'error' => 'Code execution timeout (10 seconds limit)',
                'execution_time' => $executionTime
            ];
        }
        
        return [
            'output' => $output ?: '',
            'error' => $exitCode !== 0 ? 'Runtime error (exit code: ' . $exitCode . ')' : '',
            'execution_time' => $executionTime
        ];
    }
    
    /**
     * Kiểm tra xem input có tương thích với code không
     */
    private function isInputCompatible($code, $testInput) {
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
    
    /**
     * Tạo input mặc định dựa trên phân tích code
     */
    private function generateDefaultInput($code) {
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

    // /**
    //  * Thực thi JavaScript code
    //  */
    // private function executeJavaScript($code, $tempFile) {
    //     file_put_contents($tempFile . '.js', $code);
        
    //     $startTime = microtime(true);
        
    //     $command = "node " . escapeshellarg($tempFile . '.js') . " 2>&1";
    //     $output = $this->executeWithTimeout($command, 10);
        
    //     // Extract exit code from output
    //     $exitCode = 0;
    //     if (preg_match('/EXIT_CODE:(\d+)$/', $output, $matches)) {
    //         $exitCode = (int)$matches[1];
    //         $output = preg_replace('/EXIT_CODE:\d+$/', '', $output);
    //     }
        
    //     $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
    //     if (file_exists($tempFile . '.js')) {
    //         unlink($tempFile . '.js');
    //     }
        
    //     if ($exitCode === 124) {
    //         return [
    //             'output' => '',
    //             'error' => 'Code execution timeout (10 seconds limit)',
    //             'execution_time' => $executionTime
    //         ];
    //     }
        
    //     return [
    //         'output' => $output ?: '',
    //         'error' => $exitCode !== 0 ? 'Runtime error (exit code: ' . $exitCode . ')' : '',
    //         'execution_time' => $executionTime
    //     ];
    // }

    // /**
    //  * Thực thi C++ code
    //  */
    // private function executeCpp($code, $tempFile) {
    //     file_put_contents($tempFile . '.cpp', $code);
        
    //     $startTime = microtime(true);
        
    //     // Compile
    //     $compileCommand = "g++ -o " . escapeshellarg($tempFile) . " " . escapeshellarg($tempFile . '.cpp') . " 2>&1";
    //     $compileOutput = shell_exec($compileCommand);
        
    //     if (!file_exists($tempFile)) {
    //         return [
    //             'output' => '',
    //             'error' => 'Compilation error: ' . $compileOutput,
    //             'execution_time' => round((microtime(true) - $startTime) * 1000, 2)
    //         ];
    //     }
        
    //     // Execute
    //     $command = escapeshellarg($tempFile) . " 2>&1";
    //     $output = $this->executeWithTimeout($command, 10);
        
    //     // Extract exit code from output
    //     $exitCode = 0;
    //     if (preg_match('/EXIT_CODE:(\d+)$/', $output, $matches)) {
    //         $exitCode = (int)$matches[1];
    //         $output = preg_replace('/EXIT_CODE:\d+$/', '', $output);
    //     }
        
    //     $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
    //     // Cleanup
    //     if (file_exists($tempFile . '.cpp')) {
    //         unlink($tempFile . '.cpp');
    //     }
    //     if (file_exists($tempFile)) {
    //         unlink($tempFile);
    //     }
        
    //     if ($exitCode === 124) {
    //         return [
    //             'output' => '',
    //             'error' => 'Code execution timeout (10 seconds limit)',
    //             'execution_time' => $executionTime
    //         ];
    //     }
        
    //     return [
    //         'output' => $output ?: '',
    //         'error' => $exitCode !== 0 ? 'Runtime error (exit code: ' . $exitCode . ')' : '',
    //         'execution_time' => $executionTime
    //     ];
    // }

    // /**
    //  * Thực thi Java code
    //  */
    // private function executeJava($code, $tempFile) {
    //     // Extract class name from code
    //     if (preg_match('/public\s+class\s+(\w+)/', $code, $matches)) {
    //         $className = $matches[1];
    //     } else {
    //         $className = 'Main';
    //         $code = "public class Main {\n" . $code . "\n}";
    //     }
        
    //     file_put_contents($tempFile . '.java', $code);
        
    //     $startTime = microtime(true);
        
    //     // Compile
    //     $compileCommand = "javac " . escapeshellarg($tempFile . '.java') . " 2>&1";
    //     $compileOutput = shell_exec($compileCommand);
        
    //     if (!file_exists($tempFile . '.class')) {
    //         return [
    //             'output' => '',
    //             'error' => 'Compilation error: ' . $compileOutput,
    //             'execution_time' => round((microtime(true) - $startTime) * 1000, 2)
    //         ];
    //     }
        
    //     // Execute
    //     $command = "java -cp " . dirname($tempFile) . " " . $className . " 2>&1";
    //     $output = $this->executeWithTimeout($command, 10);
        
    //     // Extract exit code from output
    //     $exitCode = 0;
    //     if (preg_match('/EXIT_CODE:(\d+)$/', $output, $matches)) {
    //         $exitCode = (int)$matches[1];
    //         $output = preg_replace('/EXIT_CODE:\d+$/', '', $output);
    //     }
        
    //     $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
    //     // Cleanup
    //     if (file_exists($tempFile . '.java')) {
    //         unlink($tempFile . '.java');
    //     }
    //     if (file_exists($tempFile . '.class')) {
    //         unlink($tempFile . '.class');
    //     }
        
    //     if ($exitCode === 124) {
    //         return [
    //             'output' => '',
    //             'error' => 'Code execution timeout (10 seconds limit)',
    //             'execution_time' => $executionTime
    //         ];
    //     }
        
    //     return [
    //         'output' => $output ?: '',
    //         'error' => $exitCode !== 0 ? 'Runtime error (exit code: ' . $exitCode . ')' : '',
    //         'execution_time' => $executionTime
    //     ];
    // }

    /**
     * Xử lý submit code
     */
    public function submitCode() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['practice_id']) || !isset($input['language']) || !isset($input['code'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        try {
            $submissionId = $this->practiceModel->saveSubmission(
                $_SESSION['user_id'],
                $input['practice_id'],
                $input['language'],
                $input['code']
            );

            // TODO: Ở đây sẽ có logic để chạy test cases và cập nhật kết quả
            // Hiện tại chỉ đơn giản set status là accepted
            $this->practiceModel->updateSubmissionStatus($submissionId, 'accepted');

            echo json_encode([
                'success' => true, 
                'submission_id' => $submissionId,
                'message' => 'Code submitted successfully'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error submitting code']);
        }
    }
}