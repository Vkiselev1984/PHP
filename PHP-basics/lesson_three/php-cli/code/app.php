<?php

// including logic files
// require_once('src/main.function.php');
// require_once('src/template.function.php');
// require_once('src/file.function.php');

require_once('../code/vendor/autoload.php');

// calling the root function
$result = main("/code/config.ini");
// output the result
echo $result;