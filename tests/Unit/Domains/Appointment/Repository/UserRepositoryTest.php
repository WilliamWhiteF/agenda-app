<?php

namespace Tests\Unit;

use App\Domains\Appointments\Models\Appointment;
use App\Domains\Appointments\Repository\UserRepository;
use App\Shared\Dto\GenerateTokenDto;
use App\Shared\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testando caso de exceção apenas devido ao caso de sucesso ser inteiramente do framework
 */
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private GenerateTokenDto $dto;
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
    }

    /**
     * Teste basico
     */
    public function test_list_appointments_paginate(): void
    {
        $repository = new UserRepository($this->user);
        $paginate = $repository->paginateAppointmentsByDate(null, null);

        $this->assertEquals($paginate->count(), self::QTDE_APPOINTMENTS, "Ambos devem ser iguais");
    }

    /**
     * Teste basico
     */
    public function test_list_appointments_paginate_filtered(): void
    {
        $repository = new UserRepository($this->user);
        $paginate = $repository->paginateAppointmentsByDate(Carbon::now(), Carbon::now()->addHour());

        $this->assertNotEquals($paginate->count(), self::QTDE_APPOINTMENTS, "Ambos não devem ser iguais");
    }
}
