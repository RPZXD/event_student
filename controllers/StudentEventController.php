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
        // Debugging: Log the input parameters
        error_log("Student ID: " . $student_id);
        error_log("Term: " . $term);
        error_log("Pee: " . $pee);

        // Query directly without using the model
        $sql = "SELECT a.*, a.teacher_id
                FROM student_activity_logs sal
                INNER JOIN activities a ON sal.activity_id = a.id
                WHERE sal.student_id = ?";
        $params = [$student_id];

        if ($pee !== null && $pee !== '') {
            $sql .= " AND a.pee = ?";
            $params[] = $pee;
        }
        if ($term !== null && $term !== '') {
            $sql .= " AND a.term = ?";
            $params[] = $term;
        }
        $sql .= " ORDER BY a.event_date DESC";

        error_log("Direct SQL: " . $sql);
        error_log("Direct Params: " . json_encode($params));

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        error_log("Direct Events: " . json_encode($events));

        // Optionally, enrich with teacher name if needed
        foreach ($events as &$ev) {
            $teacher = $dbUsers->getTeacherByUsername($ev['teacher_id']);
            $ev['teacher_name'] = $teacher['Teach_name'] ?? $ev['teacher_id'];
        }
        unset($ev);

        echo json_encode(['success' => true, 'events' => $events]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching events: ' . $e->getMessage()]);
    }
    exit;
}
