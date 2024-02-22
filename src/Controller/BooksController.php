<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\Book\GetBook;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{

    /**
     * @Route("/books")
     */
    public function showBooks(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render('book.html.twig', ['books' => $books]);
    }

    /**
     * @Route("/books/{id}")
     */
    public function detailsBook(string $id, GetBook $getBook)
    {
        try {
            $book = ($getBook)($id);
        } catch (Exception $exception) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return $this->render('detailsBook.html.twig', ['book' => $book]);
    }
}