<?php

namespace Geekbrains\Application1\Application;

class Auth
{
    public static function getPasswordHash(string $rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_BCRYPT);
    }

    public function proceedAuth(string $login, string $password): bool
    {
        echo "Starting the authorization process for the user: $login\n";

        $sql = "SELECT id_user, name, lastname, password_hash FROM users WHERE login = :login";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['login' => $login]);
        $result = $handler->fetchAll();
        var_dump($result);

        if (!empty($result)) {
            echo "User found. Verifying password...\n";
            if (password_verify($password, $result[0]['password_hash'])) {
                echo "Password correct. Authorization successful.\n";
                $_SESSION['name'] = $result[0]['name'];
                $_SESSION['lastname'] = $result[0]['lastname'];
                $_SESSION['id_user'] = $result[0]['id_user'];

                return true;
            } else {
                echo "Password is incorrect.\n";
            }
        } else {
            echo "User not found.\n";
        }

        return false;
    }
}