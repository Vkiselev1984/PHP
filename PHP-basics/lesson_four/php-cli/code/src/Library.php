<?php

namespace App\Oop;

class Library
{
    private $books = [];
    private $csvFile;
    private $libraries = [];

    public function __construct()
    {
        $this->loadConfig();
        $this->loadBooks();
        $this->loadLibraries();
    }

    private function loadConfig()
    {
        $config = parse_ini_file('config.ini', true);
        if (isset($config['storage']['address'])) {
            $this->csvFile = $config['storage']['address'];
        } else {
            throw new \Exception("CSV file address not found in config.");
        }
    }

    private function loadBooks()
    {
        if (file_exists($this->csvFile)) {
            $file = new \SplFileObject($this->csvFile);
            while (!$file->eof()) {
                $line = $file->fgetcsv();
                if ($line && count($line) >= 6) {
                    list($type, $title, $author, $method, $readCount, $locationOrLink) = $line;
                    if ($type === 'Physical') {
                        $book = new PhysicalBook($title, $author, $method, $locationOrLink);
                    } else {
                        $book = new EBook($title, $author, $method, $locationOrLink);
                    }
                    for ($i = 0; $i < $readCount; $i++) {
                        $book->incrementReadCount();
                    }
                    $this->books[] = $book;
                } else {
                    echo "Skipping invalid line: " . implode(',', $line) . "\n";
                }
            }
        }
    }

    private function loadLibraries()
    {
        if (file_exists('libraries.csv')) {
            $file = new \SplFileObject('libraries.csv');
            while (!$file->eof()) {
                $line = $file->fgetcsv();
                if ($line) {
                    list($name, $address) = $line;
                    $this->libraries[$name] = $address;
                }
            }
        }
    }

    public function addBook($type, $title, $author, $method, $extra, $location)
    {
        if ($type === 'Physical') {
            $book = new PhysicalBook($title, $author, $method, $location);
        } else {
            $book = new EBook($title, $author, $method, $location);
        }
        $this->books[] = $book;
        $this->saveBooks();
        return "Book added.";
    }

    public function deleteBook($title = null)
    {
        if ($title === null) {
            echo "Enter the title of the book to delete: ";
            $handle = fopen("php://stdin", "r");
            $title = trim(fgets($handle));
        }

        $bookFound = false;
        foreach ($this->books as $key => $book) {
            if ($book->getTitle() === $title) {
                unset($this->books[$key]);
                $this->saveBooks();
                echo "Book '{$title}' has been deleted.\n";
                $bookFound = true;
                break;
            }
        }

        if (!$bookFound) {
            echo "Book not found.\n";
        }
    }

    public function getBook($title)
    {
        foreach ($this->books as $book) {
            if ($book->getTitle() === $title) {
                $book->incrementReadCount();
                $this->saveBooks();
                return $book->getDetails();
            }
        }
        return "Book not found.";
    }

    public function viewBooks()
    {
        $output = "";
        foreach ($this->books as $book) {
            $output .= $book->getDetails() . "\n";
        }
        return $output;
    }

    public function getBookByTitleOrAuthor($input)
    {
        foreach ($this->books as $book) {
            if (strcasecmp($book->getTitle(), $input) === 0 || strcasecmp($book->getAuthor(), $input) === 0) {
                $book->incrementReadCount();
                $this->saveBooks();
                return $book->getDetails();
            }
        }
        return "Book not found.";
    }

    private function saveBooks()
    {
        $file = fopen($this->csvFile, 'w');
        foreach ($this->books as $book) {
            $line = [
                $book->getType(),
                $book->getTitle(),
                $book->getAuthor(),
                $book->getAcquisitionMethod(),
                $book->getReadCount(),
                $book instanceof PhysicalBook ? $book->getLocation() : $book->getDownloadLink(),
            ];
            fputcsv($file, $line);
        }
        fclose($file);
    }

    public function getBookInteractive()
    {
        echo "Enter book title or author: ";
        $input = trim(fgets(STDIN));

        $bookDetails = $this->getBookByTitleOrAuthor($input);
        return $bookDetails;
    }

    public function addBookInteractive()
    {
        echo "Enter book type (Physical/EBook): ";
        $type = trim(fgets(STDIN));

        echo "Enter book title: ";
        $title = trim(fgets(STDIN));

        echo "Enter book author: ";
        $author = trim(fgets(STDIN));

        echo "Enter acquisition method: ";
        $method = trim(fgets(STDIN));

        echo "Enter any extra information (if applicable): ";
        $extra = trim(fgets(STDIN));

        echo "Enter library location or link: ";
        $location = trim(fgets(STDIN));

        return $this->addBook($type, $title, $author, $method, $extra, $location);
    }
}
