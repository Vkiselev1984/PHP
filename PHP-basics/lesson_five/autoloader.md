# How to create autoloader for PHP

For example you have class "Test" in Index.php and create some object:

```php
class Test {}
$test = new Test();
var_dump($test);
```

Let's say our application grows and there is a need to break a large code into parts. We will create the Test class in a separate file.

If we try to run Index.php we will get an error about the absence of a class.

Let's add the magic \_\_autoload function with class name as parameter:

```php
function __autoload($test) {
    var_dump($test);
}

$test = new Test();
var_dump($test);
```

This is a low-level function and printing it directly will cause an error in later versions, but in earlier versions you will see the class name printed, followed by an error.

![Autoload](/img/Autoload.png)

Now we need to upload the file using:

```php
function __autoload($test) {
require_once "$test.php";
}
```

Please note require_once will call the bootloader once. So we just need to use require.

```php
function __autoload($test) {
require "$test.php";
}

$test = new Test();
var_dump($test);
```

But be careful, if you add a third-party library to your application, a conflict will occur.

So we need to rename our autoloader function:

```php
function loader($test) {
require "$test.php";
}
```

To register our own autoloader we use spl_autoload_register(). Now you can register multiple autoload functions, and they will be called in the order they are registered.

```php
spl_autoload_register('loader');

function loader($test) {
require "$test.php";
}

$test = new Test();
var_dump($test);
```

However, PHP also supports standard autoloaders, such as PSR-4, which define how classes should be organized and loaded. Using autoloading standards can improve compatibility between different libraries and frameworks.

## Namespace

Let's talk a little more about classes and, in particular, namespaces - a mechanism that allows you to organize your code and avoid name conflicts between classes, functions, and constants. They are especially useful in large projects and when using third-party libraries, where name conflicts may arise.

A namespace is defined using the namespace keyword followed by the namespace name. For example:

```php
namespace MyProject\Controllers;
```

Namespaces can be nested, allowing you to create a hierarchy. For example:

```php
namespace MyProject\Controllers\Users;
```

To use classes, functions, or constants from another namespace, you can use the use statement. For example:

```php
namespace MyProject\Controllers;

use MyProject\Models\User;

class UserController {
    public function createUser() {
        $user = new User();
    }
}
```

If two classes have the same name, you can use a namespace to avoid conflict. For example:

```php
namespace MyProject\Models;
class User {}

namespace MyProject\Controllers;
class User {}
```

If you need to refer to a class or function in the global namespace, you can use \ before the name. For example:

```php
$date = new \DateTime();
```

Namespaces are often used in conjunction with class autoloading, allowing files and directories to be organized according to namespaces. For example, the MyProject\Controllers\UserController class might be located in the MyProject/Controllers/UserController.php file.

For example, in our test class we can define a namespace name:

```php
namespace MyProject\GB;

class Test {}
```

And then use it in our code:

```php
spl_autoload_register('loader');

function loader($test) {
require "src/$test.php";
}
$test = new MyProject\GB\Test();
var_dump($test);
```

### Structure and Hierarchy

Reflect your project structure: The structure of your namespaces should reflect the directory structure of your project. For example, if you have a folder src/Controllers, the namespace should be MyProject\Controllers.

Hierarchy: Use hierarchical namespaces to organize your code. For example:

```php
namespace MyProject\Controllers\User;
```

### Naming

Use meaningful names: Namespace names should be descriptive and reflect their contents.

For example, MyProject\Models for models and MyProject\Controllers for controllers.

Avoid abbreviations: Try to avoid abbreviations in namespace names to keep your code understandable.

Use PascalCase: Use PascalCase naming style for namespaces. For example, MyProject\Utilities.

### Avoid Global Namespaces

Minimize the use of global namespaces: Try to avoid using global namespaces if possible. Use namespaces for all classes, functions, and constants.

### Example of project structure

```
/my_project
    /src
        /Controllers
            UserController.php
            ProductController.php
        /Models
            User.php
            Product.php
        /Views
            user.twig
            product.twig
    /tests
        UserControllerTest.php
    composer.json
```
