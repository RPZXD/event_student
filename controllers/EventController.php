<?php
session_start();

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../classes/DatabaseEvent.php';
require_once __DIR__ . '/../classes/DatabaseUsers.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/User.php';

use App\DatabaseEvent;
use App\DatabaseUsers;
use App\Models\Event;

$db = new DatabaseEvent();
$dbUsers = new DatabaseUsers();
$pdo = $db->getPDO(); // สมมติว่า DatabaseEvent มีเมธอด getPDO()
$EventModel = new Event($pdo);

// สร้างกิจกรรมใหม่ หรือ ลงทะเบียนกิจกรรม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;

    // ตรวจสอบ session
    if (!isset($_SESSION['username'])) {
        echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบใหม่']);
        exit;
    }

    // เช็คอินด้วย unique code
    if (isset($input['action']) && $input['action'] === 'register_activity') {
        $activity_id = intval($input['activity_id'] ?? 0);
        $code = trim($input['code'] ?? '');
        // รับ student_id จาก input ถ้ามี (สำหรับกรณีกรอกเอง)
        $student_id = isset($input['student_id']) && $input['student_id'] ? trim($input['student_id']) : ($_SESSION['user']['Stu_id'] ?? null);

        if (!$activity_id || !$code || !$student_id) {
            echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
            exit;
        }

        // ตรวจสอบ unique code
        $sql = "SELECT * FROM activity_unique_codes WHERE activity_id = ? AND code = ? AND is_used = 0 LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$activity_id, $code]);
        $codeRow = $stmt->fetch();

        if (!$codeRow) {
            echo json_encode(['success' => false, 'message' => 'โค้ดนี้ถูกใช้ไปแล้วหรือไม่ถูกต้อง']);
            exit;
        }

        // ตรวจสอบว่าลงทะเบียนไปแล้วหรือยัง
        $sql = "SELECT * FROM student_activity_logs WHERE student_id = ? AND activity_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id, $activity_id]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'คุณได้ลงทะเบียนกิจกรรมนี้แล้ว']);
            exit;
        }

        // บันทึกลง student_activity_logs และอัปเดต is_used
        $pdo->beginTransaction();
        try {
            // ตรวจสอบว่ามีคอลัมน์ unique_code_id หรือไม่
            $hasUniqueCodeId = false;
            $checkCol = $pdo->query("SHOW COLUMNS FROM student_activity_logs LIKE 'unique_code_id'");
            if ($checkCol && $checkCol->fetch()) {
                $hasUniqueCodeId = true;
            }

            if ($hasUniqueCodeId) {
                $sql = "INSERT INTO student_activity_logs (student_id, activity_id, checked_in_at, unique_code_id) VALUES (?, ?, NOW(), ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$student_id, $activity_id, $codeRow['id']]);
            } else {
                $sql = "INSERT INTO student_activity_logs (student_id, activity_id, checked_in_at) VALUES (?, ?, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$student_id, $activity_id]);
            }

            // อัปเดต activity_unique_codes: is_used, student_id, used_at
            $sql = "UPDATE activity_unique_codes SET is_used = 1, student_id = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$student_id, $codeRow['id']]);

            $pdo->commit();
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // สร้างกิจกรรมใหม่
    $data = [
        'title' => trim($input['title'] ?? ''),
        'description' => trim($input['description'] ?? ''),
        'event_date' => $input['event_date'] ?? '',
        'hours' => intval($input['hours'] ?? 0),
        'category' => trim($input['category'] ?? ''),
        'teacher_id' => $_SESSION['username'],
        'max_students' => intval($input['max_students'] ?? 0)
    ];

    // เพิ่มรับ expire_date (optional)
    $expire_date = isset($input['expire_date']) && $input['expire_date'] ? $input['expire_date'] : null;

    // Validate
    if (!$data['title'] || !$data['event_date'] || !$data['hours'] || !$data['teacher_id']) {
        echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
        exit;
    }

    try {
        // 1. สร้างกิจกรรม
        $activity_id = $EventModel->createEvent($data);

        // 2. ถ้า max_students > 0 ให้สร้าง unique codes
        $unique_codes = [];
        if ($data['max_students'] > 0) {
            // เพิ่ม expire_date ใน SQL
            $insertStmt = $pdo->prepare("INSERT INTO activity_unique_codes (activity_id, code, is_used, expire_date) VALUES (?, ?, 0, ?)");
            for ($i = 0; $i < $data['max_students']; $i++) {
                // สุ่มโค้ด 8 หลัก (ตัวอักษร+ตัวเลข)
                $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
                $insertStmt->execute([$activity_id, $code, $expire_date]);
                $unique_codes[] = $code;
            }
        }

        // 3. ส่งโค้ดแรกกลับไปสำหรับแสดง QR (หรือทั้งหมดถ้าต้องการ)
        echo json_encode([
            'success' => true,
            'activity_id' => $activity_id,
            'unique_codes' => $unique_codes,
            'code' => $unique_codes[0] ?? null // สำหรับแสดง QR
        ]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// ดึงข้อมูลกิจกรรมทั้งหมด
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['activity_id'])) {
    try {
        $sql = "SELECT * FROM activities ORDER BY created_at DESC";
        $stmt = $pdo->query($sql);
        $events = $stmt->fetchAll();

        // ใช้ models/User.php เพื่อดึงชื่อครู
        $eventList = [];
        foreach ($events as $ev) {
            $teacher_name = null;
            if (!empty($ev['teacher_id'])) {
                $teacher = User::getTeacherByUsername($ev['teacher_id']);
                $teacher_name = $teacher && isset($teacher['Teach_name']) ? $teacher['Teach_name'] : $ev['teacher_id'];
            }
            $ev['teacher_name'] = $teacher_name;

            // นับ current_students (จำนวนที่ลงทะเบียนแล้ว)
            $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM student_activity_logs WHERE activity_id = ?");
            $stmtCount->execute([$ev['id']]);
            $ev['current_students'] = (int)$stmtCount->fetchColumn();

            $eventList[] = $ev;
        }

        echo json_encode(['success' => true, 'events' => $eventList]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// ดึง code ล่าสุดของกิจกรรม (สำหรับ QR Code) หรือโค้ดทั้งหมด
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['activity_id'])) {
    $activity_id = intval($_GET['activity_id']);
    // ถ้า all_codes=1 ให้ดึงโค้ดทั้งหมด
    if (isset($_GET['all_codes'])) {
        $sql = "SELECT code, is_used FROM activity_unique_codes WHERE activity_id = ? ORDER BY id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$activity_id]);
        $codes = $stmt->fetchAll();
        echo json_encode(['codes' => $codes]);
        exit;
    }
    // ถ้าเป็นกิจกรรมที่มี max_students > 0 ให้ดึง unique code ที่ยังไม่ถูกใช้
    $sql = "SELECT max_students FROM activities WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$activity_id]);
    $activity = $stmt->fetch();
    if ($activity && $activity['max_students'] > 0) {
        // ดึง unique code ที่ยังไม่ถูกใช้ 1 รายการ
        $sql = "SELECT code FROM activity_unique_codes WHERE activity_id = ? AND is_used = 0 LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$activity_id]);
        $row = $stmt->fetch();
        echo json_encode(['code' => $row ? $row['code'] : null]);
    } else {
        // fallback: ใช้ code เดิม
        $sql = "SELECT code FROM activity_codes WHERE activity_id = ? ORDER BY created_at DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$activity_id]);
        $row = $stmt->fetch();
        echo json_encode(['code' => $row ? $row['code'] : null]);
    }
    exit;
}

// ดึง activity_id ที่นักเรียนลงทะเบียนแล้ว
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['student_registered'])) {
    // ตรวจสอบ session และดึง student_id ที่ถูกต้อง
    $student_id = null;
    if (isset($_SESSION['user']['Stu_id'])) {
        $student_id = $_SESSION['user']['Stu_id'];
    } elseif (isset($_SESSION['username'])) {
        // fallback กรณี session เก่า
        $student_id = $_SESSION['username'];
    }
    if (!$student_id) {
        echo json_encode(['registered' => []]);
        exit;
    }
    $sql = "SELECT activity_id FROM student_activity_logs WHERE student_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$student_id]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode(['registered' => array_map('intval', $ids)]);
    exit;
}

// ลบกิจกรรม
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id'] ?? 0);

    // ตรวจสอบสิทธิ์ (เฉพาะเจ้าของกิจกรรม)
    $sql = "SELECT * FROM activities WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $event = $stmt->fetch();
    if (!$event) {
        echo json_encode(['success' => false, 'message' => 'ไม่พบกิจกรรม']);
        exit;
    }
    if (!isset($_SESSION['username']) || $event['teacher_id'] != $_SESSION['username']) {
        echo json_encode(['success' => false, 'message' => 'ไม่มีสิทธิ์ลบกิจกรรมนี้']);
        exit;
    }

    // ลบกิจกรรมและ code ที่เกี่ยวข้อง
    try {
        $pdo->beginTransaction();
        $pdo->prepare("DELETE FROM activity_codes WHERE activity_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM activity_unique_codes WHERE activity_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM activities WHERE id = ?")->execute([$id]);
        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// แก้ไขกิจกรรม
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id'] ?? 0);

    // ตรวจสอบสิทธิ์ (เฉพาะเจ้าของกิจกรรม)
    $sql = "SELECT * FROM activities WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $event = $stmt->fetch();
    if (!$event) {
        echo json_encode(['success' => false, 'message' => 'ไม่พบกิจกรรม']);
        exit;
    }
    if (!isset($_SESSION['username']) || $event['teacher_id'] != $_SESSION['username']) {
        echo json_encode(['success' => false, 'message' => 'ไม่มีสิทธิ์แก้ไขกิจกรรมนี้']);
        exit;
    }

    // อัปเดตข้อมูล
    try {
        $sql = "UPDATE activities SET title = :title, description = :description, event_date = :event_date, hours = :hours, category = :category, max_students = :max_students WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $input['title'],
            ':description' => $input['description'],
            ':event_date' => $input['event_date'],
            ':hours' => $input['hours'],
            ':category' => $input['category'],
            ':max_students' => $input['max_students'],
            ':id' => $id
        ]);
        echo json_encode(['success' => true]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}


