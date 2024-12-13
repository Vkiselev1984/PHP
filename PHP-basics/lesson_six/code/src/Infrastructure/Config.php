<?php

namespace Geekbrains\Application1\Infrastructure;

class Config
{

    private string $defaultConfigFile = "/src/config/config.ini";

    private array $applicationConfiguration = [];

    public function __construct()
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . $this->defaultConfigFile;

        if (file_exists($address) && is_readable($address)) {
            $this->applicationConfiguration = parse_ini_file($address, true);
        } else {
            throw new \Exception("Configuration file not found");
        }
    }

    public function get(): array
    {
        return $this->applicationConfiguration;
    }

    public function getHost(): string
    {
        $dsn = $this->applicationConfiguration['database']['DSN'];
        preg_match('/host=([^;]+)/', $dsn, $matches);
        return $matches[1] ?? 'localhost';
    }
}