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
        $tasks = $this->model->getAll();
        echo $tasks;
    }

    public function createTask()
    {
        $response = $this->model->create();
        echo $response;
    }

}
