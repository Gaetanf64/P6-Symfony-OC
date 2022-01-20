<?php

namespace App\Repository;

use App\Entity\PasswordProfil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PasswordProfil|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordProfil|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordProfil[]    findAll()
 * @method PasswordProfil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordProfil::class);
    }

    // /**
    //  * @return PasswordProfil[] Returns an array of PasswordProfil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PasswordProfil
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
