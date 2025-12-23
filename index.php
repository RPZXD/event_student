<?php 
/**
 * Index Page - Student Event Registration System
 * Using views structure
 */

// Set page title
$pageTitle = 'ระบบลงทะเบียนกิจกรรมนักเรียน';

// Start output buffering for content
ob_start();
include __DIR__ . '/views/home/index.php';
$content = ob_get_clean();

// Include the main layout
include __DIR__ . '/views/layouts/app.php';
