<?php

namespace Geekbrains\Application1\Application;

use Geekbrains\Application1\Domain\Controllers\AbstractController;
use Geekbrains\Application1\Infrastructure\Config;
use Geekbrains\Application1\Infrastructure\Storage;
use Geekbrains\Application1\Application\Auth;

class Application
{

    private const APP_NAMESPACE = 'Geekbrains\Application1\Domain\Controllers\\';

    private string $controllerName;
    private string $methodName;

    public static Config $config;
    public static Storage $storage;
    public static Auth $auth;

    public function __construct()
    {
        Application::$config = new Config();
        Application::$storage = new Storage();
        Application::$auth = new Auth();
    }

    public function run(): string
    {
        ob_start();
        session_start();
        $routeArray = explode('/', $_SERVER['REQUEST_URI']);
        echo "Route array: " . implode(', ', $routeArray) . "\n"; // Отладочное сообщение

        if (isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
        } else {
            $controllerName = "page";
        }

        $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller";
        echo "Controller name: " . $this->controllerName . "\n"; // Отладочное сообщение

        if (class_exists($this->controllerName)) {
            if (isset($routeArray[2]) && $routeArray[2] != '') {
                $methodName = $routeArray[2];
            } else {
                $methodName = "index";
            }

            $this->methodName = "action" . ucfirst($methodName);
            echo "Method name: " . $this->methodName . "\n"; // Отладочное сообщение

            if (method_exists($this->controllerName, $this->methodName)) {
                $controllerInstance = new $this->controllerName();
                echo "Controller instance created.\n"; // Отладочное сообщение

                if ($controllerInstance instanceof AbstractController) {
                    if ($this->checkAccessToMethod($controllerInstance, $this->methodName)) {
                        echo "Access granted to method: " . $this->methodName . "\n"; // Отладочное сообщение
                        return call_user_func_array(
                            [$controllerInstance, $this->methodName],
                            []
                        );
                    } else {
                        echo "Access denied to method: " . $this->methodName . "\n"; // Отладочное сообщение
                        return "Нет доступа к методу";
                    }
                } else {
                    return call_user_func_array(
                        [$controllerInstance, $this->methodName],
                        []
                    );
                }
            } else {
                echo "Method does not exist: " . $this->methodName . "\n"; // Отладочное сообщение
                return "Метод не существует";
            }
        } else {
            echo "Class does not exist: " . $this->controllerName . "\n"; // Отладочное сообщение
            return "Класс $this->controllerName не существует";
        }
    }

    private function checkAccessToMethod(AbstractController $controllerInstance, string $methodName): bool
    {
        $userRoles = $controllerInstance->getUserRoles();
        $rules = $controllerInstance->getActionsPermissions($methodName);
        $isAllowed = false;

        echo "Checking access for method: " . $methodName . "\n"; // Отладочное сообщение
        echo "User roles: " . implode(', ', $userRoles) . "\n"; // Отладочное сообщение
        echo "Required permissions: " . implode(', ', $rules) . "\n"; // Отладочное сообщение

        if (!empty($rules)) {
            foreach ($rules as $rolePermission) {
                if (in_array($rolePermission, $userRoles)) {
                    $isAllowed = true;
                    break;
                }
            }
        } else {
            $isAllowed = true;
        }

        return $isAllowed;
    }
}