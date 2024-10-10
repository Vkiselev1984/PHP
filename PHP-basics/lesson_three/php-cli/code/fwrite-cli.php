<?php

$address = '/code/birthdays.txt';

$name = readline("Enter your name: ");
$date = readline("Please enter your date of birth in DD-MM-YYYY format: ");

if (validate($date)) {
    $data = $name . ", " . $date . "\r\n";

    $fileHandler = fopen($address, 'a');

    if (fwrite($fileHandler, $data)) {
        echo "$data add to $address";
    } else {
        echo "There was a write error. Data not saved";
    }

    fclose($fileHandler);
} else {
    echo "Incorrect information entered";
}

function validate(string $date): bool
{
    $dateBlocks = explode("-", $date);

    if (count($dateBlocks) < 3) {
        return false;
    }

    if (isset($dateBlocks[0]) && $dateBlocks[0] > 31) {
        return false;
    }

    if (isset($dateBlocks[1]) && $dateBlocks[0] > 12) {
        return false;
    }

    if (isset($dateBlocks[2]) && $dateBlocks[2] > date('Y')) {
        return false;
    }

    return true;
}
