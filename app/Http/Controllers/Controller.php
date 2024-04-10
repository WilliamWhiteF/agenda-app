<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(title="Agenda", version="0.1"),
 * @OA\SecurityScheme(
 *      type="http",
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      scheme="bearer",
 * ),
 * @OA\Tag(
 *      name="auth",
 *      description="endpoints para autenticação",
 * ),
 * @OA\Tag(
 *      name="appointment",
 *      description="endpoint para agendamentos",
 * ),
 */

abstract class Controller
{
    //
}
