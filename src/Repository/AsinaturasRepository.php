<?php

namespace App\Repository;

use App\Entity\Asinaturas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Asinaturas>
 */
class AsinaturasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asinaturas::class);
    }




    public function add(Asinaturas $asi):void
    {
        $this->getEntityManager()->persist($asi);
        $this->getEntityManager()->flush();
    }

    public function remove(Asinaturas $asi):void
    {
        $this->getEntityManager()->remove($asi);
        $this->getEntityManager()->flush();
    }


    //    /**
    //     * @return Asinaturas[] Returns an array of Asinaturas objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Asinaturas
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
