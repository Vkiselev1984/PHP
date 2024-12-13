<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Geekbrains\Application1\Infrastructure\Config;
use Geekbrains\Application1\Infrastructure\Storage;

class Application
{
    private const APP_NAMESPACE = 'Geekbrains\Application1\Domain\Controllers\\';

    private string $controllerName;
    private string $methodName;

    public static Config $config;
    public static Storage $storage;

    public function __construct()
    {
        Application::$config = new Config();
        Application::$storage = new Storage();
        $config = Application::$config->get();
        $host = Application::$config->getHost();
    }

    public function run(): string
    {
        $routeArray = explode('/', $_SERVER['REQUEST_URI']);

        if (isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
        } else {
            $controllerName = "page";
        }

        $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller";

        if (class_exists($this->controllerName)) {
            if (isset($routeArray[2]) && $routeArray[2] != '') {
                $methodName = $routeArray[2];
            } else {
                $methodName = "index";
            }

            $this->methodName = "action" . ucfirst($methodName);

            if (method_exists($this->controllerName, $this->methodName)) {
                $controllerInstance = new $this->controllerName();
                return call_user_func_array([$controllerInstance, $this->methodName], []);
            } else {
                throw new Exception("Method " . $this->methodName . " does not exist");
            }
        } else {
            throw new Exception("Class $this->controllerName does not exist");
        }
    }
}