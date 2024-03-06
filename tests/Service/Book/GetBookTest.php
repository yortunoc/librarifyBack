<?php

namespace App\Tests\Service\Book;

use App\Entity\Book;
use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;
use App\Service\Book\GetBook;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class GetBookTest extends TestCase
{
    public function testGetBookWithValidId()
    {
        $bookRepositoryMock = $this->createMock(BookRepository::class);

        $bookMock = $this->createMock(Book::class);
        $bookRepositoryMock
            ->method('find')
            ->willReturn($bookMock);

        $getBook = new GetBook($bookRepositoryMock);

        $result = $getBook(Uuid::uuid4()->toString());

        $this->assertInstanceOf(Book::class, $result);
    }

    public function testGetBookWithInvalidId()
    {
        $bookRepositoryMock = $this->createMock(BookRepository::class);

        $bookRepositoryMock
            ->method('find')
            ->willReturn(null);

        $getBook = new GetBook($bookRepositoryMock);

        $this->expectException(BookNotFound::class);

        $getBook(Uuid::uuid4()->toString());
    }
}