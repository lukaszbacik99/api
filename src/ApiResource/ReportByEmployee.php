<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\BusinessTripsStateProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ApiResource(
    uriTemplate: '/reports/employee/{id}',
    shortName: 'Report by employee',
    operations: [
        new Get(),
    ],
    normalizationContext: [
        'groups' => ['report:read'],
    ],
    provider: BusinessTripsStateProvider::class,
)]
class ReportByEmployee
{
    private int $id;

    #[Groups('report:read')]
    #[SerializedName('amount_due')]
    private int $amountDue;

    #[Groups('report:read')]
    private string $currency = 'PLN';

    #[Groups('report:read')]
    private string $country;

    #[Groups('report:read')]
    private string $start;
    #[Groups('report:read')]
    private string $end;

    public function __construct(
        int $id,
        int $amountDue,
        string $country,
        string $start,
        string $end,
    ){
        $this->id = $id;
        $this->amountDue = $amountDue;
        $this->country = $country;
        $this->start = $start;
        $this->end = $end;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getAmountDue(): int
    {
        return $this->amountDue;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getStart(): string
    {
        return $this->start;
    }

    public function getEnd(): string
    {
        return $this->end;
    }
}
