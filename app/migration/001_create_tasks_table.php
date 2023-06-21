<?php

require_once 'configuration/connect.php';

class CreateTasksTable extends Connect
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $sql = "CREATE TABLE tasks (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS tasks";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }
}
