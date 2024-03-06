<?php

namespace App\Service\CommentBook;

use App\Entity\Book;
use App\Entity\CommentsBook;
use App\Repository\CommentsBookRepository;

class CreateCommentBook
{
    private CommentsBookRepository $commentsBookRepository;

    public function __construct(CommentsBookRepository $commentsBookRepository)
    {
        $this->commentsBookRepository = $commentsBookRepository;
    }

    public function __invoke(string $comment, Book $id_book, string $id_user): CommentsBook
    {
        $category = CommentsBook::create($comment, $id_book, $id_user);
        $this->commentsBookRepository->save($category);
        return $category;
    }
}