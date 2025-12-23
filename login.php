<?php
/**
 * Login Page - Student Event Registration System
 * Using views structure
 */
session_start();

// โหลด config
$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);
$global = $config['global'];

// ตัวแปรสำหรับ error/success messages
$error = null;
$success = false;
$redirectUrl = 'index.php';

// Process login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once __DIR__ . '/controllers/LoginController.php';
    
    $input_username = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? '';
    $input_role = $_POST['role'] ?? '';

    $controller = new LoginController();
    $result = $controller->login($input_username, $input_password, $input_role);
    
    if ($result === 'success') {
        $success = true;
        // Redirect by role
        if ($input_role === 'ครู') {
            $redirectUrl = 'teacher/index.php';
        } else if ($input_role === 'นักเรียน') {
            $redirectUrl = 'student/index.php';
        } else if ($input_role === 'admin') {
            $redirectUrl = 'admin/index.php';
        }
    } else {
        $error = $result;
    }
}

// Check for logout message
$logoutSuccess = isset($_GET['logout']) && $_GET['logout'] == '1';

// Set page title
$pageTitle = 'เข้าสู่ระบบ';

// Start output buffering for content
ob_start();
include __DIR__ . '/views/auth/login.php';
$content = ob_get_clean();

// Add SweetAlert scripts to content
$alertScripts = '';

if ($error) {
    $alertScripts .= "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'เข้าสู่ระบบไม่สำเร็จ',
            text: " . json_encode($error) . ",
            confirmButtonText: 'ปิด',
            confirmButtonColor: '#3b82f6'
        });
    });
    </script>";
}

if ($success) {
    $alertScripts .= "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'เข้าสู่ระบบสำเร็จ',
            text: 'กำลังเข้าสู่ระบบ...',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = " . json_encode($redirectUrl) . ";
        });
    });
    </script>";
}

if ($logoutSuccess) {
    $alertScripts .= "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'ออกจากระบบสำเร็จ',
            text: 'คุณได้ออกจากระบบเรียบร้อยแล้ว',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#3b82f6'
        });
    });
    </script>";
}

$content .= $alertScripts;

// Include the main layout
include __DIR__ . '/views/layouts/app.php';
