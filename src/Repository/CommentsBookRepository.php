<?php

namespace App\Repository;

use App\Entity\CommentsBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentsBook>
 *
 * @method CommentsBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentsBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentsBook[]    findAll()
 * @method CommentsBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentsBook::class);
    }

    public function save(CommentsBook $commentsBook): CommentsBook
    {
        $this->getEntityManager()->persist($commentsBook);
        $this->getEntityManager()->flush();
        return $commentsBook;
    }

    public function reload(CommentsBook $commentsBook): CommentsBook
    {
        $this->getEntityManager()->refresh($commentsBook);
        return $commentsBook;
    }

    public function delete(CommentsBook $commentsBook): void
    {
        $this->getEntityManager()->remove($commentsBook);
        $this->getEntityManager()->flush();
    }
}
