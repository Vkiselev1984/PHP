<?php
$file = fopen("./file.csv", "rb");
if ($file === false) {
    echo ("The file cannot be opened or does not exist");
} else {
    $contents = '';
    while (!feof($file)) {
        $contents .= fread($file, 100);
    }
    fclose($file);
    echo $contents;
}
