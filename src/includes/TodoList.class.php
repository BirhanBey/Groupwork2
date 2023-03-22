<?php

require_once 'Db.class.php';

class TodoList
{
    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

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

    public function getListById($id)
    {
        $sql = "SELECT * FROM TodoList WHERE idTodoList = :id";
        $filters = array('id' => $id);
        $result = $this->db->executeQuery($sql, $filters);

        if (!empty($result)) {
            $todoList = $result[0];

            $sql = "SELECT * FROM Todo WHERE TodoList_id = :id";
            $filters = array('id' => $id);
            $result = $this->db->executeQuery($sql, $filters);

            $todoList->todos = $result;

            return $todoList;
        } else {
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

    public function deleteTodoList($idTodoList)
    {
        $sql = "DELETE FROM Todo WHERE TodoList_id = :idTodoList";
        $filters = array('idTodoList' => $idTodoList);
        $result = $this->db->executeQuery($sql, $filters);

        $sql = "DELETE FROM TodoList WHERE idTodoList = :idTodoList";
        $filters = array('idTodoList' => $idTodoList);
        $result = $this->db->executeQuery($sql, $filters);

        return $result !== false;
    }
}
