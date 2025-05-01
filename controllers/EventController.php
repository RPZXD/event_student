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

// สร้างกิจกรรมใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;

    // ตรวจสอบ session
    if (!isset($_SESSION['username'])) {
        echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบใหม่']);
        exit;
    }

    // เตรียมข้อมูล
    $data = [
        'title' => trim($input['title'] ?? ''),
        'description' => trim($input['description'] ?? ''),
        'event_date' => $input['event_date'] ?? '',
        'hours' => intval($input['hours'] ?? 0),
        'category' => trim($input['category'] ?? ''),
        'teacher_id' => $_SESSION['username'],
        'max_students' => intval($input['max_students'] ?? 0)
    ];

    // Validate
    if (!$data['title'] || !$data['event_date'] || !$data['hours'] || !$data['teacher_id']) {
        echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
        exit;
    }

    try {
        // 1. สร้างกิจกรรม
        $activity_id = $EventModel->createEvent($data);

        // 2. สร้างรหัสกิจกรรม (QR/Barcode)
        $code = 'EVT' . strtoupper(substr(md5(uniqid()), 0, 8));
        $EventModel->createEventCode($activity_id, $code);

        echo json_encode([
            'success' => true,
            'activity_id' => $activity_id,
            'code' => $code
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
            $eventList[] = $ev;
        }

        echo json_encode(['success' => true, 'events' => $eventList]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// ดึง code ล่าสุดของกิจกรรม (สำหรับ QR Code)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['activity_id'])) {
    $activity_id = intval($_GET['activity_id']);
    $sql = "SELECT code FROM activity_codes WHERE activity_id = ? ORDER BY created_at DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$activity_id]);
    $row = $stmt->fetch();
    echo json_encode(['code' => $row ? $row['code'] : null]);
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


