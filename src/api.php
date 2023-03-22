<?php

require "includes/Todos.class.php";
require "includes/TodoList.class.php";
// Set the HTTP response headers
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT,PATCH, DELETE, OPTIONS");
