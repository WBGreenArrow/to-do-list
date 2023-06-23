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

    public function getTaskById($id)
    {
        $task = $this->model->getById($id);
        echo $task;
    }

    public function createTask()
    {
        $response = $this->model->create();
        echo $response;
    }

    public function updateTask($id)
    {
        $response = $this->model->update($id);
        echo $response;
    }

    public function deleteTask($id)
    {
        $response = $this->model->delete($id);
        echo $response;
    }

}
