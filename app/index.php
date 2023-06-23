<?php

require_once("./controllers/tasksController.php");
require_once("./models/Task.php");

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$taskModel = new TaskModel();
$taskController = new TasksController($taskModel);

$basePath = '/api/tasks';
$segments = explode('/', $uri);

if ($method === 'GET' && $uri === $basePath) {
    $taskController->getAllTasks();
} elseif ($method === 'GET' && count($segments) === 4 && $segments[1] === 'api' && $segments[2] === 'tasks') {
    $id = $segments[3];
    $taskController->getTaskById($id);
} elseif ($method === 'POST' && $uri === $basePath) {
    $taskController->createTask();
} elseif ($method === 'PATCH' && count($segments) === 4 && $segments[1] === 'api' && $segments[2] === 'tasks') {
    $id = $segments[3];
    $taskController->updateTask($id);
} elseif ($method === 'DELETE' && count($segments) === 4 && $segments[1] === 'api' && $segments[2] === 'tasks') {
    $id = $segments[3];
    $taskController->deleteTask($id);
} else {
    http_response_code(404);
    echo json_encode(["message" => "Route not found"], JSON_PRETTY_PRINT);
}
