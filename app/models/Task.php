<?php

require_once("configuration/connect.php");

class TaskModel extends Connect
{
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = "tasks";
    }

    public function getAll()
    {
        $sqlSelect = $this->connection->query("SELECT * FROM $this->table");
        $resultQuery = $sqlSelect->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($resultQuery, JSON_PRETTY_PRINT);
    }
}
