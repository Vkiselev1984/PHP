# Form processing

First, let's create a user-form template [user_form](/code/src/Domain/Views/user-form.twig).

It will have three fields that reflect all the entities necessary for our application. For this form, we will create a simple method in the [UserController](/code/src/Domain/Controllers/UserController.php):

```php
public function actionEdit(): string {
$render = new Render();
return $render->renderPage(
'user-form.twig',
[
'title' => 'User creation form'
]);
}
```

To save, we will leave the same actionSave method, but it will need to be modified, because in the form we specified the POST data transfer method. The GET method is not suitable for creating a user. Therefore, we need to correct several methods in the User model. To begin with, this will be the data validation method in [User model](/code/src/Domain/Models/User.php):

```php
public static function validateRequestData(): bool{
if(
isset($_POST['name']) && !empty($_POST['name']) &&
isset($_POST['lastname']) && !empty($_POST['lastname']) &&
isset($_POST['birthday']) && !empty($_POST['birthday'])
){
return true;
}
else{
return false;
}
}
```

Here we have simply changed the reference to the superglobal array.

We will do the same in the method for setting user parameters:

```php
public function setParamsFromRequestData(): void {
$this->userName = $_POST['name'];
$this->userLastName = $_POST['lastname'];
$this->setBirthdayFromString($_POST['birthday']);
}
```

Now, having filled in the form, we can press the "Save" button and we will be redirected to the page of successful creation of a new user.

As you can see, creating a handler itself is a fairly simple task. But we already
know that any data from the user is not trustworthy. Therefore, we need to be able to check them.

## Regular expressions

While the user's first and last name are simple strings, we require a rather strict format for the date. It is the day, month, and year, separated by a hyphen.

How can we check that the passed date matches the pattern we need?

PHP has a mechanism for regular expressions for this.

Regular expressions are a syntactic way of describing patterns for searching and matching strings in text. They are a powerful tool for working with text, allowing you to perform various operations, such as searching for substrings, replacing, breaking text into components, and checking for compliance with certain rules.

For example, the regular expression "\d+" will match the pattern "any sequence of one or more digits in text".

The regular expression "^[A-Za-z]+$" will match a string consisting only of Latin letters.

![Regular_expressions](/img/regular_expressions.png)

Let's try to create a regular expression to check the data in
our case. These should be in order:

- Two digits
- Hyphen
- Two digits
- Hyphen
- Four digits

Let's take a closer look at our expression

The ^ and $ symbols denote the beginning and end of the string, respectively.

If they are not specified, any string that includes a date in the format we need will pass the check.

In brackets, we specify the date pattern:

- \d says that we are looking for numbers
- {2} and {4} say that we are interested in exactly 2 or 4 digits, respectively
- Hyphens remain as is, they are not read as special characters

So, the expression will look like this:

```php
^(\d{2}-\d{2}-\d{4})$
```

By the way, you can check the correctness of the regular expression online. For example, on the website [https://regex101.com/](https://regex101.com/).

Now we need to plug this regular expression into our validator.

For this we need the built-in function preg_match. It takes the regular expression as its first parameter, and the string to check as its second parameter. It returns either the number of occurrences found, or false if no matches were found.

Now our validator code will look like this:

```php
public static function validateRequestData(): bool{
if(
isset($_POST['name']) && !empty($_POST['name']) &&
isset($_POST['lastname']) && !empty($_POST['lastname']) &&
preg_match('/^(\d{2}-\d{2}-\d{4})$/', $_POST['birthday'])
){
return true;
}
else{
return false;
}
}
```

## XSS attacks and protection against them

XSS (Cross-Site Scripting) is a type of web application attack in which an attacker injects malicious code (scripts) into a web page, which is then executed in the user's browser.

We use forms with text fields. And so far we have no protection on them from receiving user data.

Let's try to enter a string in the name field:

```JS
<script>alert('XSS! ');</script>
```

When saving, Twig will help us avoid danger, as it will turn tags into browser symbols. But let's assume that Twig doesn't work for us.

Let's modify the template of the created user so that it will skip HTML code. To do this, apply the raw filter to the message value.

```twig
<h3>{{ message | raw }}</h3>
```

Now when creating a user we will see a pop-up message.

![XSS](/img/xss.png)

Of course, it does not cause harm in itself, but it defines an attack vector.

Attackers can see that they can inject more serious malicious code.

To prevent XSS attacks, PHP has a function for escaping output. This is htmlspecialchars(), which converts special HTML characters into the corresponding HTML entities.

So, we can modify our [User](/code/src/Domain/Models/User.php) creation method:

```php
public function setParamsFromRequestData(): void {
$this->userName = htmlspecialchars($_POST['name']);
$this->userLastName = htmlspecialchars($_$_POST['lastname']);
$this->setBirthdayFromString($_POST['birthday']);
}
```

Now our application will not be subject to XSS attack.

![XSS_protection](/img/xss2.png)

## Cookies

Cookies are small text files on the user's computer that store some information from the server.

They can store:

- user preferences (language, site theme)
- viewed products
- date and time of the last visit

When the user performs some action, the server processes it and then sends information to the browser that some data should be written into cookies. This is sent in the response headers. After that, with each
HTTP request from the user, the browser will send cookies to the server.

Cookies can be temporary (they have a lifespan and are destroyed after it expires) and permanent. Which cookies to use on a particular site is decided by its developer.

Cookies themselves are not dangerous - they are ordinary text files.

They will not launch malicious processes. But they are not encrypted in any way, so they can be replaced on the user's computer. For example, if a virus has settled on the user's computer. Although, even a browser extension can do this.

Let's learn how to use Cookies with PHP!

To set a cookie in PHP, the setcookie() function is used.

Она принимает несколько параметров, включая имя, значение, срок действия в
секундах, путь, домен и другие опции:

```php
// Установка cookie на один час
setcookie('username', 'Иван Иванов', time() + 3600, '/');
```

Чтение значения cookie можно выполнить с помощью суперглобального массива

```php
$_COOKIE:
if (isset($_COOKIE['username'])) {
$username = $_COOKIE['username'];
echo "Привет, $username!";
} else {
echo "Привет, гость!";
}
```

Note that when you first set a cookie, it will not be in the superglobal yet. It will only be there with the user's next request.

To update the cookie value, you can simply reuse the setcookie() function, specifying a new value. For example:

```php
setcookie('username', 'Jane Smith', time() + 3600, '/');
```

To delete a cookie, you must set the expiration time in the past:

```php
setcookie('username', '', time() - 3600, '/');
```

It is important to note that to delete a cookie, you must specify the same path and domain values ​​that were used to set the cookie.

Cookies are insecure and should only be used to tag a user, but this data should be verified each time. For this, we will need a server response mechanism.

## Sessions

In PHP, a session is a mechanism that allows you to store information about a user on the server for a certain period of time without storing it in the database.

It provides the ability to track the user's state and save data between different requests.

Essentially, a session stores a set in the form of a key-value array.

When a session starts, PHP generates a unique identifier, which is saved on the client as cookies. This is how the user tells the server which of the stored sessions belongs to him. It is similar to an electronic key for entering an office.

A file is generated on the server side that contains a unique identifier.

To start a session in PHP, you need to call the session_start() function. This must be done at the very beginning of the script, before any content is displayed on the page. This function creates a unique session identifier for the current user and sets cookies with this identifier.

After the session starts, you can save and retrieve session data. Session data is stored in the $_SESSION superglobal array. For example, to save a value in a session, you can simply assign it to an element of the
$\_SESSION['key'] = 'value'; array.

To access the saved data, simply access the $\_SESSION['key'] array element.

When a session is no longer needed, it can be terminated by calling the session_destroy() function. This will delete all session data and reset the session identifier.

However, note that this function does not delete the actual session data files on the server, they will be deleted when the server cleans up stale sessions or after a certain period of inactivity.

The lifetime of a PHP session on the server is determined by several factors and can be configured in the PHP configuration. By default, PHP sessions are stored on the server for 24 minutes after the user last accessed the server.

However, it should be noted that the lifetime of a session can be changed in various ways. For example, you can change the value of session.gc_maxlifetime in PHP settings or use the session_set_cookie_params() function to set the session lifetime via cookie.

It is also worth considering that the session lifetime may be limited on the user's browser side. Browsers usually set the lifetime of cookies associated with the session, and if the cookie lifetime expires, the session may be considered as expired, despite the fact that it still exists on the server.

## Protection against CSRF attacks

Now we have all the tools to protect our forms from spoofing attacks.

When working with a form, we must generate a CSRF token. To do this, we will need to generate a random value for the CSRF token, which will then be checked for validity.

This value is saved in the session on the server side.

The CSRF token itself is placed in a hidden form field - this will be the signature of the form.

Even if an attacker on his side generates such a field, he is unlikely to guess its correct value.

When processing a request on the server, we will receive the CSRF token value from the submitted form. All we have to do is compare it with what is stored in the session.

If the values ​​do not match, the request may be rejected, since this may be an attempt at a CSRF attack.

Let's implement such protection in our application.

Let's start with the [Application class](/code/src/Application/Application.php) In the run method (and this is a single entry point), first of all, we will call the session start:

```php
public function run() : string {
session_start();
// …
}
```

Now, for convenience, let's create a method in the [Render class](/code/src/Application/Render.phpS) to generate a template with a form:

```php
public function renderPageWithForm(string $contentTemplateName =
'page-index.tpl', array $templateVariables = []) {
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$templateVariables['csrf_token'] = $_SESSION['csrf_token'];
return $this->renderPage($contentTemplateName, $templateVariables);
}
```

As you can see, we do not write the code twice here, but first we create our random token using the random_bytes function, which creates a random string of 32 bytes. Then we convert the bytes to a string using bin2hex.

We save the result in the session and pass it to the template. Since the further actions are no different from generating a template, at the end we call the standard rendering.

Now let's move to the [UserController](/code/src/Domain/Controllers/UserController.php) controller. There, in the actionEdit method, we call our rendering method:

```php
public function actionEdit(): string {
$render = new Render();
return $render->renderPageWithForm(
'user-form.twig',
[
'title' => 'User creation form'
]);
}
```

Then in the user-form.tpl template we place a hidden field that we will receive from the server:

```twig
<form action="/user/save/" method="post">
<input id="csrf_token" type="hidden" name="csrf_token" value="{{ csrf_token
}}">
<p>
<label for="user-name">Имя:</label>
<input id="user-name" type="text" name="name">
</p>
<p>
<label for="user-lastname">Фамилия:</label>
<input id="user-lastname" type="text" name="lastname">
</p>
<p>
<label for="user-birthday">День рождения:</label>
<input id="user-birthday" type="text" name="birthday"
placeholder="ДД-ММ-ГГГГ">
</p>
<p><input type="submit" value="Сохранить"></p>
</form>
```

Don't forget to clear the cache. If we did everything correctly, then in the form we will see a code like:

```html
<input id="csrf_token" type="hidden" name="csrf_token" value="32832832832832832832832832832
```

it's time to write a token handler!

We can do this already in the model in the validateRequestData method. But we have too many checks in it.

Therefore, the code needs to be made more readable. At the very beginning, we will create a variable for storing the result. Initially, it will have a value of true, but any failed check will turn it into false.

We will divide the [checks](/code/src/Domain/Models/User.php) into:

1. The presence of fields in principle
2. Checking the format of the birthday string
3. Checking the token

```php
public static function validateRequestData(): bool{
$result = true;
if(!(
isset($_POST['name']) && !empty($_POST['name']) &&
isset($_POST['lastname']) && !empty($_POST['lastname']) &&
isset($_POST['birthday']) && !empty($_POST['birthday'])
)){
$result = false;
}
if(!preg_match('/^(\d{2}-\d{2}-\d{4})$/', $_POST['birthday'])){
$result = false;
}
if(!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !=
$_POST['csrf_token']){
$result = false;
}
return $result;
}
```

Checking the token, as you can see, is quite simple. We check its presence in the session and the match of the form token with the token in the session. Now, if we did everything correctly, the form will continue to work. But if we change the token (make it invalid), the form will return an error saving data.

![xsrf](/img/csrf.png)

![csrf_incorrect](/img/csrf_incorrect.png)

## User identification on the site

Authentication is the process of verifying the identity or identity of a user or subject.

Authorization is the process of granting access rights after successful authentication.

### Login form

So, we should start with authentication. First, we will prepare a database. This is where we will store information about logins and passwords. We will use the same Users table.

Add two fields to it:

- login
- password_hash

```SQL
application1=# ALTER TABLE Users
ADD COLUMN login VARCHAR(255),
ADD COLUMN password_hash VARCHAR(255);
ALTER TABLE
application1=# UPDATE Users
SET login = '',  password_hash = '';
UPDATE 6
```

The first will store the username with which he will log in to the system.

The second will store the encrypted password.

### Storing passwords

Storing passwords in encrypted form is one of the important security measures , and here are some reasons why it is necessary:

1. Protection from unauthorized access: If passwords are stored in clear text, intruders or a malicious program can gain access to the database and learn the passwords of all users. Encrypted storage of passwords makes them unusable even if the database is compromised.
2. Ensuring confidentiality: Passwords are personal and sensitive data of users. Storing passwords in encrypted form helps to protect their confidentiality even if the database becomes accessible to intruders.
3. Protection from internal access: Not always threats come from outside. Internal employees or administrators may have access to the database. Storing passwords in encrypted form limits the possibility of unauthorized use or abuse of this access. For secure storage of passwords, it is recommended to use password hashing.

Hashing is the process of converting input data (such as a password or message) into a unique, fixed-length string called a hash value or simply a hash.

A hash function takes variable-length input and produces a fixed-length hash value, usually represented as a string of hexadecimal or binary characters. A good hash function has several important properties:

1. Deterministic: Given the same input, a hash function should always generate the same hash.
2. Unique: Even a small change in the input should result in a significantly different hash.
3. Irreversible: The hash value cannot be reconstructed from the original data. A good hash function should be resistant to reverse transformation.
4. Uniform distribution: A hash function should distribute hash values ​​evenly over the entire range of possible values.

Hashing is widely used in security and data integrity verification. One of the main applications is password hashing. Instead of storing passwords in cleartext, systems store their hash values. When checking authentication, the system hashes the password provided by the user and compares it with the stored hash. If the hashes match, the password is considered correct.

Thus, when hashing, the password is converted into a unique string of a fixed length, which makes it difficult to convert back to the original password.

Let's create a method to get a hash from a string to save the first password. To do this, create an Auth class in the application layer:

```php
namespace Geekbrains\Application1\Application;
class Auth {
public static function getPasswordHash(string $rawPassword): string {
return password_hash($_GET['pass_string'], PASSWORD_BCRYPT);
}
}
```

Method take string with an unencrypted password as input. Then, within itself, using the password_hash function, we get the hash value for the given password.

For convenience, let's create a method for obtaining the hash in [UserController](/code/src/Domain/Controllers/UserController.php):

```php
use Geekbrains\Application1\Application\Auth;

class UserController
{
public function actionHash(): string {
return Auth::getPasswordHash($_GET['pass_string']);
}
}
```

Now we can get a hash for our password through the browser. For example, like this:
http://mysite.local/user/hash/?pass_string=geekbrains

![Auth](/img/Auth.png)

We need to save this value in the DB. Let's create a record with ID 1 there. The login will be admin, and in the password field we will write the received string. This can be done manually directly through MySQL Workbench.

Let's add a user to the table

```SQL
application1=# INSERT INTO Users (user_name, user_lastname, user_birthday_timestamp, login, password_hash)
VALUES ('asmin', 'admin', 631152000, 'asmin', 'hashed_password');
INSERT 0 1
```

We are ready to describe the login logic. Let's embed the link in our [main.twig](/code/src/Domain/Views/main.twig) template:

```twig
<body>
<div id="header">
{% include "auth-template.tpl" %}
</div>

</body>
```

A special template, auth-template, will be connected to the header of our site:

```twig
{% if not user_authorized %}
<p><a href="/user/auth/">Login</a></p>
{% else %}
<p>Welcome to the site!</p>
{% endif %}
```

If the user has already passed authentication, we will show a greeting. Otherwise, a link to the login form. For this, we will create a new action in [UserController](/code/src/Domain/Controllers/UserController.php):

```php
public function actionAuth(): string {
$render = new Render();
return $render->renderPageWithForm(
'user-auth.twig',
[
'title' => 'Login form'
]);
}
```

The form called by the link [auth.twig](/code/src/Domain/Views/auth.twig) will look simple

```twig
{% if not auth-success %}
{{ auth-error }}
{% ecndif %}
<form action="/user/login/" method="post">
<input id="csrf_token" type="hidden" name="csrf_token" value="{{ csrf_token
}}">
<p>
<label for="user-login">Login:</label>
<input id="user-login" type="text" name="login">
</p>
<p>
<label for="user-password">Password:</label>
<input id="user-password" type="password" name="password">
</p>
<p><input type="submit" value="Login"></p>
</form>
```

As you can see, in the form we access the actionLogin method in the controller, which
will try to check the data and authenticate. Or return an error.
It's time to describe the logic of its work:

```php
public function actionLogin(): string {
$result = false;
if(isset($_POST['login']) && isset($_POST['password'])){
$result = Application::$auth->proceedAuth($_POST['login'],
$_POST['password']);
}
if(!$result){
$render = new Render();
return $render->renderPageWithForm(
'auth.twig',
[
'title' => 'Login form',
'auth-success' => false,
'auth-error' => 'Incorrect login or password'
]);
}
else{
header('Location: /');
return "";
}
}
```

Let's make it part of the application by modifying the [Application class](/code/src/Application/Application.php):

```php
public static Auth $auth;
public function __construct(){
Application::$config = new Config();
Application::$storage = new Storage();
Application::$auth = new Auth();
}
```

Now we will describe the login and password verification method in the Auth class:

```php
public function proceedAuth(string $login, string $password): bool{
$sql = "SELECT id_user, user_name, user_lastname, password_hash FROM
users WHERE login = :login";
$handler = Application::$storage->get()->prepare($sql);
$handler->execute(['login' => $login]);
$result = $handler->fetchAll();
if(!empty($result) && password_verify($password,
$result[0]['password_hash'])){
$_SESSION['user_name'] = $result[0]['user_name'];
$_SESSION['user_lastname'] = $result[0]['user_lastname'];
$_SESSION['id_user'] = $result[0]['id_user'];
return true;
}
else {
return false;
}
}
```

We need to be able to access the database to get the password hash stored there. This is done in the usual way, already familiar to us.

Next, we need to check that there is a record in the database for the login. And that the stored hash matches the transferred password. The second check is done by the built-in PHP function password_verify.

This function accepts the password in explicit form and the hash, checking whether they can match.

If the login and password are entered correctly, then we set the user's first and last name in the session, returning true as a result. Otherwise - false.

In the controller method, when false is received, we call the form generation again, indicating the error there. If the login and password are correct, then using the
header function we redirect our user to the main page.

There are a few cosmetic improvements left. In the renderPage method of the Render class,
we add the formation of the parameter:

```php
if(isset($_SESSION['user_name'])){
$templateVariables['user_authorized'] = true;
}
```

It will create a marker that the user is authorized. After all, in the header template we specified the if condition, according to which we either show the link or give a greeting.
