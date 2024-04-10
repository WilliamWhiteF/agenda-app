<?php

namespace App\Domains\Appointments\Services;

use App\Domains\Appointments\Dto\AppointmentDto;
use App\Domains\Appointments\Dto\UpdateAppointmentDto;
use App\Domains\Appointments\Exceptions\InvalidDateException;
use App\Domains\Appointments\Repository\UserRepository;
use App\Shared\Interfaces\ServicesInterface;

/**
 * Valida se a data está disponivel
 */
class CheckDateService implements ServicesInterface
{
    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public function execute(AppointmentDto|UpdateAppointmentDto $dto)
    {
        $hasWeekend = collect([$dto->startDate, $dto->endDate, $dto->deadlineDate])
            ->map(fn ($date) => $date->isWeekend())
            ->filter();

        if (!$hasWeekend->isEmpty()) {
            throw new InvalidDateException('Fins de semana não são permitidos');
        }

        if ($this->repository->hasConflictingAgenda($dto->startDate, $dto->endDate)) {
            throw new InvalidDateException('Outro compromisso ja marcado');
        }
    }
}
