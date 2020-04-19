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
    public function commentPagination(int $status, int $tricksId,int $firstComment,int $maxComment)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->andWhere('a.tricksId = :tricksId')
            ->setParameter('tricksId', $tricksId)
            ->setParameter('status', $status)
            ->orderBy('a.date', 'DESC')
            ->setFirstResult($firstComment)
            ->setMaxResults($maxComment)
            ->getQuery()
            ->getResult();
    }
    public function commentCount(int $status, int $tricksId)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->andWhere('a.tricksId = :tricksId')
            ->setParameter('tricksId', $tricksId)
            ->setParameter('status', $status)
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
