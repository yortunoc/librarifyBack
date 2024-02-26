<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use App\Repository\CommentsBookRepository;
use App\Service\Book\BookFormProcessor;
use App\Service\Book\DeleteBook;
use App\Service\Book\GetBook;
use App\Service\CommentBook\CommentBookFormProcessor;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Throwable;

class BooksController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        BookRepository $bookRepository
    ) {
        return $bookRepository->findAll();
    }

    /**
     * @Rest\Get(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(string $id, GetBook $getBook)
    {
        try {
            $book = ($getBook)($id);
        } catch (Exception $exception) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return $book;
    }

    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        BookFormProcessor $bookFormProcessor,
        Request $request
    ) {
        [$book, $error] = ($bookFormProcessor)($request);
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Put(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        string $id,
        BookFormProcessor $bookFormProcessor,
        Request $request
    ) {
//        try {
            [$book, $error] = ($bookFormProcessor)($request, $id);
            $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $book ?? $error;
            return View::create($data, $statusCode);
//        } catch (Throwable $t) {
//            dump($t);
//            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
//        }
    }

    /**
     * @Rest\Patch(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function patchAction(
        string $id,
        GetBook $getBook,
        Request $request
    ) {
        $book = ($getBook)($id);
        $data = json_decode($request->getContent(), true);
        $book->patch($data);
        return View::create($book, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(string $id, DeleteBook $deleteBook)
    {
        try {
            ($deleteBook)($id);
        } catch (Throwable $t) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return View::create(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\Get(path="/books/{id}/comments")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getCommentAction(
        CommentsBookRepository $commentsBookRepository
    ) {
        return $commentsBookRepository->findAll();
    }


    /**
     * @Rest\Post(path="/books/{id}/comments")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postCommentAction(
        string $id,
        Request               $request,
        CommentBookFormProcessor $commentBookFormProcessor,
        Security $security
    )
    {
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $security->getUser();
        }
        [$category, $error] = ($commentBookFormProcessor)($request, $id, $user->getId());
        $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $category ?? $error;
        return View::create($data, $statusCode);
    }
}