<?php
namespace App\Models;

use PDO;

class ActivityModel
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

    // ดึงกิจกรรมที่ครูสร้าง
    public function getActivitiesByTeacher($teacherId)
    {
        $sql = "SELECT id, title FROM activities WHERE teacher_id = :teacherId ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['teacherId' => $teacherId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // ดึงรายชื่อนักเรียนที่ลงทะเบียนในกิจกรรม
    public function getParticipantsByActivity($activityId)
    {
        // ดึง student_id และ checked_in_at จากฐานข้อมูล event
        $sql = "SELECT student_id, checked_in_at
                FROM student_activity_logs
                WHERE activity_id = :activityId
                ORDER BY checked_in_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['activityId' => $activityId]);
        $logs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $participants = [];
        foreach ($logs as $log) {
            // ดึงข้อมูลนักเรียนจากฐานข้อมูล users
            $student = $this->dbUsers->getStudentByUsername($log['student_id']);
            if ($student) {
                $participants[] = [
                    'student_id' => $log['student_id'],
                    'Stu_name' => $student['Stu_pre'].$student['Stu_name'] . ' ' . $student['Stu_sur'],
                    'Stu_class' => 'ม.'.$student['Stu_major']. '/' .$student['Stu_room'],
                    'checked_in_at' => $log['checked_in_at']
                ];
            }
        }
        return $participants;
    }
}
