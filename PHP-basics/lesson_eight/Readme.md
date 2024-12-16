## Prepare for testing

Identify routes: Make sure you know what routes you have and what methods they call. For example, you have a route to save a user (/user/save/) that calls the actionSave method.

Prepare invalid data: Determine what data you will send to cause an error. For example:

- Submitting empty fields.
- Submitting invalid data formats (for example, a string instead of a date).
- Submitting data that does not match the expected requirements (for example, strings that are too long).

### Test routes with invalid data

- Submit empty data:
  Open a form and leave all fields empty, then submit the form.
- Expected result: The actionSave method should throw an exception or return an error message.
  Submit invalid data:
- For example, submit a string instead of a date in the birthday field.

Expected Result: The actionSave method should handle this and return an error message.

Submit data that exceeds the allowed lengths:

- For example, enter strings that are too long in the name or lastname fields.

Expected Result: The actionSave method should handle this and return an error message.

### Analyze Errors

Check the Logs: After submitting invalid data, check your web server and PHP logs. The logs may contain information about what happened and help you figure out where the error occurred.

Debug Code: If you don't see any errors in the logs, add logging to the actionSave method to see what data is coming in and how it is being processed.

Handle Exceptions: Make sure your code handles exceptions correctly. For example, if the data is invalid, you should throw an exception and return a meaningful error message.

### Troubleshoot Errors

Validate Data: Make sure you have data validation in the validateRequestData method. For example, check that the fields are not empty and have the correct formats:

```php
public static function validateRequestData(): bool
{
if (empty($_POST['name']) || empty($\_POST['lastname']) || empty($_POST['login']) || empty($\_POST['password'])) {
return false; // Not all fields are filled in
}

// Additional checks, such as date format
if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $\_POST['birthday'])) {
return false; // Invalid date format
}

return true; // All checks pass
}
```

Error Handling: Make sure you return meaningful error messages to the user. For example, if the data is invalid, you can return a message:

```php
if (!User::validateRequestData()) {
return $this->render->renderPage(
'user-form.twig',
[
'title' => 'Error',
'error' => 'Invalid data. Please check your input.'
]
);
}
```

### Retest

After making changes, retest with invalid data to ensure that errors are handled correctly and meaningful messages are returned to the user.

## ActionIndexRefresh method

### Testing

Send an invalid maxId:

- Send an invalid value, such as a string instead of a number.
  Expected result: The method should process this value and return an empty array or an error message.

### Analysis

- Check how the getAllUsersFromStorage method handles invalid values. If it does not handle them properly, this can lead to errors.

### Fix

Add validation for maxId in actionIndexRefresh method:

```php
public function actionIndexRefresh()
{
$limit = $_POST['maxId'] ?? null;

if (!is_numeric($limit)) {
return json_encode(['error' => 'Invalid identifier']);
}

$users = User::getAllUsersFromStorage($limit);

}
```

## ActionEdit Method

### Testing

Check what happens when the user id is missing:

- Try calling the method without passing the user id.
  Expected result: the method should return an error or redirect to an error page.

### Analysis

Make sure the method handles the missing id correctly.

### Fix

Add a check for the presence of the id:

```php
public function actionEdit(): string
{
$userId = $_GET['id'] ?? null;

if (!$userId) {
return $this->render->renderPage(
'error.twig',
[
'title' => 'Error',
'message' => 'User id not specified.'
]
);
}

}
```

## ActionAuth Method

### Testing

Submit empty login and password data:

- Open the form and leave the fields empty, then submit the form.
  Expected result: The method should return an error message.

### Analysis

Check how proceedAuth handles empty data.

### Fix

Add validation to actionAuth method:

```php
public function actionAuth(): string
{
if (empty($_POST['login']) || empty($_POST['password'])) {
return $this->render->renderPageWithForm(
'user-auth.twig',
[
'title' => 'Login form',
'auth-success' => false,
'auth-error' => 'Login and password cannot be empty.'
]
);
}

}
```

## ActionHash Method

### Test

Send an empty string for pass_string:

- Try calling the method with an empty parameter.
  Expected result: the method should return an error or a message that the string is empty.

### Analysis

Make sure the getPasswordHash method handles empty strings.

### Fix

Add a check for an empty string:

```php
public function actionHash(): string
{
$passString = $_GET['pass_string'] ?? '';

if (empty($passString)) {
return json_encode(['error' => 'Password string cannot be empty.']);
}

return Auth::getPasswordHash($passString);
}

```

## ActionLogin Method

### Test

Send invalid login and password data:

- Try sending invalid login and password.
  Expected result: The method should return an error message.

### Analysis

Check how the proceedAuth method handles invalid data.

### Fix

Make sure the method returns understandable error messages:

```php
public function actionLogin(): string
{
$result = false;

if (isset($_POST['login']) && isset($_POST['password'])) {
$result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
}

if (!$result) {
return $this->render->renderPageWithForm(
'user-auth.twig',
[
'title' => 'Login form',
'auth-success' => false,
'auth-error' => 'Incorrect login or password'
]
);
}

header('Location: /');
return "";
}
```

## ProceedAuth method in Auth class

### Testing

Send empty login and password data:

- Try calling the method with empty strings.
  Expected result: the method should return an error or a message that the data is incorrect.

### Analysis

Check how the method handles empty data.

### Fix

Add validation to the proceedAuth method:

```php
public function proceedAuth($login, $password)
{
if (empty($login) || empty($password)) {
return false;
}

}
```

## SetParamsFromRequestData in User class

### Test

Send invalid data:

- Try calling the method with invalid data, such as strings instead of the expected types.
  Expected result: The method should handle this value and return an error or error message.

### Analysis

Check how the method handles invalid data.

### Fix

Add validation for each parameter:

```php
public function setParamsFromRequestData()
{
$this->name = isset($_POST['name']) ? trim($_POST['name']) : '';
$this->lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
$this->login = isset($_POST['login']) ? trim($_POST['login']) : '';
$this->password = isset($_POST['password']) ? trim($_POST['password']) : '';
$this->birthday = isset($_POST['birthday']) ? trim($_POST['birthday']) : '';

if (empty($this->name) || empty($this->lastname) || empty($this->login) || empty($this->password)) {
throw new \Exception("Incorrect user data");
}
}
```

## SaveToStorage in User class

### Testing

Try saving a user with invalid data:

- Try calling the method with invalid data, such as empty strings.
  Expected result: the method should return an error or a message that the data is invalid.

### Analysis

Check how the method handles invalid data.

### Fix

Add validation before saving:

```php
public function saveToStorage()
{
if (empty($this->name) || empty($this->lastname) || empty($this->login) || empty($this->password)) {
throw new \Exception("Incorrect data to save");
}

}
```

## GetPasswordHash method in Auth class

### Test

Send an empty string for the password:

- Try calling the method with an empty password.
  Expected result: The method should return an error or a message that the password string is empty.

### Analysis

Check how the method handles empty strings.

### Fix

Add a check for an empty string:

```php
public static function getPasswordHash($passString)
{
    if (empty($passString)) {
        throw new \Exception("Password cannot be empty");
    }

    return password_hash($passString, PASSWORD_BCRYPT);
}
```

## ValidateRequestData method in User class

### Test

Send invalid data:

- Try calling the method with empty data or data that does not meet the expected requirements.
  Expected result: the method should return false or an error message.

### Analysis

Check how the method handles empty or invalid data.

### Fix

Add validation for each field:

```php
public static function validateRequestData(): bool
{
if (empty($_POST['name']) || empty($_POST['lastname']) || empty($_POST['login']) || empty($_POST['password'])) {
return false; // Not all fields are filled in
}

// Additional checks, such as date format
if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $_POST['birthday'])) {
return false; // Invalid date format
}

return true; // All checks passed
}
```

## GetUserById method in User class

### Testing

Try calling the method with an invalid identifier:

- Try calling the method with a non-existent identifier or a string instead of a number.
  Expected result: the method should return null or an error message.

### Analysis

Check how the method handles invalid identifiers.

### Fix

Add identifier validation:

```php
public static function getUserById($id)
{
if (!is_numeric($id)) {
return null;
}

}
```

## ValidateUserData method in User class

### Test

- Try calling the method with invalid data:
  Try calling the method with empty strings or data that does not meet the expected requirements.
  Expected result: the method should return false or an error message.

### Analysis

Check how the method handles empty or invalid data.

### Fix

Add validation for each field:

```php
public static function validateUserData($data): bool
{
if (empty($data['name']) || empty($data['lastname']) || empty($data['login']) || empty($data['password'])) {
return false;
}

if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $data['birthday'])) {
return false;
}

return true;
}
```

## GetUserRole method in User class

### Testing

Try calling the method with an invalid identifier:

- Try calling the method with a non-existent identifier or a string instead of a number.
  Expected result: the method should return null or an error message.

### Analysis

Check how the method handles invalid identifiers.

### Fix

Add identifier validation:

```php
public static function getUserRole($id)
{
if (!is_numeric($id)) {
return null;
}

}
```

## RegisterUser method in Auth class

### Testing

Try registering a user with incorrect data:

- Try calling the method with empty strings for login, password, and other required fields.
  Expected result: the method should return an error or a message that the data is incorrect.

### Analysis

Check how the method handles empty data.

### Fix

Add validation before registration:

```php
public function registerUser($login, $password, $email)
{
if (empty($login) || empty($password) || empty($email)) {
throw new \Exception("Login, password, and email cannot be empty");
}

}
```

## GetUserByEmail method in User class

### Testing

Try calling the method with an incorrect email:

- Try calling the method with an empty string or a non-existent email.
  Expected result: the method should return null or an error message.

### Analysis

Check how the method handles invalid emails.

### Fix

Add email validation:

```php
public static function getUserByEmail($email)
{
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
return null;
}

}
```

## SendPasswordResetLink method in the Auth class

### Testing

Try sending a password reset link with an invalid email:

- Try calling the method with an empty string or a non-existent email.
  Expected result: the method should return an error or a message that the email is invalid.

### Analysis

Check how the method handles invalid emails.

### Fix

Add email validation:

```php
public function sendPasswordResetLink($email)
{
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
throw new \Exception("Invalid email for password reset");
}

}
```

## VerifyUser method in the Auth class

### Testing

Try to verify the user with invalid data:

- Try to call the method with empty strings for the login and password.
  Expected result: the method should return an error or a message that the data is incorrect.

### Analysis

Check how the method handles empty data.

### Fix

Add validation before user verification:

```php
public function verifyUser($login, $password)
{
if (empty($login) || empty($password)) {
throw new \Exception("Login and password cannot be empty");
}

}
```

## Construct класса Config

To add handling of invalid data and improve its robustness. We will add validation to check the existence and readability of the configuration file, as well as handling possible errors when parsing the file.

### Fix

```php
public function __construct()
{
$address = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->defaultConfigFile;
var_dump($address);

if (!file_exists($address)) {
throw new \Exception("Configuration file not found: " . $address);
}

if (!is_readable($address)) {
throw new \Exception("Configuration file not available for reading: " . $address);
}

$parsedConfig = parse_ini_file($address, true);
if ($parsedConfig === false) {
throw new \Exception("Error parsing configuration file: " . $address);
}

$this->applicationConfiguration = $parsedConfig;
}
```

- Checking if a file exists: If the file does not exist, an exception is thrown with the appropriate message.
- Checking if a file is readable: If the file exists but is not readable, an exception is thrown.
- Checking if the parsing result is true: If parse_ini_file returns false, it means that there was an error while parsing and an exception is thrown.
