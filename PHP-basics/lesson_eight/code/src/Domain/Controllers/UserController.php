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

        if (!$users) {
            return $this->render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список пуст или не найден"
                ]
            );
        }

        return $this->render->renderPage(
            'user-index.twig',
            [
                'title' => 'Список пользователей в хранилище',
                'users' => $users
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
        if (User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();

            return $this->render->renderPage(
                'user-created.twig',
                [
                    'title' => 'Пользователь создан',
                    'message' => "Создан пользователь " . $user->getUserName() . " " . $user->getUserLastName()
                ]
            );
        }

        throw new \Exception("Переданные данные некорректны");
    }

    public function actionEdit(): string
    {
        return $this->render->renderPageWithForm(
            'user-form.twig',
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
                'title' => 'Форма логина'
            ]
        );
    }

    public function actionHash(): string
    {
        return Auth::getPasswordHash($_GET['pass_string']);
    }

    public function actionLogin(): string
    {
        error_log("actionLogin called"); // Добавьте это сообщение для проверки

        $result = false;

        if (isset($_POST['login']) && isset($_POST['password'])) {
            // Логирование входных данных
            error_log("Login attempt: " . $_POST['login']);
            $result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
        } else {
            error_log("Login or password not set.");
        }

        if (!$result) {
            error_log("Authentication failed.");
            return $this->render->renderPageWithForm(
                'user-auth.twig',
                [
                    'title' => 'Форма логина',
                    'auth-success' => false,
                    'auth-error' => 'Неверные логин или пароль'
                ]
            );
        }

        header('Location: /');
        return "";
    }
}