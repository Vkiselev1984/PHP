<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;

class AbstractController
{

    protected array $actionsPermissions = [];
    protected array $userRolesCache = [];

    public function getUserRoles(): array
    {

        if (!empty($this->userRolesCache)) {
            return $this->userRolesCache;
        }

        $roles = [];

        if (isset($_SESSION['id_user'])) {
            $rolesSql = "SELECT * FROM user_roles WHERE id_user = :id";

            try {
                $handler = Application::$storage->get()->prepare($rolesSql);
                $handler->execute(['id' => $_SESSION['id_user']]);
                $result = $handler->fetchAll();

                if (!empty($result)) {
                    foreach ($result as $role) {
                        $roles[] = $role['role'];
                    }
                }
            } catch (\PDOException $e) {
                // Логирование ошибки или обработка исключения
                error_log("Ошибка при получении ролей пользователя: " . $e->getMessage());
            }
        }

        // Сохраняем роли в кэш
        $this->userRolesCache = $roles;
        return $roles;
    }

    public function getActionsPermissions(string $methodName): array
    {
        return $this->actionsPermissions[$methodName] ?? [];
    }
}