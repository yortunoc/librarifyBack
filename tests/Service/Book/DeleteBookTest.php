<?php

namespace App\Tests\Service\Book;

use App\Entity\Book;
use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;
use App\Service\Book\DeleteBook;
use App\Service\Book\GetBook;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeleteBookTest extends TestCase
{
    public function testDeleteBookWithValidId()
    {
        $getBookMock = $this->createMock(GetBook::class);
        $bookMock = Book::create('', null, null, null, null, [], [], []);
        $getBookMock
            ->method('__invoke')
            ->willReturn($bookMock);
        $bookRepositoryMock = $this->createMock(BookRepository::class);
        $bookRepositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with($bookMock);
        $deleteBook = new DeleteBook($getBookMock, $bookRepositoryMock);
        $deleteBook(Uuid::uuid4()->toString());

    }

    public function testDeleteBookWithInValidId()
    {
        $getBookMock = $this->createMock(GetBook::class);
        $getBookMock
            ->method('__invoke')
            ->willThrowException(new BookNotFound());
        $bookRepositoryMock = $this->createMock(BookRepository::class);
        $bookRepositoryMock
            ->expects($this->never())
            ->method('delete');
        $deleteBook = new DeleteBook($getBookMock, $bookRepositoryMock);
        $this->expectException(BookNotFound::class);
        $deleteBook(Uuid::uuid4()->toString());

    }
}