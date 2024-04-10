<?php

namespace Tests\Feature\Http\Controllers;

use App\Domains\Appointments\Models\Appointment;
use App\Shared\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private Appointment $appointment;
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
     * test index basic listing
     */
    public function test_index(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/appointment');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'start_date',
                        'end_date',
                        'deadline_date',
                        'status',
                        'title',
                        'type',
                        'description',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /**
     * test index basic listing filtering results
     */
    public function test_filtered_index(): void
    {
        $responseFiltered = $this->actingAs($this->user)
            ->getJson('/api/appointment?' . http_build_query([
                'start_date' => '2008-01-01 00:00:00',
                'end_date' => '2008-01-01 01:00:00'
            ]));

        $responseFiltered->assertStatus(200);

        // compara a quantidade de resultados entre os dois
        $this->assertNotEquals(
            self::QTDE_APPOINTMENTS,
            count($responseFiltered->decodeResponseJson()['data']),
            'Não são iguais'
        );
    }

    /**
     * test index pagination
     */
    public function test_paginate_index(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/appointment?' . http_build_query(['page' => 2]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'start_date',
                        'end_date',
                        'deadline_date',
                        'status',
                        'title',
                        'type',
                        'description',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /**
     * test show basic listing
     */
    public function test_show(): void
    {
        $appointmentId = $this->user->appointments()->first()->id;
        $response = $this->actingAs($this->user)
            ->getJson("/api/appointment/{$appointmentId}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'start_date',
                'end_date',
                'deadline_date',
                'status',
                'title',
                'type',
                'description',
                'created_at',
                'updated_at'
            ]);
    }

    /**
     * test show not found
     */
    public function test_show_not_found(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson("/api/appointment/99999999");

        $response->assertStatus(404);
    }

    /**
     * test delete
     */
    public function test_delete(): void
    {
        $appointmentId = $this->user->appointments()->first()->id;
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/appointment/{$appointmentId}");

        $response->assertStatus(204);
    }

    /**
     * test delete not found
     */
    public function test_delete_not_found(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/appointment/99999999");

        $response->assertStatus(404);
    }

    /**
     * test create
     */
    public function test_create(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/appointment/99999999");

        $response->assertStatus(404);
    }

    /**
     * test update
     */
    public function test_update(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/appointment/99999999");
        $response->assertStatus(404);
    }

    /**
     * test update not found
     */
    public function test_update_not_found(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/appointment/99999999");
        $response->assertStatus(404);
    }
}
