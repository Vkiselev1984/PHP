<?php

namespace Geekbrains\Application1\Infrastructure;

use Geekbrains\Application1\Application\Application;
use PDO;
use PDOException;

class Storage
{
    private PDO $connection;

    public function __construct()
    {
        $config = Application::$config->get()['database'];

        try {
            $this->connection = new PDO(
                $config['DSN'],
                $config['USER'],
                $config['PASSWORD'],
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                )
            );
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new Exception("Failed to connect to the database.");
        }
    }

    public function get(): PDO
    {
        return $this->connection;
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}