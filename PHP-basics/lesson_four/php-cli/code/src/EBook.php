<?php
namespace App\Oop;

class EBook extends Book
{
    private $downloadLink;

    public function __construct($title, $author, $acquisitionMethod, $downloadLink)
    {
        parent::__construct('EBook', $title, $author, $acquisitionMethod);
        $this->downloadLink = $downloadLink;
    }

    public function getDetails()
    {
        return "Title: " . $this->getTitle() . ", Author: " . $this->getAuthor() . ", Download Link: {$this->downloadLink}, Read Count: " . $this->getReadCount();
    }
    public function getDownloadLink()
    {
        return $this->downloadLink;
    }
}