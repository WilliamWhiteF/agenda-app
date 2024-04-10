<?php

namespace App\Domains\Appointments\Services;

use App\Domains\Appointments\Dto\ListAppointmentsDto;
use App\Domains\Appointments\Repository\UserRepository;
use App\Shared\Interfaces\ServicesInterface;

/**
 * Realiza a listagem de apontamentos
 */
class ListAppointmentsService implements ServicesInterface
{
    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public function execute(ListAppointmentsDto $dto)
    {
        return $this->repository->paginateAppointmentsByDate(...$dto->toArray());
    }
}
