<?php

namespace Geekbrains\Application1;

class Application
{

    private const APP_NAMESPACE = 'Geekbrains\Application1\Controllers\\';

    private string $controllerName;
    private string $methodName;


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
                return call_user_func_array(
                    [$controllerInstance, $this->methodName],
                    []
                );
            } else {
                $errorController = new \Geekbrains\Application1\Controllers\ErrorController();
                return $errorController->error404();

            }
        } else {
            $errorController = new \Geekbrains\Application1\Controllers\ErrorController();
            return $errorController->error404();
        }
    }

    public function render(array $pageVariables)
    {

    }
}