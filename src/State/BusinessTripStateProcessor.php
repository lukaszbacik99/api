<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\BusinessTripRepository;
use App\TravelAllowance\BusinessTrip\BusinessTripException;
use App\TravelAllowance\BusinessTrip\Country\CountryException;
use App\TravelAllowance\BusinessTrip\Factory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BusinessTripStateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly BusinessTripRepository $businessTripRepository,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        assert($data instanceof \App\Entity\BusinessTrip);

        try {
            Factory::create($this->businessTripRepository)
                ->add(
                    $data->getEmployeeId(),
                    $data->getCountry(),
                    $data->getStartDate(),
                    $data->getEndDate(),
                );
        } catch (CountryException|BusinessTripException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }
}
