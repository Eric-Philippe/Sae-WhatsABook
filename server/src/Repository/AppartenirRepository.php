<?php

namespace App\Repository;

use App\Entity\Appartenir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appartenir>
 *
 * @method Appartenir|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appartenir|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appartenir[]    findAll()
 * @method Appartenir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppartenirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appartenir::class);
    }

//    /**
//     * @return Appartenir[] Returns an array of Appartenir objects
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

//    public function findOneBySomeField($value): ?Appartenir
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
