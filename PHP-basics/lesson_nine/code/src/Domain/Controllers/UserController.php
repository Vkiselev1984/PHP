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
        $csrfToken = $this->generateCsrfToken(); // Генерация CSRF-токена

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
                    'userRoles' => $userRoles, // Передаем роли в шаблон
                    'user_authorized' => isset($_SESSION['user_name']), // Передаем информацию о авторизации
                    'csrf_token' => $csrfToken // Передаем CSRF-токен
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

        try {
            $users = User::getAllUsersFromStorage($limit);
            error_log("Полученные пользователи: " . print_r($users, true)); // Отладочный вывод
            $usersData = [];

            if (count($users) > 0) {
                foreach ($users as $user) {
                    $userData = $user->getUserDataAsArray();
                    if (is_array($userData)) {
                        $usersData[] = $userData;
                    } else {
                        error_log("Ошибка: getUserDataAsArray не вернул массив для пользователя с ID: " . $user->getUserId());
                    }
                }
            }

            $response = [
                'success' => true,
                'data' => $usersData,
            ];
        } catch (\Exception $e) {
            error_log("Ошибка при получении пользователей: " . $e->getMessage());

            $response = [
                'success' => false,
                'error' => 'Не удалось получить данные пользователей.',
            ];
        }

        // Проверка перед кодированием в JSON
        if (!is_array($response)) {
            error_log("Ошибка: Ответ не является массивом перед json_encode.");
            $response = [
                'success' => false,
                'error' => 'Неверный формат ответа.',
            ];
        }

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
        $csrfToken = $this->generateCsrfToken(); // Генерация CSRF-токена

        return $render->renderPageWithForm(
            'user-auth.tpl',
            [
                'title' => 'Форма логина',
                'csrf_token' => $csrfToken, // Передаем CSRF-токен
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
        // Проверка CSRF-токена
        if (!isset($_POST['csrf_token']) || !$this->isCsrfTokenValid($_POST['csrf_token'])) {
            return json_encode(['success' => false, 'message' => 'Недействительный CSRF-токен.']);
        }

        try {
            $userId = $_POST['id'] ?? null;

            if ($userId === null) {
                return json_encode(['success' => false, 'message' => 'ID пользователя не указан.']);
            }

            $user = User::getUserById($userId);

            if ($user === null) {
                return json_encode(['success' => false, 'message' => 'Пользователь не найден.']);
            }

            $user->deleteFromStorage();

            // Возвращаем успешный ответ
            return json_encode(['success' => true, 'message' => 'Пользователь успешно удален.']);
        } catch (\Exception $e) {
            return json_encode(['success' => false, 'message' => 'Ошибка удаления: ' . $e->getMessage()]);
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

        $user = User::getUserById($userId);
        if ($user === null) {
            throw new \Exception("Пользователь не найден.");
        }

        // Генерация CSRF-токена для формы
        $csrfToken = $this->generateCsrfToken();

        $render = new Render();
        return $render->renderPageWithForm(
            'user-update.tpl',
            [
                'title' => 'Форма редактирования пользователя',
                'userId' => $userId,
                'name' => $user->getUserName(),
                'lastname' => $user->getUserLastName(),
                'login' => $user->getUserLogin(),
                'birthday' => date('d-m-Y', $user->getUserBirthday()), // Форматирование даты
                'csrf_token' => $csrfToken,
            ]
        );
    }

    protected function isCsrfTokenValid($token): bool
    {
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }

    public function actionUpdateUser(): string
    {
        $userId = $_POST['id'] ?? $_GET['id'] ?? null; // Получаем ID пользователя из POST или GET-запроса
        error_log("Полученный ID пользователя: " . $userId);
        $csrfToken = $_POST['csrf_token'] ?? null;

        // Проверка наличия ID пользователя
        if ($userId === null) {
            throw new \Exception("ID пользователя не указан.");
        }

        if ($csrfToken === null) {
            throw new \Exception("CSRF-токен не указан.");
        }

        // Проверка CSRF-токена
        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $csrfToken) {
            throw new \Exception("Неверный CSRF-токен.");
        }
        // Обновление пользователя
        $user = User::getUserById($userId);
        if ($user === null) {
            throw new \Exception("Пользователь не найден.");
        }

        // Устанавливаем параметры из данных запроса
        $user->setParamsFromRequestData();

        // Сохранение изменений
        $user->updateInStorage();

        // Перенаправление или отображение сообщения об успехе
        return "Пользователь успешно обновлен.";
    }
}