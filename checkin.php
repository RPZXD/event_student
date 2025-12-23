<?php
/**
 * Checkin Page - Student Event Registration System
 * Using views structure
 */
session_start();

// รับรหัสกิจกรรมจาก query string
$code = isset($_GET['code']) ? trim($_GET['code']) : '';

// Read configuration from JSON file
$config = json_decode(file_get_contents('config.json'), true);
$global = $config['global'];

// ดึงค่า term และ pee จาก session
$term = isset($_SESSION['term']) ? $_SESSION['term'] : '-';
$pee = isset($_SESSION['pee']) ? $_SESSION['pee'] : '-';

// เตรียมตัวแปรสำหรับรายละเอียดกิจกรรม
$activity = null;
$activity_id = null;
$already_registered = false;
$can_register = false;
$thaiDate = '';
$student_id = $_SESSION['user']['Stu_id'] ?? $_SESSION['username'] ?? null;

if ($code) {
    // 1. ตรวจสอบ code ว่าตรงกับกิจกรรมใด
    require_once('classes/DatabaseEvent.php');
    $db = new \App\DatabaseEvent();
    $pdo = $db->getPDO();

    // หา activity_id จาก code (unique หรือ code ปกติ)
    // ป้องกัน error ถ้าไม่มีตาราง activity_codes
    try {
        // ตรวจสอบว่าตาราง activity_codes มีอยู่หรือไม่
        $hasActivityCodes = false;
        $checkTable = $pdo->query("SHOW TABLES LIKE 'activity_codes'");
        if ($checkTable && $checkTable->fetch()) {
            $hasActivityCodes = true;
        }

        if ($hasActivityCodes) {
            $sql = "SELECT a.*, auc.activity_id AS unique_activity_id
                FROM activities a
                LEFT JOIN activity_unique_codes auc ON auc.code = ?
                WHERE a.id = auc.activity_id OR a.id IN (SELECT activity_id FROM activity_codes WHERE code = ?)
                LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$code, $code]);
        } else {
            // ไม่มี activity_codes ให้หาเฉพาะจาก unique_codes
            $sql = "SELECT a.*, auc.activity_id AS unique_activity_id
                FROM activities a
                LEFT JOIN activity_unique_codes auc ON auc.code = ?
                WHERE a.id = auc.activity_id
                LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$code]);
        }
        $activity = $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        $activity = null;
    }

    if ($activity) {
        $activity_id = $activity['id'];
        
        // ตรวจสอบว่านักเรียนลงทะเบียนกิจกรรมนี้แล้วหรือยัง
        if ($student_id) {
            $stmt2 = $pdo->prepare("SELECT 1 FROM student_activity_logs WHERE student_id = ? AND activity_id = ?");
            $stmt2->execute([$student_id, $activity_id]);
            $already_registered = $stmt2->fetch() ? true : false;
        }

        // Thai date formatting
        function thaiDate($dateStr) {
            $months = [
                "", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
            ];
        
            $timestamp = strtotime($dateStr);
            $day = date('j', $timestamp);
            $month = $months[date('n', $timestamp)];
            $year = date('Y', $timestamp) + 543;
        
            return "$day $month $year";
        }
        
        $thaiDate = thaiDate($activity['event_date']);

        // เงื่อนไขแสดงปุ่มลงทะเบียน
        if (!$already_registered) {
            $can_register = true;
        }
    }
}

// Set page title
$pageTitle = 'เข้าร่วมกิจกรรม';

// Start output buffering for content
ob_start();
include __DIR__ . '/views/checkin/index.php';
$content = ob_get_clean();

// Include the main layout
include __DIR__ . '/views/layouts/app.php';
