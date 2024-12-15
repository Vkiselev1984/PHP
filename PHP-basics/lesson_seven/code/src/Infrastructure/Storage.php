<?php

namespace Geekbrains\Application1\Infrastructure;

use Geekbrains\Application1\Application\Application;
use \PDO;
use \PDOException;

class Storage
{

    private PDO $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO(
                Application::$config->get()['database']['DSN'], // Убедитесь, что DSN соответствует PostgreSQL
                Application::$config->get()['database']['USER'],
                Application::$config->get()['database']['PASSWORD']
            );
        } catch (PDOException $e) {
            echo "Ошибка подключения к базе данных: " . $e->getMessage();
            throw new \Exception("Не удалось установить соединение с базой данных", 0, $e);
        }
    }

    public function get(): PDO
    {
        return $this->connection;
    }
}