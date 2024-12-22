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
        'actionSave' => ['admin'],
        'actionDeleteUser' => ['admin'],
        'actionUpdateUser' => ['admin'],
    ];

    public function actionIndex(): string
    {
        $users = User::getAllUsersFromStorage();
        $userRoles = $this->getUserRoles(); // Получаем роли пользователя

        $render = new Render();

        if (!$users) {
            return $render->renderPage(
                'user-empty.tpl',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список пуст или не найден"
                ]
            );
        } else {
            return $render->renderPage(
                'user-index.tpl',
                [
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users,
                    'userRoles' => $userRoles // Передаем роли в шаблон
                ]
            );
        }
    }

    public function actionIndexRefresh()
    {
        $limit = null;

        if (isset($_POST['maxId']) && ($_POST['maxId'] > 0)) {
            $limit = $_POST['maxId'];
        }

        $users = User::getAllUsersFromStorage($limit);
        $usersData = [];

        if (count($users) > 0) {
            foreach ($users as $user) {
                $usersData[] = $user->getUserDataAsArray();
            }
        }

        $response = [
            'success' => true,
            'data' => $usersData,
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function actionSave(): string
    {
        if (User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();

            $render = new Render();

            return $render->renderPage(
                'user-created.tpl',
                [
                    'title' => 'Пользователь создан',
                    'message' => "Создан пользователь " . $user->getUserName() . " " . $user->getUserLastName()
                ]
            );
        } else {
            throw new \Exception("Переданные данные некорректны");
        }
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
        $render = new Render();

        return $render->renderPageWithForm(
            'user-auth.tpl',
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
        $render = new Render();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                return $render->renderPageWithForm(
                    'user-auth.tpl',
                    [
                        'title' => 'Форма логина',
                        'auth-success' => false,
                        'auth-error' => 'Ошибка CSRF токена'
                    ]
                );
            }

            if (isset($_POST['login']) && isset($_POST['password'])) {
                $result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
                if (!$result) {
                    return $render->renderPageWithForm(
                        'user-auth.tpl',
                        [
                            'title' => 'Форма логина',
                            'auth-success' => false,
                            'auth-error' => 'Неверные логин или пароль'
                        ]
                    );
                }
                // Успешная аутентификация
                header('Location: /');
                exit; // Завершение скрипта после перенаправления
            }
        }

        // Если это GET-запрос, отображаем форму логина
        return $render->renderPageWithForm(
            'user-auth.tpl',
            [
                'title' => 'Форма логина',
                'auth-success' => false,
                'auth-error' => ''
            ]
        );
    }

    public function actionLogout()
    {
        echo "Logout action triggered"; // Отладочное сообщение
        session_unset();
        session_destroy();
        header('Location: /'); // Измените на нужный маршрут
        exit();
    }

    public function actionDeleteUser(): string
    {
        try {
            $userId = $_POST['id'] ?? null;

            if ($userId === null) {
                return json_encode(['success' => false, 'message' => 'User ID is required.']);
            }

            $user = User::getUserById($userId);

            if ($user === null) {
                return json_encode(['success' => false, 'message' => 'User not found.']);
            }

            $user->deleteFromStorage();
            return json_encode(['success' => true]);
        } catch (\Exception $e) {
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function generateCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public function actionEditUser(): string
    {
        $userId = $_GET['id'] ?? null;
        if ($userId === null) {
            throw new \Exception("ID пользователя не указан.");
        }

        $render = new Render();
        return $render->renderPageWithForm(
            'user-update.tpl',
            [
                'title' => 'Форма редактирования пользователя',
                'userId' => $userId, // Передаем только ID пользователя
                'name' => '', // Пустое поле для имени
                'lastname' => '', // Пустое поле для фамилии
                'login' => '', // Пустое поле для логина
                'birthday' => '', // Пустое поле для дня рождения
                // Убираем csrf_token
            ]
        );
    }

    public function actionUpdateUser(): string
    {
        // Проверка входных данных
        if (!User::validateRequestData()) {
            throw new \Exception("Переданные данные некорректны");
        }

        // Получение ID пользователя из POST-запроса
        $userId = $_POST['id'] ?? null;
        if ($userId === null) {
            return "ID пользователя не указан.";
        }

        // Загрузка существующего пользователя
        $user = User::getUserById($userId);
        if ($user === null) {
            return "Пользователь не найден.";
        }

        // Установка параметров из запроса
        $user->setParamsFromRequestData();

        // Проверка наличия ключа "birthday"
        $birthday = $_POST['birthday'] ?? null;
        if ($birthday === null) {
            // Обработка случая, когда день рождения не был передан
        }

        // Сохранение обновленных данных
        try {
            $user->updateInStorage(); // Предполагается, что у вас есть метод для обновления
            $message = "Пользователь " . $user->getUserName() . " " . $user->getUserLastName() . " был обновлен.";
        } catch (\Exception $e) {
            return "Ошибка обновления: " . $e->getMessage();
        }

        // Отображение страницы с сообщением об успешном обновлении
        $render = new Render();
        return $render->renderPage(
            'user-update.tpl',
            [
                'title' => 'Пользователь обновлен',
                'message' => $message
            ]
        );
    }
}