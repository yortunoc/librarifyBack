<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CommentsBookRepository;
use App\Service\Book\DeleteBook;
use App\Service\Book\GetBook;
use App\Service\CommentBook\CommentBookFormProcessor;
use App\Service\CommentBook\DeleteCommentBook;
use App\Service\CommentBook\GetCommentBook;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function detailsBook(string   $id, GetBook $getBook, CommentsBookRepository $commentsBookRepository)
    {
        try {
            $book = ($getBook)($id);
            $commentsBook = $commentsBookRepository->findBy(['id_book' => $book->getId()], ['createdAt' => 'DESC']);
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
        if ($security->isGranted('ROLE_USER') or $security->isGranted('ROLE_ADMIN')) {
            $user = $security->getUser();
        }
        [$commentBook, $error] = ($commentBookFormProcessor)($request, $id, $user->getId());
        $book = ($getBook)($id);
        $commentsBook = $commentsBookRepository->findBy(['id_book' => $book->getId()], ['createdAt' => 'DESC']);
        return $this::render('detailsBook.html.twig', ['book' => $book, 'commentsBook' => $commentsBook]);
    }

    /**
     * @Route("/books/{id_book}/comments/{id}")
     */
    public function editComment(Request $request, string $id_book, string $id,
                                CommentBookFormProcessor $commentBookFormProcessor,
                                Security $security, GetBook $getBook, CommentsBookRepository $commentsBookRepository)
    {
        if ($security->isGranted('ROLE_USER') or $security->isGranted('ROLE_ADMIN')) {
            $user = $security->getUser();
        }
        [$commentBook, $error] = ($commentBookFormProcessor)($request, $id_book, $user->getId(), $id);
        $book = ($getBook)($id_book);
        $commentsBook = $commentsBookRepository->findBy(['id_book' => $book->getId()], ['createdAt' => 'DESC']);
        return $this::render('detailsBook.html.twig', ['book' => $book, 'commentsBook' => $commentsBook]);
    }

    /**
     * @Route("/books/{id_book}/comments/{id}/update")
     */
    public function form_to_update_comment(string  $id_book, string $id, GetCommentBook $getCommentBook,
                                           GetBook $getBook)
    {
        $commentBook = ($getCommentBook)($id);
        $book = ($getBook)($id_book);
        return $this::render('editCommentBook.html.twig', ['book' => $book, 'commentBook' => $commentBook]);
    }

    /**
     * @Route("/books/{id_book}/comments/{id}/delete")
     */
    public function deleteComment(string  $id_book,string  $id, GetBook $getBook, DeleteCommentBook $deleteCommentBook, CommentsBookRepository $commentsBookRepository,
                                  Security $security)
    {
        try {
            ($deleteCommentBook)($id);
            $book = ($getBook)($id_book);
            $commentsBook = $commentsBookRepository->findBy(['id_book' => $book->getId()], ['createdAt' => 'DESC']);
        } catch (Exception $exception) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return new RedirectResponse('/books/'.$book->getId());
    }
}