<?php

$address = './birthdays.csv';
$name = readline("Enter name: ");
$date = readline("Enter date of birth in DD-MM-YYYY format: ");
$data = $name . ", " . $date . "\r\n"; // Please note that special line break characters - \r\n - are added at the end of each line. This is done so that one line corresponds to one user.
$fileHandler = fopen($address, 'a');
if (fwrite($fileHandler, $data)) {
    echo "Record $data added to file $address";
} else {
    echo "An error occurred while writing. Data was not saved";
}
fclose($fileHandler);