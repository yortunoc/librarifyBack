<?php

namespace App\Service\CommentBook;

use App\Entity\CommentsBook;
use App\Model\Exception\Category\CategoryNotFound;
use App\Repository\CommentsBookRepository;
use Ramsey\Uuid\Uuid;

class GetCommentBook
{

    private CommentsBookRepository $commentsBookRepository;

    public function __construct(CommentsBookRepository $commentsBookRepository)
    {
        $this->commentsBookRepository = $commentsBookRepository;
    }

    public function __invoke(string $id): ?CommentsBook
    {
        $commentsBook = $this->commentsBookRepository->find(Uuid::fromString($id));
        if (!$commentsBook) {
            CategoryNotFound::throwException();
        }
        return $commentsBook;
    }
}