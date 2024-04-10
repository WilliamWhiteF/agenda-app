<?php

namespace Tests\Unit;

use App\Domains\Appointments\Dto\UpdateAppointmentDto;
use App\Domains\Appointments\Exceptions\InvalidDateException;
use App\Domains\Appointments\Models\Appointment;
use App\Domains\Appointments\Repository\UserRepository;
use App\Domains\Appointments\Services\CheckDateService;
use App\Shared\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckDateServiceTest extends TestCase
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

    /**
     * Testando o caso de fim de semana apenas, o restante está sendo testado no repositorio
     */
    public function test_check_weekend_throws_invalidDate()
    {
        $this->expectException(InvalidDateException::class);
        $service = new CheckDateService($this->repository);
        $service->execute($this->dto);
    }
}
