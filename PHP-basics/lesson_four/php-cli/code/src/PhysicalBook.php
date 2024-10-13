<?php
namespace App\Oop;

class PhysicalBook extends Book
{
    protected $location;

    public function __construct($title, $author, $acquisitionMethod, $location)
    {
        parent::__construct('Physical', $title, $author, $acquisitionMethod);
        $this->location = $location;
    }

    public function getDetails()
    {
        return "Title: {$this->title}, Author: {$this->author}, Location: {$this->location}, Read Count: {$this->readCount}";
    }

    public function getLocation()
    {
        return $this->location;
    }
}