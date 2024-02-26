<?php

namespace App\Service\CommentBook;

use App\Entity\CommentsBook;
use App\Form\Model\CommentsBookDto;
use App\Form\Type\CommentsBookType;
use App\Repository\CommentsBookRepository;
use App\Service\Book\GetBook;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentBookFormProcessor
{


    private GetBook $getBook;
    private GetCommentBook $getCommentBook;
    private CommentsBookRepository $commentsBookRepository;
    private FormFactoryInterface $formFactory;

    public function __construct(
        GetCommentBook         $getCommentBook,
        CommentsBookRepository $commentsBookRepository,
        FormFactoryInterface   $formFactory,
        GetBook $getBook,
    ) {
        $this->getBook = $getBook;
        $this->getCommentBook = $getCommentBook;
        $this->commentsBookRepository = $commentsBookRepository;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, string $id_book, string $id_user, ?string $commentBookId = null): array
    {
        $commentsBook = null;
        $commentBookDto = null;
        if ($commentBookId === null) {
            $commentBookDto = new CommentsBookDto();
        } else {
            $commentsBook = ($this->getCommentBook)($commentBookId);
            $commentBookDto = CommentsBookDto::createFromComment($commentsBook);
        }
        $form = $this->formFactory->create(CommentsBookType::class, $commentBookDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        $book = ($this->getBook)($id_book);

        if ($commentsBook === null) {
            $commentsBook = CommentsBook::create(
                $commentBookDto->getComment(),
                $book,
                $id_user,
            );
        } else {
            $commentsBook->update(
                $commentBookDto->getComment()
            );
        }
        $this->commentsBookRepository->save($commentsBook);
        return [$commentsBook, null];
    }
}