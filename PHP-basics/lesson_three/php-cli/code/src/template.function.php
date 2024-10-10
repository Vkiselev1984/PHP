<?php

function handleError(string $errorText): string
{
    return "\033[31m" . $errorText . " \r\n \033[97m";
}

function handleHelp(): string
{
    $help = "File storage program \r\n";

    $help .= "Call order\r\n\r\n";

    $help .= "php /code/app.php [COMMAND] \r\n\r\n";

    $help .= "Available commands: \r\n";
    $help .= "read-all - read the entire file \r\n";
    $help .= "add - add a record \r\n";
    $help .= "clear - clear the file \r\n";
    $help .= "read-profiles - list user profiles \r\n";
    $help .= "read-profile - list selected user profile \r\n";
    $help .= "help - help \r\n";

    return $help;
}