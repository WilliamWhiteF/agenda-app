<?php

namespace App\Domains\Appointments\Services;

use App\Domains\Appointments\Dto\UpdateAppointmentDto;
use App\Domains\Appointments\Exceptions\NotOwnerException;
use App\Domains\Appointments\Models\Appointment;
use App\Domains\Appointments\Repository\UserRepository;
use App\Shared\Interfaces\ServicesInterface;

/**
 * Atualiza um agendamento
 */
class UpdateAppointmentService implements ServicesInterface
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly CheckDateService $checkDateService,
    ) {}

    public function execute(Appointment $appointment, UpdateAppointmentDto $dto)
    {
        if (!$this->repository->isOwner($appointment)) {
            throw new NotOwnerException("O Agendamento não pertence a você");
        }

        $possibleDates = array_filter([$dto->startDate, $dto->endDate, $dto->deadlineDate]);
        if (!empty($possibleDates)) {
            $this->checkDateService->execute($dto);
        }

        $appointment->update(array_filter($dto->toArray()));
    }
}
