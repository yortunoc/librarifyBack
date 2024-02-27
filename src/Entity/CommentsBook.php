<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CommentsBook
{
    private UuidInterface $id;

    private string $comment;


    private Book $id_book;

    private string $id_user;

    private ?DateTimeInterface $createdAt;

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|null $createdAt
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    public function __construct(UuidInterface $uuid, string $comment, Book $book, string $id_user)
    {
        $this->id = $uuid;
        $this->comment = $comment;
        $this->id_book = $book;
        $this->id_user = $id_user;
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(string $comment, Book $book, string $id_user)
    {
        return new self(Uuid::uuid4(), $comment, $book, $id_user);
    }

    public function update(string $comment): self {
        $this->comment = $comment;
        return $this;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getIdBook(): string
    {
        return $this->id_book;
    }

    public function setIdBook(Book $id_book): void
    {
        $this->id_book = $id_book;
    }

    public function getIdUser(): string
    {
        return $this->id_user;
    }

    public function setIdUser(string $id_user): void
    {
        $this->id_user = $id_user;
    }

    public function __toString()
    {
        return $this->comment ?? 'a';
    }
}
