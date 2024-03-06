<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testAddCategories()
    {
        $book = $this->createMock(Book::class);

        $category = Category::create('new category');
        $book->addCategory($category);

        $acCategories = $book->getCategories();
        self::assertTrue($acCategories->contains($category));
    }
}