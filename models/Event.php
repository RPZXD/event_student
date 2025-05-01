<?php
namespace App\Models;

use PDO;
use PDOException;

class Event
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // สร้างกิจกรรมใหม่
    public function createEvent($data)
    {
        $sql = "INSERT INTO activities (title, description, event_date, hours, category, teacher_id, created_at, max_students)
                VALUES (:title, :description, :event_date, :hours, :category, :teacher_id, NOW(), :max_students)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':event_date' => $data['event_date'],
            ':hours' => $data['hours'],
            ':category' => $data['category'],
            ':teacher_id' => $data['teacher_id'],
            ':max_students' => $data['max_students']
        ]);
        return $this->pdo->lastInsertId();
    }

    // สร้างรหัสกิจกรรม (QR/Barcode)
    public function createEventCode($activity_id, $code, $expires_at = null)
    {
        $sql = "INSERT INTO activity_codes (activity_id, code, expires_at, created_at)
                VALUES (:activity_id, :code, :expires_at, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':activity_id' => $activity_id,
            ':code' => $code,
            ':expires_at' => $expires_at
        ]);
        return $this->pdo->lastInsertId();
    }
}
