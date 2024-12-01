<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;

class UserController
{
    public function actionIndex()
    // Defines the actionIndex method that will handle requests to display the list of users.
    {
        $users = User::getAllUsersFromStorage();
        // Calls the static getAllUsersFromStorage method of the User class to get all users from the storage.
// The result is stored in the $users variable.

        $render = new Render();
        // Creates a new instance of the Render class that will be used to render templates.

        if (!$users) {
            // Checks if there are users. If not, the next block is executed.
            return $render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'List of users in storage',
                    'message' => "List is empty or not found"
                ]
            );
            // Calls the renderPage method of the Render class, passing it the name of the template 'user-empty.twig' and an array with data.
// In this case, the array contains the title and a message that the list is empty.
        } else {
            // If users are found, the next block is executed.
            return $render->renderPage(
                'user-index.twig',
                [
                    'title' => 'List of users in storage',
                    'users' => $users
                ]
            );
            // Calls the renderPage method of the Render class, passing it the name of the template 'user-index.twig' and an array with data.
// In this case, the array contains the title and the list of users.
        }
    }

    public function actionSave()
    // Defines the actionSave method that will handle requests to save a new user.
    {
        // Get parameters from the request
        $name = $_GET['name'] ?? '';
        $birthday = $_GET['birthday'] ?? '';
        // Extracts the 'name' and 'birthday' parameters from the GET request. If the parameters are missing, sets the default value to an empty string.

        // Check for empty values
        if (empty($name) || empty($birthday)) {
            return "Name and birthdate cannot be empty.";
            // If either value is empty, returns an error message.
        }

        // Create a new user
        $user = new User($name, $birthday);
        // Creates a new instance of the User class, passing it the name and birthdate.

        // Saving the user
        if ($user->save()) {
            return "User $name with birth date $birthday successfully saved.";
            // If save() returns true, returns a message about successful saving of the user.
        } else {
            return "Error saving user.";
            // If save() returns false, returns an error message.
        }
    }
}