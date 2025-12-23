<?php 
session_start();

// Check if user is logged in as student
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'นักเรียน') {
    header('Location: ../login.php');
    exit;
}

// Read configuration from JSON file
$config = json_decode(file_get_contents('../config.json'), true);
$global = $config['global'];

// Set page title
$pageTitle = 'กิจกรรมที่ลงทะเบียน';

// Get student data from session
$user = $_SESSION['user'];
$term = isset($_SESSION['term']) ? $_SESSION['term'] : '-';
$pee = isset($_SESSION['pee']) ? $_SESSION['pee'] : '-';

// Capture the view content
ob_start();
include __DIR__ . '/../views/student/event_list.php';
$content = ob_get_clean();

// Include the layout
include __DIR__ . '/../views/layouts/student.php';
