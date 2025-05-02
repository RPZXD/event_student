<?php
session_start();

header('Content-Type: application/json');


require_once __DIR__ . '/../classes/DatabaseEvent.php';
require_once __DIR__ . '/../classes/DatabaseUsers.php';
require_once __DIR__ . '/../models/ActivityModel.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/TermPee.php';

use App\DatabaseEvent;
use App\DatabaseUsers;
use App\Models\ActivityModel; 

$db = new DatabaseEvent();
$dbUsers = new DatabaseUsers();
$pdo = $db->getPDO();
$Activity = new ActivityModel();

if (isset($_GET['action']) && $_GET['action'] === 'teacher_activities') {
    // สมมติว่า session เก็บ Teach_id ของครูไว้ใน $_SESSION['username']
    $teacher = $dbUsers->getTeacherByUsername($_SESSION['username']);
    if (!$teacher) {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลครู']);
        exit;
    }
    $activities = $Activity->getActivitiesByTeacher($teacher['Teach_id']);
    echo json_encode(['success' => true, 'activities' => $activities]);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'participants') {
    $activityId = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;
    if (!$activityId) {
        echo json_encode(['success' => false, 'message' => 'ไม่พบ activity_id']);
        exit;
    }
    $participants = $Activity->getParticipantsByActivity($activityId);
    echo json_encode(['success' => true, 'participants' => $participants]);
    exit;
}



