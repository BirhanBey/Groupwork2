<?php

class Db
{

    // Database configuration
    private $host = 'ID396978_todoList.db.webhosting.be';
    private $dbname = 'ID396978_todoList';
    private $username = 'ID396978_todoList';
    private $password = 'marouan123';
    private $port = 3306;
    private $pdo;

    // Create a new PDO instance
    public function __construct()
    {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO(
                    "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                    $this->username,
                    $this->password,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    )
                );
            } catch (PDOException $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }
    }

    public function executeQuery($sql, $filters = [], $fetch = PDO::FETCH_OBJ)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($filters);
            return $stmt->fetchAll($fetch);
        } catch (PDOException $e) {
            throw new Exception("Query execution failed: " . $e->getMessage());
        }
    }
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
