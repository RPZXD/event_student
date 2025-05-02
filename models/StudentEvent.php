<?php
namespace App\Models;

use PDO;

class StudentEvent
{
    protected $pdo;
    protected $dbUsers;

    public function __construct()
    {
        require_once __DIR__ . '/../classes/DatabaseEvent.php';
        require_once __DIR__ . '/../classes/DatabaseUsers.php';
        $db = new \App\DatabaseEvent();
        $this->pdo = $db->getPDO();
        $this->dbUsers = new \App\DatabaseUsers();
    }

    public function getRegisteredEvents($student_id, $pee = null, $term = null)
    {
        $sql = "SELECT a.*, a.teacher_id
                FROM student_activity_logs sal
                INNER JOIN activities a ON sal.activity_id = a.id
                WHERE sal.student_id = ?";
        $params = [$student_id];

        if (!empty($pee)) {
            $sql .= " AND a.pee = ?";
            $params[] = $pee;
        }
        if (!empty($term)) {
            $sql .= " AND a.term = ?";
            $params[] = $term;
        }

        $sql .= " ORDER BY a.event_date DESC";

        // Debugging: Log the SQL query and parameters
        error_log("SQL Query: " . $sql);
        error_log("Parameters: " . json_encode($params));

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Debugging: Log the fetched events
        error_log("Fetched Events: " . json_encode($events));

        // Additional debugging: Check if student_activity_logs has records for the student
        $debugSql = "SELECT * FROM student_activity_logs WHERE student_id = ?";
        $debugStmt = $this->pdo->prepare($debugSql);
        $debugStmt->execute([$student_id]);
        $debugLogs = $debugStmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Student Activity Logs: " . json_encode($debugLogs));

        // Additional debugging: Check if activities table has matching pee and term
        $debugSql = "SELECT * FROM activities WHERE pee = ? AND term = ?";
        $debugStmt = $this->pdo->prepare($debugSql);
        $debugStmt->execute([$pee, $term]);
        $debugActivities = $debugStmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Matching Activities: " . json_encode($debugActivities));

        foreach ($events as &$ev) {
            $teacher = $this->dbUsers->getTeacherByUsername($ev['teacher_id']);
            $ev['teacher_name'] = $teacher['Teach_name'] ?? $ev['teacher_id'];
        }
        unset($ev);

        return $events;
    }
}
