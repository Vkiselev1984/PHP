<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UserController
{

    public function actionIndex(): string
    {
        $users = User::getAllUsersFromStorage();

        $render = new Render();

        if (!$users) {
            return $render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'List of users in storage',
                    'message' => "The list is empty or not found"
                ]
            );
        } else {
            return $render->renderPage(
                'user-index.twig',
                [
                    'title' => 'List of users in storage',
                    'users' => $users
                ]
            );
        }
    }

    public function actionSave(): string
    {
        if (User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();

            $render = new Render();

            return $render->renderPage(
                'user-created.twig',
                [
                    'title' => 'User created',
                    'message' => "User created" . $user->getUserName() . " " . $user->getUserLastName()
                ]
            );
        } else {
            throw new \Exception("The transmitted data is incorrect");
        }
    }

    public function actionUpdate(): string
    {
        if (!isset($_GET['id']) || !User::exists($_GET['id'])) {
            throw new \Exception("User does not exist");
        }

        $user = new User();
        $user->setUserId($_GET['id']);

        $arrayData = [];

        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $arrayData['user_name'] = $_GET['name'];
        }

        if (isset($_GET['lastname']) && !empty($_GET['lastname'])) {
            $arrayData['user_lastname'] = $_GET['lastname'];
        }

        if (empty($arrayData)) {
            throw new \Exception("No data to update");
        }

        $user->updateUser($arrayData);

        $render = new Render();
        return $render->renderPage(
            'user-created.twig',
            [
                'title' => 'User updated',
                'message' => "User updated " . $user->getUserId()
            ]
        );
    }

    public function actionDelete(): string
    {
        if (User::exists($_GET['id'])) {
            User::deleteFromStorage($_GET['id']);

            $render = new Render();

            return $render->renderPage(
                'user-removed.twig',
                []
            );
        } else {
            throw new \Exception("User does not exist");
        }
    }
}