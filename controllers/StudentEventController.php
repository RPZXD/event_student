<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/StudentEvent.php';

if (isset($_GET['action']) && $_GET['action'] === 'terms_pees') {
    try {
        require_once __DIR__ . '/../classes/DatabaseEvent.php';
        $db = new \App\DatabaseEvent();
        $pdo = $db->getPDO();
        $stmt = $pdo->query("SELECT DISTINCT term FROM activities ORDER BY term DESC");
        $terms = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $stmt = $pdo->query("SELECT DISTINCT pee FROM activities ORDER BY pee DESC");
        $pees = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode(['success' => true, 'terms' => $terms, 'pees' => $pees]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching terms and pees: ' . $e->getMessage()]);
    }
    exit;
}

$student_id = $_SESSION['user']['Stu_id'] ?? $_SESSION['username'] ?? null;

if (!$student_id) {
    echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบ']);
    exit;
}

// รับค่าจาก GET
$term = isset($_GET['term']) ? trim($_GET['term']) : '';
$pee = isset($_GET['pee']) ? trim($_GET['pee']) : '';

$model = new \App\Models\StudentEvent();
try {
    $events = $model->getRegisteredEvents($student_id, $pee, $term);
    echo json_encode(['success' => true, 'events' => $events]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching events: ' . $e->getMessage()]);
}
