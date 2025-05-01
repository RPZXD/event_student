<?php
namespace App;

use PDO;
use PDOException;

class DatabaseEvent
{
    private $pdo;

    public function __construct(
        $host = 'localhost',
        $dbname = 'phichaia_event',
        // $username = 'root',
        // $password = ''
        $username = 'phichaia_eventstd',
        $password = 'hi5D@z310'
    ) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new \Exception('Database query error: ' . $e->getMessage());
        }
    }


    public function getPDO()
    {
        return $this->pdo;
    }
}
