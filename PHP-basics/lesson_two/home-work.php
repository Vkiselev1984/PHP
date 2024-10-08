1. Implement the main 4 arithmetic operations in the form of a function with variable parameters - two parameters of
this number, the third is the operation. Be sure to use the return statement.

<?php

function calculate($num1, $num2, $operation)
{
    switch ($operation) {
        case 'add':
            return $num1 + $num2;
        case 'subtract':
            return $num1 - $num2;
        case 'multiply':
            return $num1 * $num2;
        case 'divide':
            if ($num2 == 0) {
                return 'Error: Division by zero';
            }
            return $num1 / $num2;
        default:
            return 'Error: Invalid operation';
    }
}

echo "Enter first number: ";
$num1 = trim(fgets(STDIN));

echo "Enter second number: ";
$num2 = trim(fgets(STDIN));

echo "Enter operation (add, subtract, multiply, divide): ";
$operation = trim(fgets(STDIN));

$result = calculate($num1, $num2, $operation);
echo "Result: " . $result . PHP_EOL;

?>

A calculate function has been created that takes three parameters: two numbers and a string with an operation.
An array with available operations is defined inside the function.
The return statement was used to return the result of the operation.
Added user interaction via the console for entering numbers and selecting an operation.

2. Declare an array in which the names of regions will be used as keys, and arrays with the names of cities from the
corresponding region will be used as values. Output the values ​​of the array in a loop so that the result is as
follows: Moscow region: Moscow, Zelenograd, Klin Leningrad region: St. Petersburg, Vsevolozhsk, Pavlovsk, Kronstadt
Ryazan region ... (names of cities can be found on maps.yandex.ru).

<?php

$regions = [
    '50' => ['Moscow', 'Zelenograd', 'Klin'],
    '47' => ['St. Petersburg', 'Vsevolozhsk', 'Pavlovsk', 'Kronstadt'],
    '62' => ['Ryazan', 'Kasimov', 'Skopin'],
];

$input_region = readline("Enter the region number: ");

// Check if the entered area exists in the array
if (array_key_exists($input_region, $regions)) {
    // Display the names of cities
    echo $input_region . ": " . implode(", ", $regions[$input_region]) . "\n";
} else {
    echo "Area not found.\n";
}
?>

An array of $regions has been created with regions and cities.
The user entered the region number into the console.
Checks to see if this area exists in the array and outputs the corresponding cities.

3. Declare an array whose indices are Russian letters, and whose values ​​are the corresponding Latin letter
combinations ('a' => 'a', 'b' => 'b', 'v' => 'v', 'g' => ' g', …, 'e' => 'e', ​​'yu' => 'yu', 'ya' => 'ya'). Write a
function to transliterate strings.

<?php
// Declare an associative array to transliterate
$transliteration_map = [
    'а' => 'a',
    'б' => 'b',
    'в' => 'v',
    'г' => 'g',
    'д' => 'd',
    'е' => 'e',
    'ё' => 'yo',
    'ж' => 'zh',
    'з' => 'z',
    'и' => 'i',
    'й' => 'y',
    'к' => 'k',
    'л' => 'l',
    'м' => 'm',
    'н' => 'n',
    'о' => 'o',
    'п' => 'p',
    'р' => 'r',
    'с' => 's',
    'т' => 't',
    'у' => 'u',
    'ф' => 'f',
    'х' => 'kh',
    'ц' => 'ts',
    'ч' => 'ch',
    'ш' => 'sh',
    'щ' => 'shch',
    'ъ' => '',
    'ы' => 'y',
    'ь' => '',
    'э' => 'e',
    'ю' => 'yu',
    'я' => 'ya'
];

// Request string input from user
echo "Enter a string in Russian: ";
$input_string = trim(fgets(STDIN)); // Read a string from standard input

// Convert the string to an array of characters
$chars = preg_split('//u', $input_string, -1, PREG_SPLIT_NO_EMPTY);

// Array to store the transliterated string
$transliterated_string = [];

// Loop through each character of the string
foreach ($chars as $char) {
    // If the character is in the array, we add its transliteration, otherwise we add the character itself
    if (isset($transliteration_map[$char])) {
        $transliterated_string[] = $transliteration_map[$char];
    } else {
        $transliterated_string[] = $char; // Leave the character unchanged
    }
}

// Merge the array into a string
$result = implode('', $transliterated_string);

// Make the first letter uppercase
$result = ucfirst($result);

// Output the result
echo $result . PHP_EOL; // Output transliterated capitalized string
?>

An associative array $transliteration_map is created, where the keys are Russian letters, and the values ​​are the
corresponding Latin letters or combinations.
The user enters a string through the console. The string is split into an array with unnecessary spaces pre-removed.
The program goes through each character and checks whether it is in the transliteration array. If it is, then the
corresponding value is added to the $transliterated_string array, otherwise the character itself is added.
The array of transliterated characters is combined into a string using implode(). ucfirst() is used to convert the first
letter of the result to a capital letter.
The result of the transliteration is output to the console.

4. Using recursion, organize a function for raising a number to a power. Format: function power($val, $pow), where $val
is the given number, $pow is the power.

<?php
// Recursive function for raising a number to a power
function power($val, $pow)
{
    // Base case: any number to the power of 0 is 1
    if ($pow == 0) {
        return 1;
    }
    // If the power is negative, recursively call the function with a positive power
    if ($pow < 0) {
        return 1 / power($val, -$pow);
    }
    // Recursive case: multiply the number by the result of calling the function with a reduced power
    return $val * power($val, $pow - 1);
}

// Prompt the user to enter a number
echo "Enter a number: ";
$number = trim(fgets(STDIN)); // Read a number from standard input

// Prompt the user to enter a power
echo "Enter a power: ";
$exponent = trim(fgets(STDIN)); // Read the power from standard input

// Convert the input values ​​to numbers
$number = (float) $number; // Convert to a floating point number
$exponent = (int) $exponent; // Convert to an integer

// Call the function and print the result
$result = power($number, $exponent);
echo "$number to the power of $exponent is $result" . PHP_EOL; // Print the result
?>

5. Write a function that calculates the current time and returns it in the format with the correct declensions, for
example:
22 hours 15 minutes
21 hours 43 minutes.

<?php
// Function to get the current time with correct declinations
function getCurrentTimeWithDeclension()
{
    // Get the current time
    $hours = date('G'); // Hours in 24-hour format
    $minutes = date('i'); // Minutes

    // Determine the declination for hours
    if ($hours % 10 == 1 && $hours % 100 != 11) {
        $hourDeclension = 'hour';
    } elseif ($hours % 10 >= 2 && $hours % 10 <= 4 && ($hours % 100 < 10 || $hours % 100 >= 20)) {
        $hourDeclension = 'hours';
    } else {
        $hourDeclension = 'hours';
    }

    // Determine the declination for minutes
    if ($minutes % 10 == 1 && $minutes % 100 != 11) {
        $minuteDeclension = 'minute';
    } elseif ($minutes % 10 >= 2 && $minutes % 10 <= 4 && ($minutes % 100 < 10 || $minutes % 100 >= 20)) {
        $minuteDeclension = 'minutes';
    } else {
        $minuteDeclension = 'minutes';
    }

    // Form a string with the current time
    return "$hours $hourDeclension $minutes $minuteDeclension";
}

// Call the function and print the result
echo getCurrentTimeWithDeclension() . PHP_EOL;
?>