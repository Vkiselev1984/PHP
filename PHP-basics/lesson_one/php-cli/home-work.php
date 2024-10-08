Execute the code in the PHP CLI container and explain what this code will output and why:

<?php
$a = 5;
$b = '05';
var_dump($a == $b);
var_dump((int) '012345');
var_dump((float) 123.0 === (int) 123.0);
var_dump(0 == 'hello, world');
?>

In the container with PHP CLI, change the PHP version from 8.2 to 7.4. Will the output change?
Using only two numeric variables, swap their values. For example, if a = 1, b = 2, it is necessary that it turns out: b
= 1, a = 2. Additional variables, functions and constructs of the list() type cannot be used.

The code will output:

$a == $b
Here we compare the variable $a (which is equal to 5) and the variable $b (which is equal to the string '05'). The ==
operator performs a non-strict comparison, which means that PHP will convert the string '05' to a number before the
comparison. As a result, '05' will be converted to 5. Therefore, the result will be true. Output: bool(true)

(int) '012345'
Here the string '012345' is converted to an integer. When converting a string to an integer, PHP ignores leading zeros,
so the result will be 12345. Output: int(12345)

(float) 123.0 === (int) 123.0
Here, a strict comparison takes place (using the === operator), which checks both the value and the type. (float) 123.0
returns 123.0 (float type), and (int) 123.0 returns 123 (int type). Since the types are different (float and int), the
result will be false. Output: bool(false)


0 == 'hello, world'
Here there is a non-strict comparison of the number 0 and the string 'hello, world'. When comparing, PHP will try to
convert the string 'hello, world' to a number. Since the string does not start with a digit, it will be converted to 0.
Output: bool(true) in version 7.4, but in versions 8.2 and higher, due to modifications, the answer will be false. In
later versions, PHP improved the handling of non-strict comparison, and now a string that cannot be converted to a
number will not be cast to 0 in the context of comparison with 0.