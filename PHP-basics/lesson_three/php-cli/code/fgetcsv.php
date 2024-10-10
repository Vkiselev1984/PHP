<?php

$address = './file.csv';
$fileHandle = fopen($address, 'r');
while ($data = fgetcsv($fileHandle)) {
    print_r($data);
}
