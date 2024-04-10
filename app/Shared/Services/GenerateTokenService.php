<?php

namespace App\Shared\Services;

use App\Shared\Dto\GenerateTokenDto;
use App\Shared\Exceptions\InvalidCredentialsException;
use App\Shared\Interfaces\ServicesInterface;
use App\Shared\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Classe responsavel por gerar tokens de acesso
 */
class GenerateTokenService implements ServicesInterface
{
    public function execute(GenerateTokenDto $dto)
    {
        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
            throw new InvalidCredentialsException("Credenciais Invalidas");
        }

        $user = Auth::user();
        return $user->createToken($dto->tokenName);
    }
}
