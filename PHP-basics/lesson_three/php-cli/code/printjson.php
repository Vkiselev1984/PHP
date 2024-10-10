<?php

// Data to write
$data = array('name' => 'Petr Petrov', 'birthday' => '06-11-1998');

// Encode data into JSON format
$json = json_encode($data);

// Write JSON to file
$filePath = 'data.json'; // File name to write
file_put_contents($filePath, $json);

// Read data from file
$jsonFromFile = file_get_contents($filePath);

// Decode JSON back into array
$decodedData = json_decode($jsonFromFile, true); // true to get associative array

// Print decoded data
print_r($decodedData);
