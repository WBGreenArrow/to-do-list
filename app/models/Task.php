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
        http_response_code(200);

        header('Content-Type: application/json');
        echo json_encode($resultQuery, JSON_PRETTY_PRINT);
    }

    public function create()
    {
        header('Content-Type: application/json');

        $json = file_get_contents('php://input');

        $data = json_decode($json, true);

        if (isset($data['title']) && isset($data['description'])) {
            $title = $data['title'];
            $description = $data['description'];

            $sqlInsert = $this->connection->prepare("INSERT INTO $this->table (`title`, `description`) VALUES (?, ?)");
            $sqlInsert->execute([$title, $description]);

            $insertedId = $this->connection->lastInsertId();

            if ($insertedId) {
                $response = [
                    'message' => 'Task created successfully',
                    'inserted_id' => $insertedId
                ];
                http_response_code(200);
                echo json_encode($response, JSON_PRETTY_PRINT);
            } else {
                $response = [
                    'message' => 'Failed to create task'
                ];
                http_response_code(500);
                echo json_encode($response, JSON_PRETTY_PRINT);
            }

        } else {
            $response = [
                'message' => 'Title and description are required fields'
            ];

            http_response_code(400); // Bad Request
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }
}
