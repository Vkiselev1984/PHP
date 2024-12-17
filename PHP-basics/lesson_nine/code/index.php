<?php

require_once('./vendor/autoload.php');

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;

try {
    $app = new Application();
    echo $app->run();
} catch (Exception $e) {
    $render = new Render();
    echo $render->renderExceptionPage($e);
}