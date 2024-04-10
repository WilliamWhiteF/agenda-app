<?php

namespace App\Domains\Appointments\Services;

use App\Domains\Appointments\Dto\AppointmentDto;
use App\Domains\Appointments\Repository\UserRepository;
use App\Shared\Interfaces\ServicesInterface;

/**
 * Cria uma agendamento
 */
class StoreAppointmentService implements ServicesInterface
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly CheckDateService $checkDateService,
    ) {
    }

    public function execute(AppointmentDto $dto)
    {
        $this->checkDateService->execute($dto);

        return $this->repository->addAppointment($dto);
    }
}
