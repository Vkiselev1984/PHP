<?php
require '/data/mysite.local/src/Domain/Models/User.php';
use Geekbrains\Application1\Domain\Models\User;

// Создание нового пользователя
$newUser = new User(null, 'test_login', 'Test', 'User', strtotime('2000-01-01'));
$newUser->saveToStorage();

// Получение всех пользователей
$users = User::getAllUsersFromStorage();
foreach ($users as $user) {
    echo 'ID: ' . $user->getUserId() . ', Name: ' . $user->getUserName() . ', Last Name: ' . $user->getUserLastName() . "\n";
}
?>