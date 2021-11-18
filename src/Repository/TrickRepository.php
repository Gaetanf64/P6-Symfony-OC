<?php

namespace App\Repository;

use App\Entity\Trick;
use App\Entity\Media;
use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    // /**
    //  * @return Trick[] Returns Trick objects
    //  */

    public function findAllTrick()
    {
        $query = $this->createQueryBuilder('t')
            ->addSelect('t') // to make Doctrine actually use the join
            ->leftJoin('t.media', 'm')
            // ->leftJoin('t.groupe', 'g')
            ->addSelect('m')
            // ->addSelect('g')
            ->where('t.id = m.trick')
            // ->andWhere('t.id = g.tricks')
            ->orderBy('t.dateUpdate', 'DESC')
            // ->setParameter('parameter', $parameter)
            ->getQuery();

        return $query->getResult();
        // return $this->createQueryBuilder('t')
        //     ->andWhere('t.trick = :val')
        //     ->setParameter('val', $value)
        //     ->orderBy('t.id', 'DESC')
        //     ->setMaxResults(10)
        //     ->getQuery()
        //     ->getResult();
    }


    // /**
    //  * @return Trick[] Returns an array of Trick objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trick
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
