<?php

namespace App\Repository;

use App\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    // /**
    //  * @return Messages[] Returns an array of Messages objects
    //  */
  
    // On prepare une requete pour récuperer tous les anciens messages supérieur au dernier Id affiché
    public function findByIdSupAndGame($lastid, $idGame)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.id > :id')
            ->setParameter('id' , $lastid)
            ->andWhere('m.game = :game')
            ->setParameter('game', $idGame)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

   
        
}
