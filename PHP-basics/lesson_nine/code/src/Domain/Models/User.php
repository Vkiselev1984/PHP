<?php

namespace Geekbrains\Application1\Domain\Models;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Infrastructure\Storage;
use Geekbrains\Application1\Application\Auth;

class User
{

    private ?int $userId;

    private ?string $userName;

    private ?string $userLastName;
    private ?int $userBirthday;

    private ?string $userLogin;
    private ?string $userPassword;

    private static string $storageAddress = '/storage/birthdays.txt';

    public function __construct(int $id = null, string $name = null, string $lastName = null, int $birthday = null)
    {
        $this->userId = $id;
        $this->userName = $name;
        $this->userLastName = $lastName;
        $this->userBirthday = $birthday;
    }

    public function setName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function setLastName(string $userLastName): void
    {
        $this->userLastName = $userLastName;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function getUserLastName(): ?string
    {
        return $this->userLastName;
    }

    public function getUserBirthday(): ?int
    {
        return $this->userBirthday;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }


    public function setBirthdayFromString(string $birthdayString): void
    {
        $this->userBirthday = strtotime($birthdayString);
    }

    public static function getAllUsersFromStorage(?int $limit = null): array
    {
        $sql = "SELECT * FROM users";

        if (isset($limit) && $limit > 0) {
            $sql .= " WHERE id_user > " . (int) $limit;
        }

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute();
        $result = $handler->fetchAll();

        $users = [];

        foreach ($result as $item) {
            $birthdayTimestamp = isset($item['user_birthday']) && !empty($item['user_birthday'])
                ? strtotime($item['user_birthday'])
                : null;
            $user = new User($item['id_user'], $item['name'], $item['lastname'], $birthdayTimestamp);
            $users[] = $user;
        }

        return $users;
    }

    public static function validateRequestData(): bool
    {
        $result = true;
        if (
            !(
                isset($_POST['name']) && !empty($_POST['name']) &&
                isset($_POST['lastname']) && !empty($_POST['lastname']) &&
                isset($_POST['birthday']) && !empty($_POST['birthday'])
            )
        ) {
            $result = false;
        }
        if (!preg_match('/^(\d{2}-\d{2}-\d{4})$/', $_POST['birthday'])) {
            $result = false;
        }
        if (
            !isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !=
            $_POST['csrf_token']
        ) {
            $result = false;
        }
        return $result;
    }




    public function setParamsFromRequestData(): void
    {
        $this->userName = $_POST['name'];
        $this->userLastName = $_POST['lastname'];
        $this->setBirthdayFromString($_POST['birthday']);
    }


    public function saveToStorage()
    {
        $sql = "INSERT INTO users(name, lastname, user_birthday_timestamp, `login`, password_hash) VALUES (:name, :lastname, :user_birthday, :login, :password)";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute([
            'name' => $this->userName,
            'lastname' => $this->userLastName,
            'user_birthday' => $this->userBirthday,
            'login' => $this->userLogin,
            'password' => $this->userPassword
        ]);
    }

    public function getUserDataAsArray(): array
    {
        $userArray = [
            'id' => $this->userId,
            'username' => $this->userName,
            'userlastname' => $this->userLastName,
            'userbirthday' => date('d.m.Y', $this->userBirthday)
        ];

        return $userArray;
    }
    public static function getUserById(int $id): ?User
    {
        $sql = "SELECT * FROM users WHERE id_user = :id";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['id' => $id]);
        $result = $handler->fetch();

        if ($result) {
            return new User($result['id_user'], $result['name'], $result['lastname'], strtotime($result['user_birthday']));
        }

        return null;
    }

    public function updateInStorage(): void
    {
        $sql = "UPDATE users SET name = :name, lastname = :lastname, user_birthday_timestamp = :user_birthday, login = :login, password_hash = :password WHERE id_user = :id";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute([
            'id' => $this->userId,
            'name' => $this->userName,
            'lastname' => $this->userLastName,
            'user_birthday' => $this->userBirthday,
            'login' => $this->userLogin,
            'password' => $this->userPassword
        ]);
    }

    public static function deleteUser(int $id): void
    {
        $sql = "DELETE FROM users WHERE id_user = :id";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['id' => $id]);
    }
}