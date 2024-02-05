<?php

namespace App\Form\Model;

use App\Entity\Book;

class BookDto
{
    public $title;
    public $base64Image;
    public $categories = [];

    public static function createFromBook(Book $book): self
    {
        $dto = new self();
        $dto->title = $book->getTitle();
        return $dto;
    }


}