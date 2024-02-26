<?php

namespace App\Service\CommentBook;

use App\Repository\CommentsBookRepository;

class DeleteCommentBook
{
    private GetCommentBook $getCommentBook;
    private CommentsBookRepository $commentsBookRepository;

    public function __construct(GetCommentBook $getCategory, CommentsBookRepository $categoryRepository)
    {
        $this->getCommentBook = $getCategory;
        $this->commentsBookRepository = $categoryRepository;
    }

    public function __invoke(string $id)
    {
        $category = ($this->getCommentBook)($id);
        $this->commentsBookRepository->delete($category);
    }
}