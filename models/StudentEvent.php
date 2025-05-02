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
        require_once __DIR__ . '/TermPee.php';

        $sql = "SELECT a.*, a.teacher_id
                FROM student_activity_logs sal
                INNER JOIN activities a ON sal.activity_id = a.id
                WHERE sal.student_id = ?";
        $params = [$student_id];

        // เลือกทั้งปีและเทอม
        if ($pee !== null && $pee !== '' && $term !== null && $term !== '') {
            $sql .= " AND a.pee = ? AND a.term = ?";
            $params[] = $pee;
            $params[] = $term;
        }
        // เลือกแค่ปี (และ term ว่าง)
        else if ($pee !== null && $pee !== '' && ($term === null || $term === '')) {
            $sql .= " AND a.pee = ?";
            $params[] = $pee;
        }
        // เลือกแค่เทอม (และ pee ว่าง)
        else if (($pee === null || $pee === '') && $term !== null && $term !== '') {
            $sql .= " AND a.term = ?";
            $params[] = $term;
        }
        // ไม่เลือกอะไรเลย: ไม่ต้องเพิ่มเงื่อนไข

        $sql .= " ORDER BY a.event_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // เติมชื่อครูจาก DatabaseUsers
        foreach ($events as &$ev) {
            $teacher = $this->dbUsers->getTeacherByUsername($ev['teacher_id']);
            $ev['teacher_name'] = $teacher && isset($teacher['Teach_name']) ? $teacher['Teach_name'] : $ev['teacher_id'];
        }
        unset($ev);

        return $events;
    }
}
