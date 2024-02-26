<?php

namespace App\Form\Model;

use App\Entity\CommentsBook;
use Ramsey\Uuid\UuidInterface;

class CommentsBookDto
{
    public ?UuidInterface $id = null;
    public ?string $comment = null;
    public ?string $id_book = null;
    public ?string $id_user = null;

    public function getIdBook(): ?string
    {
        return $this->id_book;
    }

    public function getIdUser(): ?string
    {
        return $this->id_user;
    }


    public static function createFromComment(CommentsBook $commentsBook): self
    {
        $dto = new self();
        $dto->id = $commentsBook->getId();
        $dto->comment = $commentsBook->getComment();
        $dto->id_book = $commentsBook->getIdBook();
        $dto->id_user = $commentsBook->getIdUser();
        return $dto;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function __toString()
    {
        return $this->comment ?? '';
    }
}