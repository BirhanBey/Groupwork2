<?php

require 'Db.class.php';

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



    // Get All Todo lists //
    public function getAllLists()
    {
        $sql = "SELECT TodoList.*, Todo.*
            FROM TodoList
            LEFT JOIN Todo ON TodoList.idTodoList = Todo.TodoList_id";
        $result = $this->db->executeQuery($sql);

        $lists = array();
        foreach ($result as $row) {
            $list_id = $row->idTodoList;
            $todo = array(
                'id' => $row->id,
                'description' => $row->description,
                'isCompleted' => $row->isCompleted
            );

            if (!isset($lists[$list_id])) {
                $lists[$list_id] = array(
                    'id' => $row->idTodoList,
                    'name' => $row->name,
                    'todos' => array()
                );
            } else {
                $lists[$list_id]['todos'][] = $todo;
            }
        }

        return array_values($lists);
    }







    // get list by Id // 
    public function getListById($id)
    {
        // Retrieve the TodoList by ID
        $sql = "SELECT * FROM TodoList WHERE idTodoList = :id";
        $filters = array('id' => $id);
        $result = $this->db->executeQuery($sql, $filters);

        // Check if TodoList exists
        if (!empty($result)) {
            $todoList = $result[0];

            // Retrieve the Todos for the TodoList
            $sql = "SELECT * FROM Todo WHERE TodoList_id = :id";
            $filters = array('id' => $id);
            $result = $this->db->executeQuery($sql, $filters);

            // Add the Todos to the TodoList
            $todoList->todos = $result;

            // Return the TodoList with Todos
            return $todoList;
        } else {
            // Return the name of the TodoList if it doesn't have any Todos
            $sql = "SELECT name FROM TodoList WHERE idTodoList = :id";
            $filters = array('id' => $id);
            $result = $this->db->executeQuery($sql, $filters);
            return $result[0]->name;
        }
    }




    public function addTodoList($name)
    {
        $sql = "INSERT INTO TodoList (name) VALUES (:name)";
        $filters = array('name' => $name);
        $result = $this->db->executeQuery($sql, $filters);

        if ($result) {
            return $this->db->getLastInsertId();
        } else {
            return false;
        }
    }

    public function getTodoById($id)
    {
        $sql = "SELECT * FROM Todo WHERE id = :id";
        $filters = array('id' => $id);
        $result = $this->db->executeQuery($sql, $filters);
        return $result ? $result[0] : null;
    }



    public function deleteTodoList($idTodoList)
    {
        // Delete the child records from the Todo table
        $sql = "DELETE FROM Todo WHERE TodoList_id = :idTodoList";
        $filters = array('idTodoList' => $idTodoList);
        $result = $this->db->executeQuery($sql, $filters);

        // Delete the parent record from the TodoList table
        $sql = "DELETE FROM TodoList WHERE idTodoList = :idTodoList";
        $filters = array('idTodoList' => $idTodoList);
        $result = $this->db->executeQuery($sql, $filters);

        return $result !== false; // return true if deletion was successful, false otherwise
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

        return $result ? $this->db->getLastInsertId() : null;
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

        // Return the updated status of the todo
        return $newStatus;
    }
    public function deleteTodoById($id)
    {
        $sql = "DELETE FROM Todo WHERE id = :id";
        $filters = array('id' => $id);
        $result = $this->db->executeQuery($sql, $filters);
        return $result;
    }
}
