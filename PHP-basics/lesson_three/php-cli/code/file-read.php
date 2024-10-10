<?php
// Path to CSV file
$filePath = 'file.csv';

// Reading file contents
$fileContents = file_get_contents($filePath);

// Reading file contents
if ($fileContents === false) {
    die("Error reading file: $filePath");
}

// Outputting file contents
echo $fileContents;