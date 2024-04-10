<?php

namespace App\Domains\Appointments\Repository;

use App\Domains\Appointments\Dto\AppointmentDto;
use App\Domains\Appointments\Interfaces\UserRepositoryInterface;
use App\Domains\Appointments\Models\Appointment;
use App\Shared\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Repositório do usuario
 */
class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly User $user
    ) {}

    /**
     * Realiza a paginação dos apontamentos do usuario
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return Paginator resultados paginados
     */
    public function paginateAppointmentsByDate(?Carbon $startDate, ?Carbon $endDate): Paginator
    {
        $appointments = $this->user->appointments();

        if (!empty($startDate)) {
            $appointments->where('start_date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $appointments->where('end_date', '<=', $endDate);
        }
        return $appointments->paginate();
    }

    /**
     * Valida os agendamentos marcados atualmente para entender se a agenda está disponivel
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return bool true se tiver conflitos
     */
    public function hasConflictingAgenda(Carbon $startDate, Carbon $endDate): bool
    {
        $startDateFormatted = $startDate->format('Y-m-d H:i:s');
        $endDateFormatted = $endDate->format('Y-m-d H:i:s');

        $hasConflictingAgenda = $this->user->appointments()
            ->whereBetween('start_date', [$startDateFormatted, $endDateFormatted])
            ->orWhereBetween('end_date', [$startDateFormatted, $endDateFormatted])
            ->exists();

        return $hasConflictingAgenda;
    }

    public function addAppointment(AppointmentDto $dto): Appointment
    {
        return $this->user->appointments()
            ->create($dto->toArray());
    }

    public function isOwner(Appointment $appointment): bool
    {
        return ($this->user->id === $appointment->user_id);
    }
}
