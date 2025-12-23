<?php 
session_start();

// เช็ค session และ role
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'ครู') {
    header('Location: ../login.php');
    exit;
}

// Read configuration from JSON file
$config = json_decode(file_get_contents('../config.json'), true);
$global = $config['global'];

// Set page title
$pageTitle = 'สร้างกิจกรรม - Teacher Portal';

// Start output buffering for content
ob_start();
include __DIR__ . '/../views/teacher/create-event.php';
$content = ob_get_clean();

// Include the teacher layout
include __DIR__ . '/../views/layouts/teacher.php';
