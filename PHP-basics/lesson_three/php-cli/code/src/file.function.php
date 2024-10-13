<?php

function readAllFunction(array $config): string
{
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "rb");

        $contents = '';

        while (!feof($file)) {
            $contents .= fread($file, 100);
        }

        fclose($file);
        return $contents;
    } else {
        return handleError("The file does not exist");
    }
}

function addFunction(array $config): string
{
    $address = $config['storage']['address'];

    $name = readline("Enter your name: ");
    $date = readline("Please enter your date of birth in DD-MM-YYYY format: ");
    if (validate($date)) {
        $data = $name . ", " . $date . "\r\n";

        $fileHandler = fopen($address, 'a');

        if (fwrite($fileHandler, $data)) {
            return "The entry $data has been added to the file $address";
        } else {
            return handleError("There was a write error. Data not saved.");
        }

        fclose($fileHandler);
    } else {
        return handleError("Invalid date format. Please enter a valid date in DD-MM-YYYY format.");
    }
}

function clearFunction(array $config): string
{
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "w");

        fwrite($file, '');

        fclose($file);
        return "File cleared";
    } else {
        return handleError("The file does not exist");
    }
}

function helpFunction()
{
    return handleHelp();
}

function readConfig(string $configAddress): array|false
{
    return parse_ini_file($configAddress, true);
}

function readProfilesDirectory(array $config): string
{
    $profilesDirectoryAddress = $config['profiles']['address'];

    if (!is_dir($profilesDirectoryAddress)) {
        mkdir($profilesDirectoryAddress);
    }

    $files = scandir($profilesDirectoryAddress);

    $result = "";

    if (count($files) > 2) {
        foreach ($files as $file) {
            if (in_array($file, ['.', '..']))
                continue;

            $result .= $file . "\r\n";
        }
    } else {
        $result .= "Directory is empty \r\n";
    }

    return $result;
}

function readProfile(array $config): string
{
    $profilesDirectoryAddress = $config['profiles']['address'];

    if (!isset($_SERVER['argv'][2])) {
        return handleError("Profile file not specified");
    }

    $profileFileName = $profilesDirectoryAddress . $_SERVER['argv'][2] . ".json";

    if (!file_exists($profileFileName)) {
        return handleError("File $profileFileName does not exist");
    }

    $contentJson = file_get_contents($profileFileName);
    $contentArray = json_decode($contentJson, true);

    $info = "name: " . $contentArray['name'] . "\r\n";
    $info .= "lastname: " . $contentArray['lastname'] . "\r\n";

    return $info;
}

function validate(string $date): bool
{
    $dateBlocks = explode("-", $date);

    if (count($dateBlocks) !== 3) {
        return false;
    }

    list($day, $month, $year) = $dateBlocks;

    if (!is_numeric($day) || !is_numeric($month) || !is_numeric($year)) {
        return false;
    }

    $day = (int) $day;
    $month = (int) $month;
    $year = (int) $year;

    if ($day < 1 || $day > 31 || $month < 1 || $month > 12 || $year < 1900 || $year > date('Y')) {
        return false;
    }
    if (!checkdate($month, $day, $year)) {
        return false;
    }

    return true;
}

function findBirthdaysToday(array $config): string
{
    $address = $config['storage']['address'];

    if (!file_exists($address) || !is_readable($address)) {
        return handleError("The file does not exist or is not readable.");
    }

    $today = date('d-m');
    $birthdays = [];

    $file = fopen($address, "r");

    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        if (!empty($line)) {
            list($name, $date) = explode(", ", $line);
            $date = trim($date);
            if (date('d-m', strtotime($date)) === $today) {
                $birthdays[] = $name;
            }
        }
    }

    fclose($file);

    if (empty($birthdays)) {
        return "There is no need to congratulate anyone on their birthday today";
    } else {
        return "Today is the birthday of: " . implode(", ", $birthdays);
    }
}

function searchFunction(array $config): string
{
    return findBirthdaysToday($config);
}

function deleteFunction(array $config): string
{
    $address = $config['storage']['address'];

    if (!file_exists($address) || !is_readable($address)) {
        return handleError("The file does not exist or is not readable.");
    }

    $searchTerm = readline("Enter the name or date to delete: ");
    $lines = file($address, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $found = false;

    $updatedLines = [];
    foreach ($lines as $line) {
        if (strpos($line, $searchTerm) === false) {
            $updatedLines[] = $line;
        } else {
            $found = true;
        }
    }

    if ($found) {
        file_put_contents($address, implode(PHP_EOL, $updatedLines) . PHP_EOL);
        return "Entry with '$searchTerm' has been deleted.";
    } else {
        return "No entry found with '$searchTerm'.";
    }
}