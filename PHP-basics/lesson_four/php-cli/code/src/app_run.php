<?php

require 'vendor/autoload.php';

use App\Oop\App;

$app = new App();
$args = array_slice($_SERVER['argv'], 1);
$result = $app->runCommand($args);
echo $result . "\n";