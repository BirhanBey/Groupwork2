<?php


require "api.php";



// if you want all the Todo Lists ==> search to localhost
// if you want search one TodoList by id ==> localhost/api/TodoList&id={id} and you will get the todos 
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if an endpoint is provided
    if (isset($_GET['endpoint'])) {
        $endpoint = $_GET['endpoint'];
        // Check if the endpoint is /TodoList

        if ($endpoint === '/TodoList' || $endpoint === '/todolist') {

            // Check if an ID is provided
            if (isset($_GET['id'])) {
                // Get the todo list from the database
                $todo = new TodoList();
                $result = $todo->getListById($_GET['id']);

                // Return the result as JSON
                echo json_encode($result);
            } else {
                // Handle missing ID parameter
                echo json_encode(array('error' => 'ID parameter missing'));
            }
        } else {
            // Set endpoint to empty string if it's not recognized
            $endpoint = '';
        }
    }

    if ($endpoint === '') {
        // No endpoint provided, show all TodoLists
        $todo = new TodoList();
        $result = $todo->getAllLists();

        // Return the result as JSON
        echo json_encode($result);
    }
}




// if you want to add a todo list => api/TodoList and the body has to be only the name
//if you want to add a todo => api/Todo and the body needs a description and TodoList_id

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the endpoint from the request URL
    $endpoint = $_GET['endpoint'];
    // Check if the endpoint is /Todo


    if ($endpoint === '/Todo') {

        // Get the todo data from the request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Call the addTodo method with the todo data
        $idTodoList = $data['TodoList_id'];
        $description = $data['description'];
        $todos = new Todos();
        $result = $todos->addTodo($idTodoList, $description);

        // Send a response based on the result of the addTodo method

        $response = array('message' => 'Todo created');
        if (isset($result)) {
            http_response_code(201);
            echo json_encode($response);
        } else {
            http_response_code(500);
            echo json_encode(array('message' => 'Unable to create todo'));
        }


        // Check if the endpoint is /TodoList
    } else if ($endpoint === '/TodoList') {

        // Get the todo list name from the request body
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'];

        // Call the addTodoList method with the todo list name
        $todos = new TodoList();
        $result = $todos->addTodoList($name);

        // Send a response based on the result of the addTodoList method

        $response = array('message' => 'TodoList created');
        if (json_last_error() === JSON_ERROR_NONE) {
            http_response_code(201);
            echo json_encode($response);
        } else {
            http_response_code(500);
            echo json_encode(array('message' => 'Unable to create TodoList'));
        }
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'Endpoint not found'));
    }
}















// if you want to delete a todo list => api/TodoList&id={id}
// if you want to delete a todo => api/Todo&id={id}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Get the endpoint from the request URL
    $endpoint = $_GET['endpoint'];

    // Check if the endpoint is /TodoList
    if (strpos($endpoint, '/TodoList') !== false) {
        // Check if an ID is provided
        if (isset($_GET['id'])) {
            // Delete the todo list from the database
            $todo = new TodoList();
            $result = $todo->deleteTodoList($_GET['id']);

            // Send a response based on the result of the deleteTodoList method
            if ($result) {
                http_response_code(200);
                echo json_encode(array('message' => 'Todo list deleted'));
            } else {
                http_response_code(500);
                echo json_encode(array('message' => 'Unable to delete todo list'));
            }
        } else {
            // Handle missing ID parameter
            echo json_encode(array('error' => 'ID parameter missing'));
        }
        // Check if the endpoint is /Todo
    } elseif (strpos($endpoint, '/Todo') !== false) {
        // Check if an ID is provided
        if (isset($_GET['id'])) {
            // Delete the todo from the database
            $todo = new Todos();
            $result = $todo->deleteTodoById($_GET['id']);

            // Send a response based on the result of the deleteTodoById method
            if ($result) {
                http_response_code(200);
                echo json_encode(array('message' => 'Todo deleted'));
            } else {
                http_response_code(500);
                echo json_encode(array('message' => 'Unable to delete todo'));
            }
        } else {
            // Handle missing ID parameter
            echo json_encode(array('error' => 'ID parameter missing'));
        }
    } else {
        // If the endpoint is not recognized, send a 404 response
        http_response_code(404);
        echo json_encode(array('message' => 'Endpoint not found'));
    }
}




// toggle isCompleted => api/Todo&id={id} , if isCompleted is 1 it will change to 0 and if its 0 it will change to 1
if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    // Get the endpoint from the request URL
    $endpoint = $_GET['endpoint'];
    // Check if the endpoint is /Todo

    if ($endpoint === '/Todo') {
        // Get the todo data from the request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Check if the ID is provided
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(array('message' => 'Todo ID not provided'));
            exit;
        }

        // Get the ID of the todo to update
        $id = $_GET['id'];

        // Get the current status of the todo
        $todos = new Todos();
        $currentStatus = $todos->getTodoStatus($id);

        // Toggle the status of the todo
        $newStatus = $currentStatus == 1 ? 0 : 1;

        // Call the updateTodo method with the new status
        $result = $todos->updateTodoStatus($id, $newStatus);

        // Send a response based on the result of the updateTodo method

        if (!$result) {

            http_response_code(200);
            echo json_encode(array('message' => 'Todo status updated'));
        } else {
            http_response_code(500);
            echo json_encode(array('message' => 'Unable to update todo status'));
        }

        // If the endpoint is not recognized, send a 404 response
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'Endpoint not found'));
    }
}
