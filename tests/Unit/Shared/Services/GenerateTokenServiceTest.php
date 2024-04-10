<?php

namespace Tests\Unit;

use App\Shared\Dto\GenerateTokenDto;
use App\Shared\Exceptions\InvalidCredentialsException;
use App\Shared\Models\User;
use App\Shared\Services\GenerateTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Testando caso de exceção apenas devido ao caso de sucesso ser inteiramente do framework
 */
class GenerateTokenServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private GenerateTokenDto $dto;
    private const DEFAULT_EMAIL = 'generateteste@generateteste.com';
    private const DEFAULT_PASSWORD = 'password';
    private const DEFAULT_TOKEN_NAME = 'access';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->create([
                'email' => self::DEFAULT_EMAIL,
                'password' => Hash::make(self::DEFAULT_PASSWORD),
            ]);

        $this->dto = new GenerateTokenDto(
            self::DEFAULT_EMAIL,
            self::DEFAULT_PASSWORD,
            self::DEFAULT_TOKEN_NAME,
        );
    }

    /**
     * Teste de credencial invalida
     */
    public function test_throws_invalid_credentials(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => self::DEFAULT_EMAIL, 'password' => self::DEFAULT_PASSWORD])
            ->andReturn(false);

        $service = new GenerateTokenService();
        $service->execute($this->dto);
    }
}
