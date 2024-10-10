<?php

function main(string $configFileAddress): string
{
    $config = readConfig($configFileAddress);

    if (!$config) {
        return handleError("Unable to connect settings file");
    }

    $functionName = parseCommand();

    if (function_exists($functionName)) {
        $result = $functionName($config);
    } else {
        $result = handleError("The called function does not exist");
    }

    return $result;
}

function parseCommand(): string
{
    $functionName = 'helpFunction';

    if (isset($_SERVER['argv'][1])) {
        $functionName = match ($_SERVER['argv'][1]) {
            'read-all' => 'readAllFunction',
            'search' => 'searchFunction',
            'delete' => 'deleteFunction',
            'add' => 'addFunction',
            'clear' => 'clearFunction',
            'read-profiles' => 'readProfilesDirectory',
            'read-profile' => 'readProfile',
            'help' => 'helpFunction',
            default => 'helpFunction'
        };
    }

    return $functionName;
}

