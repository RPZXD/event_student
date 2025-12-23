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

require_once('../classes/DatabaseEvent.php');
use App\DatabaseEvent;

// ตรวจสอบ activity_id
if (!isset($_GET['activity_id'])) {
    header('Location: create_event.php');
    exit;
}

$activity_id = $_GET['activity_id'];

// สร้างการเชื่อมต่อฐานข้อมูล
$db = new DatabaseEvent();
$pdo = $db->getPDO();

// ดึงข้อมูลกิจกรรม
$stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ? AND teacher_id = ?");
$stmt->execute([$activity_id, $_SESSION['username']]);
$activity = $stmt->fetch();

if (!$activity) {
    header('Location: create_event.php');
    exit;
}

// Pagination settings
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 50; // แสดง 50 รายการต่อหน้า
$offset = ($page - 1) * $limit;

// นับจำนวนโค้ดทั้งหมด
$stmt = $pdo->prepare("SELECT COUNT(*) FROM activity_unique_codes WHERE activity_id = ?");
$stmt->execute([$activity_id]);
$totalCodes = $stmt->fetchColumn();
$totalPages = ceil($totalCodes / $limit);

// ดึงรายการโค้ดแบบ pagination
$sql = "SELECT * FROM activity_unique_codes WHERE activity_id = ? ORDER BY id LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute([$activity_id]);
$codes = $stmt->fetchAll();

// Set page title
$pageTitle = 'QR Code - ' . htmlspecialchars($activity['title']);

// Start output buffering for content
ob_start();
include __DIR__ . '/../views/teacher/qr-code.php';
$content = ob_get_clean();

// Include the teacher layout
include __DIR__ . '/../views/layouts/teacher.php';
