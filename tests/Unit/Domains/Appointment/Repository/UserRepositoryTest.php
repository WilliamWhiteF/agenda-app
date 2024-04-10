<?php

namespace Tests\Unit;

use App\Domains\Appointments\Models\Appointment;
use App\Domains\Appointments\Repository\UserRepository;
use App\Shared\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserRepository $repository;
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
    }

    /**
     * Teste paginação sem filtro
     */
    public function test_list_appointments_paginate(): void
    {
        $paginate = $this->repository->paginateAppointmentsByDate(null, null);

        $this->assertEquals($paginate->count(), self::QTDE_APPOINTMENTS, "Ambos devem ser iguais");
    }

    /**
     * Teste paginando com filtro
     */
    public function test_list_appointments_paginate_filtered(): void
    {
        $paginate = $this->repository->paginateAppointmentsByDate(Carbon::now(), Carbon::now()->addHour());

        $this->assertNotEquals($paginate->count(), self::QTDE_APPOINTMENTS, "Ambos não devem ser iguais");
    }

    /**
     * Testa o metodo de agenda conflitante
     */
    public function test_conflicting_agenda()
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addHour();

        // sem evento, deve retornar sem conflito
        $this->assertFalse($this->repository->hasConflictingAgenda($startDate, $endDate));

        Appointment::factory([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ])
            ->count(1)
            ->for($this->user)
            ->create();

        // deve retornar o conflito
        $this->assertTrue($this->repository->hasConflictingAgenda($startDate, $endDate));
        $this->assertTrue(
            $this->repository->hasConflictingAgenda(
                $startDate->subHour(),
                $endDate->addHour()
            )
        );
    }

    /**
     * Testa o metodo de validação de dono
     */
    public function test_isowner()
    {
        $appointment = $this->user->appointments()->first();

        $this->assertTrue($this->repository->isOwner($appointment));
    }
}
