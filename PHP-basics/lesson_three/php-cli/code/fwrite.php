<?php

$address = './people.csv';
$data = "Василий Васильев, 05-06-1992";
$fileHandler = fopen($address, 'a');
fwrite($fileHandler, $data);
fclose($fileHandler);