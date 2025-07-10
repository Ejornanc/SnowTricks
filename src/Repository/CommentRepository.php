<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Find comments for a specific trick with pagination
     * 
     * @param int $trickId The trick ID
     * @param int $limit Maximum number of comments to return
     * @param int $offset Offset for pagination
     * @return Comment[] Returns an array of Comment objects
     */
    public function findByTrickWithPagination(int $trickId, int $limit = 10, int $offset = 0): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.trick = :trickId')
            ->setParameter('trickId', $trickId)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Count total comments for a specific trick
     * 
     * @param int $trickId The trick ID
     * @return int Number of comments
     */
    public function countByTrick(int $trickId): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.trick = :trickId')
            ->setParameter('trickId', $trickId)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    //    /**
    //     * @return Comment[] Returns an array of Comment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Comment
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
