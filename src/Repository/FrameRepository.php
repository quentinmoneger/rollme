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
    
    public function findByNumberAndScenarioId($number, $scenarioId)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.number = :number')
            ->andWhere('f.scenario_id = :scenario_id')
            ->setParameter('number', $number)
            ->setParameter('scenario_id', $scenarioId)
            ->orderBy('f.number', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByScenarioId($scenarioId)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.scenario_id = :scenario_id')
            ->setParameter('scenario_id', $scenarioId)
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
