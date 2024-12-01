<?php

namespace Geekbrains\Application1\Models;

class User
{
    private string $userName; // Username
    private string $userBirthday; // Date of birth
    private static string $storageAddress = '/storage/birthdays.txt'; // Path to file

    public function __construct(string $name, string $birthday)
    {
        $this->userName = $name; // Assign a value to the property
        $this->userBirthday = $birthday; // Assign a value to the property
    }

    public function getUserName(): string
    {
        return $this->userName; // Return the username
    }

    public function getUserBirthday(): string
    {
        return $this->userBirthday; // Return the date of birth
    }

    public function save(): bool
    {
        // Path to the file where users will be stored
        $filePath = $_SERVER['DOCUMENT_ROOT'] . self::$storageAddress; // Use the class property

        // Read existing users from the file
        $users = [];
        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $users = json_decode($jsonData, true) ?? [];
        }

        // Check for duplicates
        foreach ($users as $existingUser) {
            if ($existingUser['name'] === $this->getUserName() && $existingUser['birthday'] === $this->getUserBirthday()) {
                echo "User " . $this->getUserName() . " with birthdate " . $this->getUserBirthday() . " already exists.";
                return false; // Don't save if user already exists
            }
        }

        // Add new user to array
        $users[] = [
            'name' => $this->getUserName(), // Use new getter
            'birthday' => $this->getUserBirthday(), // Use new getter
        ];

        // Save updated array back to file
        $jsonData = json_encode($users, JSON_PRETTY_PRINT);
        $result = file_put_contents($filePath, $jsonData);

        // Debug messages
        if ($result === false) {
            echo "Error saving data to file.";
        } else {
            echo "User " . $this->getUserName() . "with date of birth" . $this->getUserBirthday() . "successfully saved.";
        }

        return $result !== false;
    }

    public static function getAllUsersFromStorage(): array|false
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . self::$storageAddress; // Use the class property

        if (file_exists($address) && is_readable($address)) {
            $jsonData = file_get_contents($address);
            $userArray = json_decode($jsonData, true);

            $users = [];
            if (is_array($userArray)) {
                foreach ($userArray as $item) {
                    if (isset($item['name']) && isset($item['birthday'])) {
                        $user = new User(
                            trim($item['name']), // Username
                            trim($item['birthday']) // Date of birth
                        );
                        $users[] = $user;
                    } else {
                        // Debug message for invalid format
                        echo "Invalid element format: " . json_encode($item);
                    }
                }
            }

            return $users;
        } else {
            return false;
        }
    }

    public function setBirthdayFromString(string $birthday): void
    {
        $this->userBirthday = $birthday; // Set the date of birth
    }
}