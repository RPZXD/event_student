<?php
session_start();

header('Content-Type: application/json');


require_once __DIR__ . '/../classes/DatabaseEvent.php';
require_once __DIR__ . '/../classes/DatabaseUsers.php';
require_once __DIR__ . '/../models/StudentEvent.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/TermPee.php';

use App\DatabaseEvent;
use App\DatabaseUsers;
use App\Models\StudentEvent; // Fix namespace for StudentEvent

$db = new DatabaseEvent();
$dbUsers = new DatabaseUsers();
$pdo = $db->getPDO();
$StudentEventModel = new StudentEvent(); // Correct instantiation

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Action: Fetch terms and pees
    if (isset($_GET['action']) && $_GET['action'] === 'terms_pees') {
        try {
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

    // Action: Fetch registered events for a student
    $student_id = $_SESSION['user']['Stu_id'] ?? $_SESSION['username'] ?? null;

    if (!$student_id) {
        echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบ']);
        exit;
    }

    $term = isset($_GET['term']) ? trim($_GET['term']) : '';
    $pee = isset($_GET['pee']) ? trim($_GET['pee']) : '';

    try {
        $events = $StudentEventModel->getRegisteredEvents($student_id, $pee, $term);
        echo json_encode(['success' => true, 'events' => $events]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching events: ' . $e->getMessage()]);
    }
    exit;
}
