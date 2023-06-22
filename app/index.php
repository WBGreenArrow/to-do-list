<?php

require_once("./controllers/tasksController.php");
require_once("./models/Task.php");

$route = isset($_GET['route']) ? $_GET['route'] : '';

$taskModel = new TaskModel();

$taskController = new TasksController($taskModel);

$routes = require_once("./routes.php");

$matchedRoute = null;

foreach ($routes as $routeData) {
    if ($_SERVER['REQUEST_METHOD'] === $routeData['method'] && $route === $routeData['route']) {
        $matchedRoute = $routeData;
        break;
    }
}

if ($matchedRoute) {
    $handler = $matchedRoute['handler'];
    [$controllerName, $methodName] = explode('@', $handler);

    $controller = new $controllerName($taskModel);
    $controller->$methodName();
} else {
    http_response_code(400);
    echo json_encode(["message" => "router not found"], JSON_PRETTY_PRINT);
}
