<?php
// Настройки подключения к базе данных
$host = 'database'; // или ваш хост
$db = 'application1'; // имя вашей базы данных
$user = 'root'; // имя пользователя базы данных
$pass = 'root'; // пароль пользователя базы данных

try {
    // Подключение к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Логин и пароль
    $login = 'admin';
    $password = 'admin';

    // Хеширование пароля
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // SQL-запрос для вставки пользователя
    $sql = "INSERT INTO users (login, password) VALUES (:login, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':password', $hashedPassword);

    // Выполнение запроса
    if ($stmt->execute()) {
        echo "Пользователь admin успешно создан.";
    } else {
        echo "Ошибка при создании пользователя.";
    }
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>