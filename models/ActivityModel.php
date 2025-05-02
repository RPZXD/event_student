<?php
namespace App\Models;

use App\DatabaseEvent;
use App\DatabaseUsers;
use PDO;

class ActivityModel
{
    private $pdo;
    private $dbEvent;
    private $dbUsers;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->dbEvent = new DatabaseEvent($pdo);
        $this->dbUsers = new DatabaseUsers($pdo);
    }

    public function getActivitiesByTeacher($teacherId)
    {
        $sql = "SELECT id, title FROM activities WHERE teacher_id = :teacher_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['teacher_id' => $teacherId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: []; // Return an empty array if no results
    }

    public function getParticipantsByActivity($activityId)
    {
        $sql = "SELECT name, email, registered_at FROM participants WHERE activity_id = :activity_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['activity_id' => $activityId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: []; // Return an empty array if no results
    }
}
