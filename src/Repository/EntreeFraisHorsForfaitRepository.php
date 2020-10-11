<?php

namespace App\Repository;

use App\Entity\EntreeFraisHorsForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EntreeFraisHorsForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntreeFraisHorsForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntreeFraisHorsForfait[]    findAll()
 * @method EntreeFraisHorsForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntreeFraisHorsForfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntreeFraisHorsForfait::class);
    }

    // /**
    //  * @return EntreeFraisHorsForfait[] Returns an array of EntreeFraisHorsForfait objects
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
    public function findOneBySomeField($value): ?EntreeFraisHorsForfait
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
