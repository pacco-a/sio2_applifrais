<?php

namespace App\Repository;

use App\Entity\EntreeFraisForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EntreeFraisForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntreeFraisForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntreeFraisForfait[]    findAll()
 * @method EntreeFraisForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntreeFraisForfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntreeFraisForfait::class);
    }

    // /**
    //  * @return EntreeFraisForfait[] Returns an array of EntreeFraisForfait objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EntreeFraisForfait
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
