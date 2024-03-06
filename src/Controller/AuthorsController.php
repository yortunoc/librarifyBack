<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthorsController extends AbstractController
{

    /**
     * @Route("/authors")
     */
    public function showauthors(AuthorRepository $authorRepository)
    {
        $authors= $authorRepository->findAll();
        return $this->render('authors.html.twig', ['authors' => $authors]);
    }
}