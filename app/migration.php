<?php

require_once '../app/configuration/connect.php';

class Migration extends Connect
{
    public function __construct()
    {
        parent::__construct();
    }

    public function runMigrations()
    {
        $migrationFiles = glob('migration/*.php');

        foreach ($migrationFiles as $migrationFile) {
            $migrationName = basename($migrationFile, '.php');

            $migrationExecuted = $this->isMigrationExecuted($migrationName);

            if (!$migrationExecuted) {
                require_once $migrationFile;

                $migrationClassName = $this->getMigrationClassName($migrationName);
                $migration = new $migrationClassName();

                $migration->up();

                $version = $this->getMigrationVersion($migrationName);
                $this->registerMigration($migrationName, $version);
            }
        }

        echo("Migrations executed successfully.");
    }

    private function isMigrationExecuted($migrationName)
    {
        $sql = "SELECT COUNT(*) FROM migrations WHERE migration_name = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$migrationName]);

        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    private function registerMigration($migrationName, $version)
    {
        $sql = "INSERT INTO migrations (migration_name, version) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$migrationName, $version]);
    }

    private function getMigrationClassName($migrationName)
    {
        $parts = explode('_', $migrationName);
        $classNameParts = array_map('ucfirst', $parts);
        array_shift($classNameParts);
        $className = implode('', $classNameParts);

        return $className;
    }

    private function getMigrationVersion($migrationName)
    {
        $parts = explode('_', $migrationName);
        $version = (int) $parts[0];

        return $version;
    }

    public function createMigrationsTableIfNotExists()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            migration_name VARCHAR(255) NOT NULL,
            version INT(11) NOT NULL DEFAULT 0
        )";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }
}

$migration = new Migration();
$migration->createMigrationsTableIfNotExists();
$migration->runMigrations();
