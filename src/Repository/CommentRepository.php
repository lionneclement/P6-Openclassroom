<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findAllCommentByTricksId(int $id, bool $status): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.TricksId = :id')
            ->andWhere('p.Status = :status')
            ->setParameter('id', $id)
            ->setParameter('status', $status)
            ->orderBy('p.Date', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findAllCommentByStatus(bool $status): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.Status = :val')
            ->setParameter('val', $status)
            ->orderBy('p.Date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
