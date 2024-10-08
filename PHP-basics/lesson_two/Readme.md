# Conditions, Arrays, Loops, Functions

## Branches

When executing a program linearly, we can only execute simple instructions that will never change their behavior under any circumstances. They will consistently perform the same steps.

But what if we need to react differently to different states?

For example, if a user has the opportunity to send us one of several response options, we need to check if it is correct and, in case of an error, inform the user.

Thus, we form the condition "if the assumption is true, take step A, otherwise take step B"

Branching in PHP is a mechanism that allows programmers to make decisions based on conditions. This allows programs to make decisions based on data entered by the user or received from other sources.

For example, you can write a condition in the program that checks whether the file sent by the user is a photo or a PDF document. And in different branches of the code, either the placement of the photo in the gallery will be indicated, or the PDF document will be saved in the repository.

### Conditional operators

#### The If statement

The if statement is used to execute code if a certain condition is true.
It has the following syntax:

```php
if (condition) {
// code to be executed if the condition is true
}
```

It is important to understand what the condition is.

In the simplest case, it can be the value of a variable.

```php
$isRed = true;
if ($isRed) {
//the code that will be executed if we work with the red color
}
```

The condition can be an expression – a comparison of values.
Therefore, let's see what expressions can be used as conditions.

#### Else if

The else if statement is used to add more conditions to the if statement.
It has the following syntax:

But what if we need to create a more complex branching?

The else if statement is used to check additional conditions if the first condition is false.

```php
if (condition1) {
// code to be executed if the condition1 is true
} else if (condition2) {
// code to be executed if the condition2 is true
}
```

#### Else

The else statement is used to execute code if none of the previous conditions are met.
It has the following syntax:

```php
if (condition1) {
// code to be executed if the condition1 is true
} else if (condition2) {
// code to be executed if the condition2 is true
} else {
// code to be executed if none of the previous conditions are met
}
```

### Switch statement

The switch statement is used to execute code based on the value of a variable.
It has the following syntax:

```php
switch (variable) {
case value1:
// code to be executed if the value of the variable is equal to value1
break;
case value2:
// code to be executed if the value of the variable is equal to value2
break;
default:
// code to be executed if the value of the variable is not equal to any of the previous values
}
```

In this example, the variable is checked for the value of the first case. If it is equal to this value, the code inside this case is executed. If it is not equal to this value, the code inside the default case is executed.

#### Match

The match statement is used to check if a variable matches a pattern. It has the following syntax:

```php
match (variable) {
case pattern1:
// code to be executed if the variable matches pattern1
break;
case pattern2:
// code to be executed if the variable matches pattern2
break;
default:
// code to be executed if the variable does not match any of the previous patterns
}
```

In this example, the variable is checked for the pattern of the first case. If it matches this pattern, the code inside this case is executed. If it does not match this pattern, the code inside the default case is executed.

#### Ternary operator

The ternary operator is used to execute code based on the value of a variable. It has the following syntax:

```php
expression1? expression2 : expression3;
```

It is used to replace an if-else statement.

### Loop statements

#### For loop

The for loop is used to execute code a certain number of times.
It has the following syntax:

```php
for (initialization; condition; increment) {
// code to be executed
}
```

The initialization is executed once at the beginning of the loop.
The condition is checked at the beginning of each iteration.
The increment is executed after each iteration.

#### While loop

The while loop is used to execute code while a certain condition is true.
It has the following syntax:

```php
while (condition) {
// code to be executed
}
```

The condition is checked at the beginning of each iteration.
If the condition is false, the loop ends.

#### Do-while loop

The do-while loop is used to execute code at least once, and then check the condition.
It has the following syntax:

```php
do {
// code to be executed
} while (condition);
```

The condition is checked at the end of each iteration.
If the condition is false, the loop ends.

### Comparison operators

There are several comparison operators in PHP.

| Operator | Description                                                      |
| -------- | ---------------------------------------------------------------- | --- | ---------- |
| ==       | Equal to                                                         |
| ===      | Equal to and type                                                |
| !=       | Not equal to                                                     |
| !==      | Not equal to and type                                            |
| >        | Greater than                                                     |
| <        | Less than                                                        |
| >=       | Greater than or equal to                                         |
| <=       | Less than or equal to                                            |
| &&       | Logical AND                                                      |
|          |                                                                  |     | Logical OR |
| !        | Logical NOT                                                      |
| <=>      | Comparison with level of significance                            |
| in       | Checks if the value is in the array                              |
| is       | Checks if the value is equal to the specified value              |
| ===      | Checks if the value is equal to the specified value and type     |
| !==      | Checks if the value is not equal to the specified value and type |
| null     | Checks if the value is null                                      |
| ===      | Checks if the value is null or undefined                         |
| !==      | Checks if the value is not null or undefined                     |

It is important to understand the type comparison:

```php
$a = 5;
$b = "5";
if ($a == $b) {
echo "a is identical to b";
}
if ($a !== $b) {
echo "a is not identical to b or has a different type";
}
```

In this example, despite the fact that when converting types, the string "5" can be converted to the integer value "5", which happens with a simple comparison, in the case of comparison by value and type, it is not subject to conversion.

### Logical operators

There are several logical operators in PHP.

| Operator   | Description |
| ---------- | ----------- |
| &&         | Logical AND |
| Logical OR |
| !          | Logical NOT |
| xor        | Logical XOR |

The logical AND operator is used to check if two or more conditions are true. If at least one of the conditions is true, the result will be true.

The logical OR operator is used to check if at least one of the conditions is true. If at least one of the conditions is true, the result will be true.

The logical NOT operator is used to negate the result of a condition. If the condition is true, the result will be false. If the condition is false, the result will be true.

The logical XOR operator is used to check if two conditions are different. If the conditions are different, the result will be true. If the conditions are the same, the result will be false.

## Functions

A function is a block of code that can be called from other parts of the program. It is used to perform a specific task.

### Function declaration

The function declaration has the following syntax:

```php
function functionName(parameters) {
// code to be executed
}
```

The function name is the name of the function. It must start with a letter or an underscore. It can contain letters, numbers, and underscores.

The parameters are the input values that the function will receive. They are separated by commas.

The parameters can be of any type.

The code to be executed is enclosed in curly braces.

### Function calling

The function call has the following syntax:

```php
functionName(arguments);
```

The function name is the name of the function.

The arguments are the values that the function will receive. They are separated by commas.

The function will execute and return the result.

### Function return value

The function can return a value. It has the following syntax:

```php
function functionName(parameters) {
// code to be executed
return value;
}
```

The return value is the result of the function. It can be of any type.

### Function scope

The scope of a function is the part of the program where the function is valid. It is the part of the program where the function can access the variables and the code of the function.

### Function overloading

Function overloading is the ability to have multiple functions with the same name but with different parameters.

## Arrays

An array is a collection of values of the same type. It is used to store multiple values in a single variable.

### Array declaration

The array declaration has the following syntax:

```php
arrayName = array(element1, element2,..., elementN);
```

The arrayName is the name of the array.

The elements are the values that will be stored in the array. They are separated by commas.

### Array accessing

The array access has the following syntax:

```php
arrayName[index];
```

The index is the position of the element in the array. It can be an integer or a string.

The value of the element will be returned. If the index is out of range, the result will be null.

### Array iteration

The array iteration has the following syntax:

```php
foreach (arrayName as element) {
// code to be executed
}
```

### Array functions

There are several functions for arrays in PHP.

| Function             | Description                                                          |
| -------------------- | -------------------------------------------------------------------- |
| count()              | Returns the number of elements in the array                          |
| in_array()           | Checks if the value is in the array                                  |
| array_push()         | Adds an element to the end of the array                              |
| array_pop()          | Removes the last element from the array                              |
| array_shift()        | Removes the first element from the array                             |
| array_unshift()      | Adds an element to the beginning of the array                        |
| array_slice()        | Returns a slice of the array                                         |
| array_splice()       | Removes an element from the array                                    |
| array_merge()        | Merges two or more arrays                                            |
| array_keys()         | Returns the keys of the array                                        |
| array_values()       | Returns the values of the array                                      |
| array_count_values() | Returns the values and the count of each value in the array          |
| array_reverse()      | Reverses the order of the elements in the array                      |
| array_sum()          | Calculates the sum of the elements in the array                      |
| array_product()      | Calculates the product of the elements in the array                  |
| array_filter()       | Filters the elements in the array                                    |
| array_map()          | Applies a function to each element in the array                      |
| array_reduce()       | Reduces the array to a single value using a callback function        |
| array_chunk()        | Splits the array into chunks                                         |
| array_search()       | Searches for the value in the array and returns its key              |
| array_unique()       | Removes duplicate values from the array                              |
| array_intersect()    | Finds the common values between two or more arrays                   |
| array_diff()         | Finds the values in the first array that are not in the second array |
| array_merge()        | Merges two or more arrays                                            |
| array_pad()          | Pads the array with a specified value                                |
| array_slice()        | Returns a slice of the array                                         |
| array_splice()       | Removes an element from the array                                    |

### Array sorting

There are several functions for sorting arrays in PHP.

| Function                                  | Description                                                                                 |
| ----------------------------------------- | ------------------------------------------------------------------------------------------- |
| sort()                                    | Sorts the elements of the array                                                             |
| rsort()                                   | Sorts the elements of the array in reverse order                                            |
| asort()                                   | Sorts the elements of the array by value and keeps the keys                                 |
| arsort()                                  | Sorts the elements of the array by value in reverse order and keeps the keys                |
| sort($arrayName)                          | Sorts the elements of the array                                                             |
| rsort($arrayName)                         | Sorts the elements of the array in reverse order                                            |
| asort($arrayName)                         | Sorts the elements of the array by value and keeps the keys                                 |
| arsort($arrayName)                        | Sorts the elements of the array by value in reverse order and keeps the keys                |
| usort()                                   | Sorts the elements of the array using a user-defined comparison function                    |
| uasort()                                  | Sorts the elements of the array using a user-defined comparison function and keeps the keys |
| uksort()                                  | Sorts the elements of the array by key and keeps the values                                 |
| natcasesort()                             | Sorts the elements of the array using a case-insensitive natural order                      |
| rsort($arrayName, SORT_STRING)            | Sorts the elements of the array in reverse order using a case-insensitive natural order     |
| usort($arrayName, "myComparisonFunction") | Sorts the elements of the array using a user-defined comparison function                    |

### Array functions with multiple parameters

There are several functions for manipulating arrays with multiple parameters.

| Function                  | Description                                                                       |
| ------------------------- | --------------------------------------------------------------------------------- |
| array_fill()              | Fills the array with a value                                                      |
| array_fill_keys()         | Fills the array with keys                                                         |
| array_pad()               | Pads the array with a specified value                                             |
| array_slice()             | Returns a slice of the array                                                      |
| array_splice()            | Removes an element from the array                                                 |
| array_combine()           | Combines the elements of two or more arrays into a new array                      |
| array_intersect_key()     | Finds the common keys between two or more arrays                                  |
| array_diff_key()          | Finds the keys in the first array that are not in the second array                |
| array_exchange_array()    | Exchanges the elements of two or more arrays                                      |
| array_merge()             | Merges two or more arrays                                                         |
| array_merge_recursive()   | Merges two or more arrays recursively                                             |
| array_replace_recursive() | Replaces the elements of an array with the elements of another array              |
| array_replace()           | Replaces the elements of an array with the elements of another array              |
| array_combine_recursive() | Combines the elements of two or more arrays into a new array                      |
| array_combine_assoc()     | Combines the elements of two or more arrays into a new array with keys and values |

### Array destructuring

Array destructuring is the process of assigning the values of elements in an array to variables. It is used to simplify the assignment of values to variables.

The syntax for array destructuring is:

```php
$variableName = [$arrayName];
```

The $variableName is the name of the variable.

The $arrayName is the name of the array.

The values of the elements in the array will be assigned to the variables.

### Array functions with return values

There are several functions for manipulating arrays with return values.

| Function          | Description                                                          |
| ----------------- | -------------------------------------------------------------------- |
| array_sum()       | Calculates the sum of the elements in the array                      |
| array_product()   | Calculates the product of the elements in the array                  |
| array_filter()    | Filters the elements in the array                                    |
| array_map()       | Applies a function to each element in the array                      |
| array_reduce()    | Reduces the array to a single value using a callback function        |
| array_chunk()     | Splits the array into chunks                                         |
| array_search()    | Searches for the value in the array and returns its key              |
| array_unique()    | Removes duplicate values from the array                              |
| array_intersect() | Finds the common values between two or more arrays                   |
| array_diff()      | Finds the values in the first array that are not in the second array |
| array_merge()     | Merges two or more arrays                                            |
| array_pad()       | Pads the array with a specified value                                |
| array_slice()     | Returns a slice of the array                                         |
| array_splice()    | Removes an element from the array                                    |

### Array functions with multiple return values

There are several functions for manipulating arrays with multiple return values.

| Function             | Description                                                          |
| -------------------- | -------------------------------------------------------------------- |
| array_count_values() | Counts the occurrences of each value in the array                    |
| array_sum()          | Calculates the sum of the elements in the array                      |
| array_product()      | Calculates the product of the elements in the array                  |
| array_filter()       | Filters the elements in the array                                    |
| array_map()          | Applies a function to each element in the array                      |
| array_reduce()       | Reduces the array to a single value using a callback function        |
| array_chunk()        | Splits the array into chunks                                         |
| array_search()       | Searches for the value in the array and returns its key              |
| array_unique()       | Removes duplicate values from the array                              |
| array_intersect()    | Finds the common values between two or more arrays                   |
| array_diff()         | Finds the values in the first array that are not in the second array |

## Areas of views

Local variables are created inside a function and are available only within that function. They cannot be used outside the function, and their value is stored only during the execution of the function.

Each time the function is called, new local variables are created.
In our example:

```php
function getAverageScore(array $studentsArray) : float {
foreach ($students as $student) {
$summ += $student['score'];
}
return $summ / count($students);
}
echo $summ;// there will be an error here
```

We will not be able to access the $sum variable outside the function body. Also, the opposite is true – in the body of a function, you cannot access variables that are defined
outside it.

There is a mechanism that allows you to make such an appeal – global variables (variables that are defined outside of any function).

But using global variables is bad form in programming, as it is unclear where such a variable can change.

### Passing a parameter by reference

In PHP, function parameters are usually passed by value, that is, a copy of the variable value is inside the function, but not the variable itself:

```php
$counter = 0;
function incrementCounter(int $counter): int {
return $counter++;
}
echo $counter;
incrementCounter($counter);
echo $counter;// the value has not changed in any way
```

However, PHP also provides the possibility of passing parameters by reference, in which the function receives not a copy of the value, but the variable itself, and can change its value.

To pass a parameter by reference, the ampersand symbol & is used before the variable name when declaring it in the function definition.

```php
$counter = 0;
function incrementCounter(int &$counter): int {
$counter++;
}
echo $counter;
incrementCounter($counter);
echo $counter;
```

When passing parameters by reference, keep in mind that this can lead to unexpected results and errors, especially in cases where the variable changes in different parts of the code. Therefore, passing parameters by reference should be used only in cases where it is really necessary and justified.
