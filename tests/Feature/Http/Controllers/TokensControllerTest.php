<?php

namespace Tests\Feature\Http\Controllers;

use App\Shared\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TokensControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private const TOKEN_NAME = 'access_token';
    private const DEFAULT_PASSWORD = 'password';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->create([
                'password' => Hash::make(self::DEFAULT_PASSWORD),
            ]);
    }

    /**
     * Testa que pode criar um token
     */
    public function test_can_create_token(): void
    {
        $response = $this->postJson('/api/tokens/create', [
            'email' => $this->user->email,
            'password' => self::DEFAULT_PASSWORD,
            'token_name' => self::TOKEN_NAME,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /**
     * Testa validação de credencial
     */
    public function test_invalid_credentials(): void
    {
        $response = $this->postJson('/api/tokens/create', [
            'email' => $this->user->email,
            'password' => 'adasdasd',
            'token_name' => self::TOKEN_NAME,
        ]);

        $response->assertStatus(401);
    }
}
