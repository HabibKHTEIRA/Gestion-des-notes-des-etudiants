<?php

namespace App\Repository;

use App\Entity\FormationInt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormationInt>
 *
 * @method FormationInt|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormationInt|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormationInt[]    findAll()
 * @method FormationInt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationIntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormationInt::class);
    }

    //    /**
    //     * @return FormationInt[] Returns an array of FormationInt objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FormationInt
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
