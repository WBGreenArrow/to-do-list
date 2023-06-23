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

    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $task = $statement->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($task, JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Task not found'], JSON_PRETTY_PRINT);
        }
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

            http_response_code(400);
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json');

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $task = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            http_response_code(404);
            echo json_encode(['message' => 'Task not found'], JSON_PRETTY_PRINT);
            return;
        }

        if (isset($data['title']) || isset($data['description'])) {
            $title = isset($data['title']) ? $data['title'] : $task['title'];
            $description = isset($data['description']) ? $data['description'] : $task['description'];

            $updateStatements = [];
            $values = [];

            if (!empty($title)) {
                $updateStatements[] = "`title` = ?";
                $values[] = $title;
            }

            if (!empty($description)) {
                $updateStatements[] = "`description` = ?";
                $values[] = $description;
            }

            if (!empty($updateStatements)) {
                $setClause = implode(', ', $updateStatements);
                $values[] = $id;

                $sqlUpdate = $this->connection->prepare("UPDATE $this->table SET $setClause WHERE `id` = ?");
                $sqlUpdate->execute($values);

                if ($sqlUpdate->rowCount() > 0) {
                    $response = [
                        'message' => 'Task updated successfully',
                        'updated_id' => $id
                    ];
                    http_response_code(200);
                    echo json_encode($response, JSON_PRETTY_PRINT);
                } else {
                    $response = [
                        'message' => 'Failed to update task'
                    ];
                    http_response_code(500);
                    echo json_encode($response, JSON_PRETTY_PRINT);
                }
            } else {
                $response = [
                    'message' => 'No fields provided for update'
                ];
                http_response_code(400);
                echo json_encode($response, JSON_PRETTY_PRINT);
            }
        } else {
            $response = [
                'message' => 'Title or description are required fields'
            ];
            http_response_code(400);
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    public function delete($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $task = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Task not found'], JSON_PRETTY_PRINT);
            return;
        }


        $sql = "DELETE FROM $this->table WHERE id = :id";
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $statement->execute();

        if ($result) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Task deleted successfully'], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Failed to delete task'], JSON_PRETTY_PRINT);
        }
    }
    
}
