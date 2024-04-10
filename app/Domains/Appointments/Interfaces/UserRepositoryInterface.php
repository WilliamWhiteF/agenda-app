<?php

namespace App\Domains\Appointments\Interfaces;

use App\Domains\Appointments\Dto\AppointmentDto;
use App\Domains\Appointments\Models\Appointment;
use App\Shared\Interfaces\RepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function paginateAppointmentsByDate(?Carbon $startDate, ?Carbon $endDate): Paginator;
    public function hasConflictingAgenda(Carbon $startDate, Carbon $endDate): bool;
    public function addAppointment(AppointmentDto $dto): Appointment;
    public function isOwner(Appointment $appointment): bool;
}
