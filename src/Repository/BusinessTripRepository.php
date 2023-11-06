<?php

namespace App\Repository;

use App\Entity\BusinessTrip;
use App\Entity\Employee;
use App\TravelAllowance\BusinessTrip\Values;
use App\TravelAllowance\BusinessTripRepositoryInterface;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BusinessTrip>
 *
 * @method BusinessTrip|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessTrip|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessTrip[]    findAll()
 * @method BusinessTrip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessTripRepository extends ServiceEntityRepository implements BusinessTripRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BusinessTrip::class);
    }

    public function add(Values $values): void
    {
        $businessTrip = new BusinessTrip();
        $businessTrip->setEmployee(
            $this->getEntityManager()->getReference(Employee::class, $values->employeeId)
        );
        $businessTrip->setCountry($values->country->code);
        $businessTrip->setStartDate($values->startDate);
        $businessTrip->setEndDate($values->endDate);
        $businessTrip->setAmountDue($values->travelAllowance);

        $this->getEntityManager()->persist($businessTrip);
        $this->getEntityManager()->flush();
    }

    public function getBusinessTripCount(
        int $employeeId,
        DateTimeImmutable $endDateIsGreaterOrEqual,
        DateTimeImmutable $startDateIsLowerOrEqual
    ): int {
        $result = $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.employee = :employeeId')
            ->andWhere('b.endDate >= :endDateIsGreaterOrEqual')
            ->andWhere('b.startDate <= :startDateIsLowerOrEqual')
            ->setParameter('employeeId', $employeeId)
            ->setParameter('endDateIsGreaterOrEqual', $endDateIsGreaterOrEqual)
            ->setParameter('startDateIsLowerOrEqual', $startDateIsLowerOrEqual)
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }
}
