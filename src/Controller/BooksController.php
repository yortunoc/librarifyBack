<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CommentsBookRepository;
use App\Service\Book\GetBook;
use App\Service\CommentBook\CommentBookFormProcessor;
use App\Service\CommentBook\CreateCommentBook;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
    public function detailsBook(string $id, GetBook $getBook, CommentsBookRepository $commentsBookRepository)
    {
        try {
            $book = ($getBook)($id);
            $commentsBook = $commentsBookRepository->findBy(['id_book' => $book->getId()]);
        } catch (Exception $exception) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return $this->render('detailsBook.html.twig', ['book' => $book, 'commentsBook' => $commentsBook]);
    }

    /**
     * @Route("/books/{id}/comments")
     */
    public function save_comment(Request  $request, string $id, CommentBookFormProcessor $commentBookFormProcessor,
                                 Security $security, GetBook $getBook, CommentsBookRepository $commentsBookRepository)
    {
        if ($security->isGranted('ROLE_USER')) {
            $user = $security->getUser();
        }
        [$commentBook, $error] = ($commentBookFormProcessor)($request, $id, $user->getId());
        $book = ($getBook)($id);
        $commentsBook = $commentsBookRepository->findBy(['id_book' => $book->getId()]);
        return $this::render('detailsBook.html.twig', ['book' => $book, 'commentsBook' => $commentsBook]);
    }
}