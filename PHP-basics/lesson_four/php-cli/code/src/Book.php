<?php
namespace App\Oop;

abstract class Book
{
    protected $type;
    protected $title;
    protected $author;
    protected $acquisitionMethod;
    protected $readCount;

    public function __construct($type, $title, $author, $acquisitionMethod)
    {
        $this->type = $type;
        $this->title = $title;
        $this->author = $author;
        $this->acquisitionMethod = $acquisitionMethod;
        $this->readCount = 0;
    }

    public function incrementReadCount()
    {
        $this->readCount++;
    }

    public function getReadCount()
    {
        return $this->readCount;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getAcquisitionMethod()
    {
        return $this->acquisitionMethod;
    }

    abstract public function getDetails();
}