<?php

$routes = [
    ['method' => 'GET', 'route' => 'tasks', 'handler' => 'TasksController@getAllTasks'],
    ['method' => 'POST', 'route' => 'tasks', 'handler' => 'TasksController@create'],
    ['method' => 'DELETE', 'route' => 'tasks/{id}', 'handler' => 'TaskController@delete'],
];

return $routes;
