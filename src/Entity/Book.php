<?php

namespace App\Entity;

use App\Entity\Book\Score;
use App\Event\Book\BookCreatedEvent;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

class Book
{
    private UuidInterface $id;

    private string $title;

    private ?string $image;

    /** @var Collection|Category[] */
    private Collection $categories;

    /** @var Collection|Author[] */
    private Collection $authors;

    /** @var Collection|CommentsBook[] */
    private Collection $comments;

    private Score $score;

    private ?string $description;

    private ?DateTimeInterface $createdAt;

    private ?DateTimeInterface $readAt;

    private array $domainEvents;

    /**
     * @param UuidInterface $uuid
     * @param string $title
     * @param string|null $image
     * @param string|null $description
     * @param Score|null $score
     * @param DateTimeInterface|null $readAt
     * @param Collection|Author[]|null $authors
     * @param Collection|Category[] |null $categories
     * @param Collection|CommentsBook[] |null $comments
     */
    public function __construct(
        UuidInterface $uuid,
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        ?Collection $authors,
        ?Collection $categories,
        ?Collection $comments
    ) {
        $this->id = $uuid;
        $this->title = $title;
        $this->image = $image;
        $this->description = $description ?? $description;;
        $this->score = $score ?? Score::create();
        $this->readAt = $readAt;
        $this->categories = $categories ?? new ArrayCollection();
        $this->authors = $authors ?? new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->comments = $comments ?? new ArrayCollection();
    }

    /**
     * @param string $title
     * @param string|null $image
     * @param string|null $description
     * @param Score|null $score
     * @param DateTimeInterface|null $readAt
     * @param array|Author[] $authors
     * @param array|Category[] $categories
     * @return self
     */
    public static function create(
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        array $authors,
        array $categories,
        array $comments
    ): self {
        $book = new self(
            Uuid::uuid4(),
            $title,
            $image,
            $description,
            $score,
            $readAt,
            new ArrayCollection($authors),
            new ArrayCollection($categories),
            new ArrayCollection($comments)
        );
        $book->addDomainEvent(new BookCreatedEvent($book->getId()));
        return $book;
    }

    public function addDomainEvent(Event $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function pullDomainEvents(): array
    {
        return $this->domainEvents;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function updateCategories(Category ...$categories)
    {
        /** @var Category[]|ArrayCollection */
        $originalCategories = new ArrayCollection();
        foreach ($this->categories as $category) {
            $originalCategories->add($category);
        }

        // Remove categories
        foreach ($originalCategories as $originalCategory) {
            if (!\in_array($originalCategory, $categories)) {
                $this->removeCategory($originalCategory);
            }
        }

        // Add categories
        foreach ($categories as $newCategory) {
            if (!$originalCategories->contains($newCategory)) {
                $this->addCategory($newCategory);
            }
        }
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

        return $this;
    }

    public function updateAuthors(Author ...$authors)
    {
        /** @var Author[]|ArrayCollection */
        $originalAuthors = new ArrayCollection();
        foreach ($this->authors as $author) {
            $originalAuthors->add($author);
        }

        // Remove authors
        foreach ($originalAuthors as $originalAuthor) {
            if (!\in_array($originalAuthor, $authors)) {
                $this->removeAuthor($originalAuthor);
            }
        }

        // Add authors
        foreach ($authors as $newAuthor) {
            if (!$originalAuthors->contains($newAuthor)) {
                $this->addAuthor($newAuthor);
            }
        }
    }

    public function addComment(CommentsBook $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }

        return $this;
    }

    public function removeComment(CommentsBook $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
        }

        return $this;
    }

    public function updateComments(CommentsBook ...$comments)
    {
        /** @var CommentsBook[]|ArrayCollection */
        $originalComments = new ArrayCollection();
        foreach ($this->comments as $comment) {
            $originalComments->add($comment);
        }

        // Remove comments
        foreach ($originalComments as $originalComment) {
            if (!\in_array($originalComment, $comments)) {
                $this->removeComment($originalComment);
            }
        }

        // Add authors
        foreach ($comments as $newComment) {
            if (!$originalComments->contains($newComment)) {
                $this->addComment($newComment);
            }
        }
    }

    /**
     * @param string $title
     * @param string|null $image
     * @param string|null $description
     * @param Score|null $score
     * @param DateTimeInterface $readAt
     * @param array|Author[] $authors
     * @param array|Category[] $categories
     * @param array|CommentsBook[] $comments
     * @return void
     */
    public function update(
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        array $authors,
        array $categories,
        array $comments
    ) {
        $this->title = $title;
        if ($image !== null) {
            $this->image = $image;
        }
        $this->description = $description;
        $this->score = $score;
        $this->readAt = $readAt;
        $this->updateCategories(...$categories);
        $this->updateAuthors(...$authors);
        $this->updateComments(...$comments);
    }

    public function patch(array $data): self
    {
        if (array_key_exists('score', $data)) {
            $this->score = Score::create($data['score']);
        }
        if (array_key_exists('title', $data)) {
            $title = $data['title'];
            if ($title === null) {
                throw new DomainException('Title cannot be null');
            }
            $this->title = $title;
        }
        return $this;
    }

    public function setScore(Score $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getScore(): Score
    {
        return $this->score;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isRead(): ?bool
    {
        return $this->readAt === null ? false : true;
    }

    public function markAsRead(DateTimeInterface $readAt): self
    {
        $this->readAt = $readAt;
        return $this;
    }

    public function getReadAt(): ?DateTimeInterface
    {
        return $this->readAt;
    }

    public function __toString()
    {
        return $this->title ?? 'Libro';
    }
}