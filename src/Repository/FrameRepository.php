<?php

namespace App\Repository;

use App\Entity\Frame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

/**
 * @method Frame|null find($id, $lockMode = null, $lockVersion = null)
 * @method Frame|null findOneBy(array $criteria, array $orderBy = null)
 * @method Frame[]    findAll()
 * @method Frame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FrameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Frame::class);
    }

    // /**
    //  * @return Frame[] Returns an array of Frame objects
    //  */
    
    public function findByNumberAndScenarioId($number, $scenario)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.number = :number')
            ->andWhere('f.scenario = :scenario')
            ->setParameter('number', $number)
            ->setParameter('scenario', $scenario)
            ->orderBy('f.number', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByScenarioId($scenario)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.scenario = :scenario')
            ->setParameter('scenario', $scenario)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Frame
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
