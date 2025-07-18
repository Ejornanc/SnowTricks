<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trick>
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @return Trick[] Returns an array of Trick objects
     */
    public function findAllWithMedia(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.images', 'i')
            ->leftJoin('t.videos', 'v')
            ->addSelect('i')
            ->addSelect('v')
            ->getQuery()
            ->getResult()
            ;
    }
}
