<?php

namespace App\Repository;

use App\Entity\TimeSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeSlot>
 *
 * @method TimeSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeSlot[]    findAll()
 * @method TimeSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeSlot::class);
    }

    public function findAvailableForService($service): array
    {
        $duration = $service->getDuration();
        
        return $this->createQueryBuilder('t')
            ->andWhere('t.isAvailable = :available')
            ->andWhere('t.startTime > :now')
            ->setParameter('available', true)
            ->setParameter('now', new \DateTime())
            ->orderBy('t.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
