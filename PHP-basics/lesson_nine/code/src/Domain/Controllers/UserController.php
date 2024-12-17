<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Application\Auth;
use Geekbrains\Application1\Domain\Models\User;

class UserController extends AbstractController
{
    protected array $actionsPermissions = [
        'actionHash' => ['admin', 'some'],
        'actionSave' => ['admin']
    ];

    private Render $render;

    public function __construct()
    {
        $this->render = new Render();
    }

    public function actionIndex(): string
    {
        $users = User::getAllUsersFromStorage();
        if (!isset($_SESSION['id_user'])) {
            return $this->render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'List of users in storage',
                    'message' => "User not authenticated",
                    'isAdmin' => false
                ]
            );
        }

        $userId = $_SESSION['id_user'];
        $user = User::getUserById($userId);

        // Проверка на существование пользователя
        if ($user === null) {
            return $this->render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'List of users in storage',
                    'message' => "User not found",
                    'isAdmin' => false
                ]
            );
        }

        $isAdmin = in_array('admin', $user->getUserRoles($userId));

        return $this->render->renderPage(
            'user-index.twig',
            [
                'title' => 'List of users in storage',
                'users' => $users,
                'isAdmin' => $isAdmin
            ]
        );
    }
    public function actionIndexRefresh()
    {
        $limit = $_POST['maxId'] ?? null;

        $users = User::getAllUsersFromStorage($limit);
        $usersData = [];

        if (!empty($users)) {
            foreach ($users as $user) {
                $usersData[] = $user->getUserDataAsArray();
            }
        }

        return json_encode($usersData);
    }

    public function actionSave(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                return json_encode(['success' => false, 'message' => 'Неверный CSRF-токен']);
            }

            $name = $_POST['name'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            $birthday = $_POST['birthday'] ?? '';

            if (User::getUserByLogin($login)) {
                return json_encode(['success' => false, 'message' => 'Пользователь с таким логином уже существует.']);
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $user = new User();
            $user->setParamsFromRequestData($name, $lastname, $login, $passwordHash, $birthday);
            $user->saveToStorage();

            return json_encode(['success' => true, 'message' => 'Пользователь успешно зарегистрирован!']);
        }

        return json_encode(['success' => false, 'message' => 'Неверный метод запроса.']);
    }

    public function actionDelete(int $userId): string
    {
        User::deleteUser($userId);
        return json_encode(['success' => true]);
    }

    public function actionUpdate(int $userId): string
    {
        $user = User::getUserById($userId);
        if ($user) {
            $name = $_POST['name'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $login = $_POST['login'] ?? '';
            $passwordHash = $_POST['passwordHash'] ?? '';
            $birthday = $_POST['birthday'] ?? '';
            $user->setParamsFromRequestData($name, $lastname, $login, $passwordHash, $birthday);
            $user->updateInStorage();
            return json_encode(['success' => true]);
        }
        return json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    }
    public function actionEdit(): string
    {
        $render = new Render();
        return $render->renderPageWithForm(
            'user-form.tpl',
            [
                'title' => 'Форма создания пользователя'
            ]
        );
    }


    public function actionAuth(): string
    {
        return $this->render->renderPageWithForm(
            'user-auth.twig',
            [
                'title' => 'Login form'
            ]
        );
    }

    public function actionHash(): string
    {
        return Auth::getPasswordHash($_GET['pass_string']);
    }

    public function actionRegister(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';

            if (User::getUserByLogin($login)) {
                return $this->render->renderPageWithForm(
                    'user-register.twig',
                    [
                        'title' => 'Registration',
                        'error' => 'Пользователь с таким логином уже существует.'
                    ]
                );
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            User::createUser($name, $lastname, $login, $passwordHash);

            return $this->render->renderPage(
                'user-success.twig',
                [
                    'title' => 'Registration Successful',
                    'message' => 'Вы успешно зарегистрированы!'
                ]
            );
        }

        return $this->render->renderPageWithForm(
            'user-register.twig',
            [
                'title' => 'Registration'
            ]
        );
    }

    public function actionLogin(): string
    {
        error_log("actionLogin called");

        $result = false;

        if (isset($_POST['login']) && isset($_POST['password'])) {
            error_log("Login attempt: " . $_POST['login']);

            // Не выводите пароль в лог!
            // error_log("Password attempt: " . $_POST['password']); 

            $result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);

            if ($result) {
                error_log("Authentication successful for user: " . $_POST['login']);
                if (is_array($result) && isset($result[0]['password_hash'])) {
                    error_log("Stored password hash: " . $result[0]['password_hash']);
                }
            } else {
                error_log("Authentication failed for user: " . $_POST['login']);
            }
        } else {
            error_log("Login or password not set.");
        }

        if (!$result) {
            error_log("Authentication failed.");
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
        error_log("Redirecting to home page after successful login.");
        return "";
    }
}