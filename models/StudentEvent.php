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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($events as &$ev) {
            $teacher = $this->dbUsers->getTeacherByUsername($ev['teacher_id']);
            $ev['teacher_name'] = $teacher['Teach_name'] ?? $ev['teacher_id'];
        }
        unset($ev);

        return $events;
    }
}
