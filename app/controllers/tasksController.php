<?php

require_once("./models/Task.php");

class TasksController
{
    private $model;

    public function __construct($modelInstance)
    {
        $this->model = $modelInstance;
    }

    public function getAllTasks()
    {
        header('Content-Type: application/json');

        $tasks = $this->model->getAll();
        echo $tasks;
    }

}
