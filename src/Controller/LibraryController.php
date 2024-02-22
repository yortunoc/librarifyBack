<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route("/home", name="home_page")
     */
    public function home()
    {
        return $this->render('home.html.twig');
    }

//    /**
//     * @Route("/index", name="index_page")
//     */
//    public function index()
//    {
//        return $this->render('base.html.twig');
//    }

//    /**
//     * @Route("/books", name="books_get")
//     */
//    public function list(Request $request, LoggerInterface $logger, BookRepository $bookRepository)
//    {
//        $title = $request->get('title', 'alegria');
//        $logger->info('List action called');
//        $bookAsArray = [];
//        $books = $bookRepository->findAll();
//        foreach ($books as $book) {
//            $bookAsArray[] = [
//                'id' => $book->getId(),
//                'title' => $book->getTitle(),
//                'image' => $book->getImage()
//            ];
//        }
//
//
//        $response = new JsonResponse();
//        $response->setData([
//            'success' => true,
//            'data' => $bookAsArray
//        ]);
//        return $response;
//    }
//
//    /**
//     * @Route("/book/create", name="create_book")
//     */
//    public function createBook(EntityManagerInterface $em)
//    {
//        $book = new Book();
//        $book->setTitle('Havia rutas salvajes');
//        $em->persist($book);
//        $em->flush();
//    }
}