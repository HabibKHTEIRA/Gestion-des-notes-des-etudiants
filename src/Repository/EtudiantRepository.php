<?php

namespace App\Repository;

use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;


/**
 * @extends ServiceEntityRepository<Etudiant>
 *
 * @method Etudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiant[]    findAll()
 * @method Etudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function save(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function rechercherParNomPrenom($nomPrenom)
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.nom LIKE :nom')
            ->orWhere('e.prenom LIKE :prenom')
            ->setParameter('nom', '%' . $nomPrenom . '%')
            ->setParameter('prenom', '%' . $nomPrenom . '%')
            ->setMaxResults(15) 
            ->getQuery();
    
        return $qb->getResult();
    }
    public function rechercherexpression($nomPrenom)
{
    $qb = $this->createQueryBuilder('e')
        ->where("CONCAT(e.nom, ' ', e.prenom) LIKE :nomPrenom")
        ->orWhere("CONCAT(e.prenom, ' ', e.nom) LIKE :Prenomnom")
        ->setParameter('nomPrenom', '%' . $nomPrenom . '%')
        ->setParameter('Prenomnom', '%' . $nomPrenom . '%')
        ->getQuery();

    return $qb->getResult();
}
    

//    /**
//     * @return Etudiant[] Returns an array of Etudiant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Etudiant
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
