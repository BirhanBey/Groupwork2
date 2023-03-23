<?php
require_once 'Db.class.php';

class Todos
{
    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function getTodoStatus($id)
    {
        $sql = "SELECT isCompleted FROM Todo WHERE id = :id";
        $filters = array('id' => $id);

        $result = $this->db->executeQuery($sql, $filters);
        if (!empty($result)) {
            return $result[0]->isCompleted;
        } else {
            return null;
        }
    }

    public function addTodo($idTodoList, $description)
    {
        // Generate a timestamp for the createdAt field
        $createdAt = date('Y-m-d');

        // Set isCompleted to false by default
        $isCompleted = false;

        $sql = "INSERT INTO Todo (TodoList_id, description, isCompleted, createdAt) VALUES (:idTodoList, :description, :isCompleted, :createdAt)";
        $filters = array(
            'idTodoList' => $idTodoList,
            'description' => $description,
            'isCompleted' => $isCompleted,
            'createdAt' => $createdAt
        );
        $result = $this->db->executeQuery($sql, $filters);


        return $result === false  ?  null : $this->db->getLastInsertId();
    }

    public function updateTodoStatus($id)
    {
        // Get the current status of the todo
        $isCompleted = $this->getTodoStatus($id);

        // Toggle the status of the todo
        $newStatus = $isCompleted ? 0 : 1;

        // Update the status of the todo in the database
        $sql = "UPDATE Todo SET isCompleted = :newStatus WHERE id = :id";
        $filters = array('id' => $id, 'newStatus' => $newStatus);
        $result = $this->db->executeQuery($sql, $filters);

        return $result !== false;
    }

    public function getTodoById($id)
    {
        $sql = "SELECT * FROM Todo WHERE id = :id";
        $filters = array('id' => $id);
        $result = $this->db->executeQuery($sql, $filters);
        return $result ? $result[0] : null;
    }

    public function deleteTodoById($id)
    {
        // Delete the todo from the database
        $sql = "DELETE FROM Todo WHERE id = :id";
        $filters = array('id' => $id);
        $result = $this->db->executeQuery($sql, $filters);

        return $result !== false;
    }
}
