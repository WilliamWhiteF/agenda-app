<?php

namespace Tests\Unit;

use App\Domains\Appointments\Dto\UpdateAppointmentDto;
use App\Domains\Appointments\Exceptions\InvalidDateException;
use App\Domains\Appointments\Exceptions\NotOwnerException;
use App\Domains\Appointments\Models\Appointment;
use App\Domains\Appointments\Repository\UserRepository;
use App\Domains\Appointments\Services\CheckDateService;
use App\Domains\Appointments\Services\UpdateAppointmentService;
use App\Shared\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateAppointmentServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserRepository $repository;
    private UpdateAppointmentDto $dto;
    private const QTDE_APPOINTMENTS = 10;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->has(Appointment::factory()->count(self::QTDE_APPOINTMENTS))
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $this->repository = new UserRepository($this->user);

        $startDate = Carbon::parse('2024-04-13 08:00:00');
        $endDate = (clone $startDate)->addHour();
        $this->dto = new UpdateAppointmentDto(
            $startDate,
            $endDate,
            $endDate,
        );
    }

    public function test_execute_throws_invalid_date()
    {
        $this->expectException(InvalidDateException::class);
        $checkDateService = new CheckDateService($this->repository);
        $service = new UpdateAppointmentService($this->repository, $checkDateService);

        $appointment = $this->user->appointments()->first();
        $service->execute($appointment, $this->dto);
    }

    public function test_execute_throws_not_owner_exception()
    {
        $this->expectException(NotOwnerException::class);
        $checkDateService = new CheckDateService($this->repository);
        $service = new UpdateAppointmentService($this->repository, $checkDateService);

        $falseUser = User::factory()
            ->has(Appointment::factory()->count(1))
            ->create([
                'name' => 'Test User',
                'email' => 'test@tesupdate.com',
            ]);

        $appointment = $falseUser->appointments()->first();
        $service->execute($appointment, $this->dto);
    }
}
