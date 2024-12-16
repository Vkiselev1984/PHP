<?php

namespace Geekbrains\Application1\Infrastructure;

use Geekbrains\Application1\Application\Application;
use \PDO;

class Storage
{

    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO(
            Application::$config->get()['database']['DSN'],
            Application::$config->get()['database']['USER'],
            Application::$config->get()['database']['PASSWORD']
        );

        $this->connection->exec("SET NAMES 'utf8'");
    }

    public function get(): PDO
    {
        return $this->connection;
    }

    public function getTables(): array
    {
        $tables = [];

        $this->connection->exec("Application1");

        $query = $this->connection->query("SHOW TABLES;");

        while ($row = $query->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        return $tables;
    }
}