<?php

$address = "./file.csv";
if (file_exists($address) && is_readable($address)) {
    $file = fopen($address, "rb");
    $contents = '';
    while (!feof($file)) {
        $contents .= fread($file, 100);
    }
    fclose($file);
    echo $contents;
} else {
    echo ("The file cannot be opened or does not exist");
}