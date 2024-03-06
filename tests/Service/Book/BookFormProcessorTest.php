<?php

namespace App\Tests\Service\Book;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Form\Model\AuthorDto;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Service\Author\CreateAuthor;
use App\Service\Author\GetAuthor;
use App\Service\Book\BookFormProcessor;
use App\Service\Book\GetBook;
use App\Service\Category\CreateCategory;
use App\Service\Category\GetCategory;
use App\Service\FileUploader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class BookFormProcessorTest extends TestCase
{
    public function testGetBookDtoEmpty()
    {
        $bookFormProcessor = $this->getBookFromProcessor();

        $result =$bookFormProcessor->getBooksEntity(null);

        $this->assertIsArray($result);
        $this->assertInstanceOf(BookDto::class, $result[0]);
        $this->assertNull($result[1]);

    }

    public function testGetBookDtoAndBook()
    {

        $getBookMock = $this->createConfiguredMock(GetBook::class,
            ['__invoke' => Book::create('title')]);

        $bookFormProcessor = $this->getBookFromProcessor($getBookMock);

        $result =$bookFormProcessor->getBooksEntity(Uuid::uuid4());

        $this->assertIsArray($result);
        $this->assertInstanceOf(BookDto::class, $result[0]);
        $this->assertInstanceOf(Book::class, $result[1]);
    }


    public function testAddCommentsWithEmptyComments()
    {
        $bookFormProcessor = $this->getBookFromProcessor();

        $bookDto = new BookDto();
        $bookDto->comments = [];

        $comments = $bookFormProcessor->addComments($bookDto);

        $this->assertCount(0, $comments);
    }

    public function testAddCategories()
    {
        $bookDto = new BookDto();
        $categoryDto = $this->createMock(CategoryDto::class);

        $bookDto->categories = [$categoryDto];

        $categoryDto->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $categoryDto->expects($this->once())
            ->method('getName')
            ->willReturn('name');

        $createCategoryMock = $this->createMock(CreateCategory::class);


        $createCategoryMock->expects($this->once())
            ->method('__invoke')
            ->willReturn(Category::create('name'));

        $bookFormProcessor = $this->getBookFromProcessor(createCategory: $createCategoryMock);

        $comments = $bookFormProcessor->addCategories($bookDto);

        $this->assertCount(1, $comments);
    }

    public function testAddAuthorWithEmptyAuthor()
    {
        $bookFormProcessor = $this->getBookFromProcessor();

        $bookDto = new BookDto();
        $bookDto->comments = [];

        $comments = $bookFormProcessor->addAuthors($bookDto);

        $this->assertCount(0, $comments);
    }

    public function testAddAuthors()
    {
        $bookFormProcessor = $this->getBookFromProcessor();

        $bookDto = new BookDto();
        $categoryDto = $this->createMock(AuthorDto::class);
        $categoryDto->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $bookDto->comments = [$categoryDto];

        $comments = $bookFormProcessor->addComments($bookDto);

        $this->assertCount(1, $comments);
    }

    public function testAddDuplicateAuthors()
    {
        $bookDto = new BookDto();
        $authorDtoMock = $this->createMock(AuthorDto::class);
        $authorDtoMock->expects($this->atMost(2))
            ->method('getId')
            ->willReturn(Uuid::uuid4());

        $getAuthorMock = $this->createMock(GetAuthor::class);

        $getAuthorMock->expects($this->once())
            ->method('__invoke')
            ->willReturn(Author::create('author'));

        $bookDto->authors = [$authorDtoMock];

        $authorDtoMock->expects($this->never())
            ->method('getName');

        $bookFormProcessor = $this->getBookFromProcessor(getAuthorMock: $getAuthorMock);
        $comments = $bookFormProcessor->addAuthors($bookDto);

        $this->assertCount(1, $comments);
    }


    public function testGetForm()
    {
        $bookDto = $this->createMock(BookDto::class);
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $bookFormProcessor = $this->getBookFromProcessor(formFactoryMock: $formFactoryMock);

        $requestData = '{"title": "Test Book"}';
        $request = new Request(content: $requestData);

        $formMock = $this->createMock(FormInterface::class);
        $formFactoryMock->expects($this->once())
            ->method('create')
            ->with(BookFormType::class, $bookDto)
            ->willReturn($formMock);

        $formMock->expects($this->once())
            ->method('submit')
            ->with(json_decode($requestData, true));

        $formResult = $bookFormProcessor->getForm($request, $bookDto);

        $this->assertInstanceOf(FormInterface::class, $formResult);
    }


    public function getBookFromProcessor(
        ?MockObject $getBookMock =null,
        ?GetAuthor $getAuthorMock =null,
        ?CreateCategory $createCategory = null,
        ?FormFactoryInterface $formFactoryMock= null): BookFormProcessor
    {

        $getBookMock = $getBookMock ?? $this->createMock(GetBook::class);
        $bookRepositoryMock = $this->createMock(BookRepository::class);
        $getCategoryMock = $this->createMock(GetCategory::class);
        $createCategoryMock = $createCategory ?? $this->createMock(CreateCategory::class);
        $createAuthorMock = $this->createMock(CreateAuthor::class);
        $getAuthorMock = $getAuthorMock ?? $this->createMock(GetAuthor::class);
        $fileUploaderMock = $this->createMock(FileUploader::class);
        $formFactoryMock = $formFactoryMock ?? $this->createMock(FormFactoryInterface::class);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        return new BookFormProcessor(
            $getBookMock,
            $bookRepositoryMock,
            $getCategoryMock,
            $createCategoryMock,
            $createAuthorMock,
            $getAuthorMock,
            $fileUploaderMock,
            $formFactoryMock,
            $eventDispatcherMock
        );
    }

}