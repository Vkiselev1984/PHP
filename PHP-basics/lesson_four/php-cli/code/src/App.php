<?php
namespace App\Oop;

use App\Oop\Library;

class App
{
    private $library;

    public function __construct()
    {
        $this->library = new Library();
    }
    private $csvFile = 'Books.csv';

    public function runCommand($args)
    {
        echo "Running command with arguments: " . implode(', ', $args) . "\n";
        switch ($args[0]) {
            case 'view':
                return $this->library->viewBooks();
            case 'add':
                return $this->library->addBookInteractive();
            case 'delete':
                return $this->library->deleteBook(isset($args[1]) ? $args[1] : null);
            case 'get':
                return $this->library->getBookInteractive();
            default:
                return "Unknown command.";
        }
    }
}