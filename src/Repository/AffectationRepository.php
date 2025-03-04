<?php
namespace App\Repository;

use App\Entity\Affectation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AffectationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectation::class);
    }

    public function isEmployeeAssigned(int $employeeId, \DateTime $startDate, \DateTime $endDate): bool
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.employee = :employeeId')
            ->andWhere('a.startDate <= :endDate')
            ->andWhere('a.endDate >= :startDate')
            ->setParameter('employeeId', $employeeId)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        return $qb->getQuery()->getOneOrNullResult() !== null;
    }
}