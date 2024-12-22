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
        echo "Route array: " . implode(', ', $routeArray) . "\n";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                return json_encode(["error" => "Ошибка: недопустимый CSRF-токен."]);
            }
        }

        $this->controllerName = "Geekbrains\Application1\Domain\Controllers\PageController";
        $this->methodName = "actionIndex";

        $controllerName = '';

        if (isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
            $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller";
        }

        echo "Controller name from route: " . $controllerName . "\n";

        if ($controllerName === 'logout') {
            $this->controllerName = Application::APP_NAMESPACE . "UserController";
            $this->methodName = "actionLogout";
            echo "Using UserController for logout\n";
        } else {
            if (isset($routeArray[2]) && $routeArray[2] != '') {
                $methodName = $routeArray[2];
                $this->methodName = "action" . ucfirst($methodName);
            }
        }

        echo "Controller name: " . $this->controllerName . "\n";

        if (class_exists($this->controllerName)) {
            echo "Method name: " . $this->methodName . "\n";

            if (method_exists($this->controllerName, $this->methodName)) {
                $controllerInstance = new $this->controllerName();
                echo "Controller instance created.\n";

                if ($controllerInstance instanceof AbstractController) {
                    if ($this->checkAccessToMethod($controllerInstance, $this->methodName)) {
                        echo "Access granted to method: " . $this->methodName . "\n";
                        return call_user_func_array([$controllerInstance, $this->methodName], []);
                    } else {
                        echo "Access denied to method: " . $this->methodName . "\n";
                        return "Нет доступа к методу";
                    }
                } else {
                    return call_user_func_array([$controllerInstance, $this->methodName], []);
                }
            } else {
                echo "Method does not exist: " . $this->methodName . "\n";
                return "Метод не существует";
            }
        } else {
            echo "Class does not exist: " . $this->controllerName . "\n";
            return "Класс $this->controllerName не существует";
        }

        return "Произошла ошибка при выполнении запроса";
    }

    private function checkAccessToMethod(AbstractController $controllerInstance, string $methodName): bool
    {
        $userRoles = $controllerInstance->getUserRoles();
        $rules = $controllerInstance->getActionsPermissions($methodName);
        $isAllowed = false;

        echo "Проверка доступа к методу: " . $methodName . "\n";
        echo "Роли пользователя: " . implode(', ', $userRoles) . "\n";
        echo "Необходимые разрешения: " . implode(', ', $rules) . "\n";


        if (in_array('admin', $userRoles)) {
            if (in_array($methodName, ['actionEditUser', 'actionDeleteUser', 'actionUpdateUser'])) {
                return true;
            }
        }

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