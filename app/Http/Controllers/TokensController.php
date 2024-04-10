<?php

namespace App\Http\Controllers;

use App\Shared\Dto\GenerateTokenDto;
use App\Shared\Exceptions\InvalidCredentialsException;
use App\Shared\Services\GenerateTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokensController extends Controller
{
    /**
     * @OA\Post(
     *      tags={"auth"},
     *      summary="Gera um Bearer Token",
     *      description="Retorna um bearer token valido para acesso",
     *      path="/api/tokens/create",
     *      @OA\RequestBody(
     *          required=true,
     *          description="informação de autenticação",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/generate_token"
     *          ),
     *      ),
     *      @OA\Response(response="401", description="Credenciais invalidas"),
     *      @OA\Response(response="500", description="Erro desconhecido"),
     *      @OA\Response(
     *          response="200",
     *          description="Criação de token",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="token",type="string")
     *          )
     *      )
     * )
     */
    public function __invoke(Request $request, GenerateTokenService $generateToken)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'token_name' => 'required',
        ]);

        try {
            $dto = GenerateTokenDto::fromRequest($request);
            $token = $generateToken->execute($dto);

            return response(['token' => $token->plainTextToken], 200);
        } catch (InvalidCredentialsException $e) {
            Log::error("[LOGIN-ERROR] {$e->getFile()}:{$e->getLine()} - {$e->getMessage()}");
            return response($e->getMessage(), 401);
        } catch (\Exception $e) {
            Log::error("[LOGIN-ERROR] {$e->getFile()}:{$e->getLine()} - {$e->getMessage()}");
            return response($e->getMessage(), 500);
        }
    }
}
