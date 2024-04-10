<?php

namespace App\Shared\Dto;

use App\Shared\Interfaces\DtoInterface;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     type="object",
 *     schema="generate_token",
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="token_name",
 *         type="string"
 *     ),
 * )
 */
class GenerateTokenDto implements DtoInterface
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $tokenName
    ) {}

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'tokenName' => $this->tokenName,
        ];
    }

    public static function fromRequest(Request $request): self
    {
        $data = $request->only(['email', 'password', 'token_name']);

        return new self(...array_values($data));
    }
}
