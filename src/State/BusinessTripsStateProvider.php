<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ReportByEmployee;
use App\Repository\BusinessTripRepository;

class BusinessTripsStateProvider implements ProviderInterface
{
    public function __construct(
        private BusinessTripRepository $businessTripRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $collection = [];
        $result = $this->businessTripRepository->getBusinessTripsByEmployeeId($uriVariables['id']);
        foreach ($result as $trip) {
            $tmp = new ReportByEmployee(
                $trip['id'],
                $trip['amountDue'],
                $trip['country'],
                $trip['startDate']->format('Y-m-d H:i:s'),
                $trip['endDate']->format('Y-m-d H:i:s'),
            );
            $collection[] = $tmp;
        }

        return $collection;
    }
}
