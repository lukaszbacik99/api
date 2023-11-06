<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ApiResource(
    operations: [
        new Post(),
    ],
    normalizationContext: [
        'groups' => ['employee:read'],
    ],
    denormalizationContext: [
        'groups' => ['employee:write'],
    ],
)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('employee:read')]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: BusinessTrip::class)]
    private Collection $businessTrips;

    public function __construct()
    {
        $this->businessTrips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, BusinessTrip>
     */
    public function getBusinessTrips(): Collection
    {
        return $this->businessTrips;
    }
}
