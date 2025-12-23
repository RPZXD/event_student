<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'นักเรียน') {
    header('Location: ../login.php');
    exit;
}

// Read configuration from JSON file
$config = json_decode(file_get_contents('../config.json'), true);
$global = $config['global'];

// Set page title
$pageTitle = 'ลงทะเบียนกิจกรรม';

// Capture the view content
ob_start();
include '../views/student/event_regis.php';
$content = ob_get_clean();

// Include the layout
include '../views/layouts/student.php';
