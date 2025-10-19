document.addEventListener('DOMContentLoaded', () => {
    // === 1. KHAI BÁO BIẾN ===
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    // Thay đổi selector để chỉ chọn các section trong content
    const sections = document.querySelectorAll('.content .page-section');
    const surveyForm = document.getElementById('survey-form');
    const startSurveyBtn = document.getElementById('start-survey-btn');
    const customModal = document.getElementById('custom-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const goToExercisesLink = document.getElementById('go-to-exercises');
    const logoutBtn = document.getElementById('logout-btn');
    const submitSurveyBtn = document.getElementById('submit-survey-btn');
    const errorModal = document.getElementById('error-modal');
    const closeErrorModalBtn = document.getElementById('close-error-modal-btn');
    const statusModal = document.getElementById('status-modal');
    const statusTitle = document.getElementById('status-title');
    const statusMessage = document.getElementById('status-message');
    const closeStatusModalBtn = document.getElementById('close-status-modal-btn');

    let myChart = null;

    // Lấy userId và username từ các thẻ meta hoặc biến JS được in ra bởi PHP
    // Vì chúng ta đang dùng PHP, chúng ta sẽ dựa vào data-attributes hoặc biến toàn cục.
    // Tạm thời lấy từ session_id() và biến PHP đã được home.php cũ in ra.
    const userId = '<?php echo $_SESSION["user_id"] ?? "guest"; ?>'; // Giả định PHP in biến này
    const username = '<?php echo htmlspecialchars($_SESSION["username"] ?? "Khách"); ?>'; // Giả định PHP in biến này

    // === 2. HÀM CHUNG ===

    // Hàm hiển thị section
    function showSection(targetId) {
        sections.forEach((section) => {
            section.classList.add('hidden');
        });
        document.querySelector(targetId).classList.remove('hidden');
    }

    // Hàm để kiểm tra trạng thái của form khảo sát (Giữ nguyên logic tốt của bạn)
    const checkFormValidity = () => {
        const radios = surveyForm.querySelectorAll('input[type="radio"]');
        const uniqueNames = new Set();
        radios.forEach((radio) => uniqueNames.add(radio.name));

        let allAnswered = true;
        uniqueNames.forEach((name) => {
            const group = document.querySelector(`input[name="${name}"]:checked`);
            if (!group) {
                allAnswered = false;
            }
        });

        if (allAnswered) {
            submitSurveyBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitSurveyBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            submitSurveyBtn.disabled = false;
        } else {
            submitSurveyBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            submitSurveyBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            submitSurveyBtn.disabled = true;
        }
    };

    s; // Hàm hiển thị modal thông báo
    const showStatusModal = (title, message) => {
        statusTitle.textContent = title;
        statusMessage.textContent = message;
        statusModal.classList.remove('hidden');
    };

    // === 3. XỬ LÝ SỰ KIỆN ===

    // Xử lý chuyển đổi tab Sidebar (Tối ưu hóa)
    sidebarLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            // Lấy ID section từ href, ví dụ: "#survey" -> "survey"
            const targetId = link.getAttribute('href').substring(1);

            // Nếu là liên kết nội bộ (#), xử lý chuyển đổi giao diện
            if (targetId) {
                showSection(`#${targetId}`);
                sidebarLinks.forEach((l) => l.classList.remove('active'));
                link.classList.add('active');
            }
            // Nếu là liên kết Router (/logout), trình duyệt sẽ xử lý
        });
    });

    // Xử lý nút "Làm khảo sát AQ" trên trang chủ
    if (startSurveyBtn) {
        startSurveyBtn.addEventListener('click', () => {
            // Sử dụng hàm showSection đã tối ưu
            showSection('#survey');
            sidebarLinks.forEach((l) => l.classList.remove('active'));
            document.querySelector('.sidebar-link[href="/survey"]').classList.add('active');
        });
    }

    // Lắng nghe sự kiện thay đổi trên các radio button (Khảo sát)
    surveyForm.addEventListener('change', checkFormValidity);
    // Khởi tạo trạng thái ban đầu
    checkFormValidity();

    // Xử lý form khảo sát (Gửi AJAX đến Controller Component)
    if (surveyForm) {
        surveyForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (submitSurveyBtn.disabled) {
                errorModal.classList.remove('hidden');
                return;
            }

            const formData = new FormData(surveyForm);
            const coreScores = {
                control: parseInt(formData.get('control')) || 0,
                ownership: parseInt(formData.get('ownership')) || 0,
                reach: parseInt(formData.get('reach')) || 0,
                endurance: parseInt(formData.get('endurance')) || 0
            };

            // ... (Phần code hiển thị biểu đồ radar và thanh tiến trình giữ nguyên) ...

            // Hiển thị hộp thoại tùy chỉnh
            customModal.classList.remove('hidden');

            // Gửi dữ liệu đến máy chủ để lưu vào CSDL
            try {
                // SỬA: Gọi đến Router Component /save-survey thay vì save_survey.php
                const response = await fetch('/save-survey', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        userId: userId,
                        username: username,
                        coreScores: coreScores
                    })
                });

                const result = await response.json();
                if (result.success) {
                    surveyForm.reset();
                    document.getElementById('results').classList.add('hidden');
                    document.getElementById('control-score').textContent = '0';
                    document.getElementById('ownership-score').textContent = '0';
                    document.getElementById('reach-score').textContent = '0';
                    document.getElementById('endurance-score').textContent = '0';
                    showStatusModal('Thành công!', 'Kết quả khảo sát của bạn đã được lưu thành công.');
                } else {
                    showStatusModal('Lỗi!', `Không thể lưu kết quả. Lỗi: ${result.message}`);
                }
            } catch (error) {
                showStatusModal('Lỗi!', 'Không thể kết nối với máy chủ để lưu dữ liệu. Vui lòng thử lại sau.');
                console.error('Lỗi khi gửi dữ liệu:', error);
            }
        });
    }

    // Xử lý nút đóng/mở Modal (Giữ nguyên)
    if (closeModalBtn) closeModalBtn.addEventListener('click', () => customModal.classList.add('hidden'));
    if (closeErrorModalBtn) closeErrorModalBtn.addEventListener('click', () => errorModal.classList.add('hidden'));
    if (closeStatusModalBtn) closeStatusModalBtn.addEventListener('click', () => statusModal.classList.add('hidden'));

    // Xử lý nút đăng xuất (Tối ưu hóa)
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            window.location.href = '/logout'; // Chuyển hướng về Router /logout
        });
    }

    // ... Các xử lý Modal khác ...
});
