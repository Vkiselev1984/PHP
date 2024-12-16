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
        ini_set('session.save_handler', 'files');
        ini_set('session.save_path', '/tmp');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        try {
            session_start();
        } catch (\Exception $e) {
            die("Ошибка при запуске сессии: " . $e->getMessage());
        }

        $memcached = new \Memcached();
        $memcached->addServer('172.20.0.1', 11211);

        if ($memcached->set('test_key', 'test_value')) {
            echo "Подключение к Memcached успешно!";
        } else {
            echo "Ошибка подключения к Memcached: " . $memcached->getResultMessage();
        }

        Application::$config = new Config();
        Application::$storage = new Storage();
        Application::$auth = new Auth();
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

                if ($controllerInstance instanceof AbstractController) {
                    if ($this->checkAccessToMethod($controllerInstance, $this->methodName)) {
                        return call_user_func_array(
                            [$controllerInstance, $this->methodName],
                            []
                        );
                    } else {
                        return "Нет доступа к методу";
                    }
                } else {
                    return call_user_func_array(
                        [$controllerInstance, $this->methodName],
                        []
                    );
                }
            } else {
                return "Метод не существует";
            }
        } else {
            return "Класс $this->controllerName не существует";
        }
    }

    private function checkAccessToMethod(AbstractController $controllerInstance, string $methodName): bool
    {
        $userRoles = $controllerInstance->getUserRoles();

        $rules = $controllerInstance->getActionsPermissions($methodName);

        $isAllowed = false;

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