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
                    'title' => 'List of users in storage',
                    'message' => "List is empty or not found"
                ]
            );
        }

        return $this->render->renderPage(
            'user-index.twig',
            [
                'title' => 'List of users in storage',
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
            $user->setParamsFromRequestData(); // Установить параметры из POST данных
            $user->saveToStorage(); // Сохранить данные в хранилище
            return json_encode(['success' => true]);
        }
        return json_encode(['success' => false, 'message' => 'Ошибка валидации данных']);
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
            $user->setParamsFromRequestData(); // Установить параметры из POST данных
            $user->updateInStorage(); // Обновить данные в хранилище
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

    public function actionLogin(): string
    {
        error_log("actionLogin called");

        $result = false;

        if (isset($_POST['login']) && isset($_POST['password'])) {
            error_log("Login attempt: " . $_POST['login']);

            // Отладочное сообщение для отображения пароля (не рекомендуется в реальных приложениях)
            // error_log("Password attempt: " . $_POST['password']); // Не выводите пароль в лог!

            $result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);

            if ($result) {
                error_log("Authentication successful for user: " . $_POST['login']);
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