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

    /**
     * @return Frame Returns a instance of Frame from the number of the frame and the scenarioId
     */
    public function findByNumberAndScenarioId($number, $scenario) :object
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.number = :number')
            ->andWhere('f.scenario = :scenario')
            ->setParameter('number', $number)
            ->setParameter('scenario', $scenario)
            ->orderBy('f.number', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    /**
     * @return Frame[] Returns an array of Frame 
     */
    public function findByScenarioId($scenario) :array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.scenario = :scenario')
            ->setParameter('scenario', $scenario)
            ->orderBy('f.number', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

}
