<?php

define("HOST", "localhost");
define("DATABASENAME", "to-do-list-db");
define("USER", "admin");
define("PASSWORD", "w[[8EN9ThU4MD)0w");

class Connect
{
    protected $connection;

    public function __construct()
    {
        $this->connectDatabase();
    }

    public function connectDatabase()
    {
        try {
            $this-> connection = new PDO("mysql:host=". HOST. ";dbname=". DATABASENAME, USER, PASSWORD);
        } catch(PDOException $e) {
            echo("Error: ".$e->getMessage());
            die();
        }
    }
}
