<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\BusinessTripRepository;
use App\State\BusinessTripStateProcessor;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BusinessTripRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            normalizationContext: [
                'groups' => ['trip:read'],
            ],
            denormalizationContext: [
                'groups' => ['trip:write'],
            ],
        ),
    ],
    processor: BusinessTripStateProcessor::class,
)]
class BusinessTrip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'businessTrips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    #[Groups(['trip:read', 'trip:write'])]
    private ?int $employeeId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['trip:read', 'trip:write'])]
    private DateTimeInterface $startDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['trip:read', 'trip:write'])]
    private DateTimeInterface $endDate;

    #[ORM\Column(nullable: true)]
    #[Groups('trips:read')]
    private ?int $amountDue = null;

    #[ORM\Column(length: 2)]
    #[Groups(['trip:read', 'trip:write'])]
    private ?string $country = null;

    private string $currency = 'PLN';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getEmployeeId(): ?int
    {
        return $this->employeeId;
    }

    public function setEmployeeId(?int $employeeId): static
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    public function getStartDate(): DateTimeInterface
    {
        return $this->startDate;
    }

    #[Groups('trips:read')]
    public function getStart(): string
    {
        return $this->startDate->format('Y-m-d H:i');
    }

    public function setStartDate(DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): DateTimeInterface
    {
        return $this->endDate;
    }

    #[Groups('trips:read')]
    public function getEnd(): string
    {
        return $this->endDate->format('Y-m-d H:i');
    }

    public function setEndDate(DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getAmountDue(): ?int
    {
        return $this->amountDue;
    }

    public function setAmountDue(?int $amountDue): static
    {
        $this->amountDue = $amountDue;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
