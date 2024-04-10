<?php

namespace App\Http\Controllers;

use App\Domains\Appointments\Dto\AppointmentDto;
use App\Domains\Appointments\Dto\AppointmentsDto;
use App\Domains\Appointments\Dto\ListAppointmentsDto;
use App\Domains\Appointments\Dto\UpdateAppointmentDto;
use App\Domains\Appointments\Exceptions\InvalidDateException;
use App\Domains\Appointments\Exceptions\NotOwnerException;
use App\Domains\Appointments\Models\Appointment;
use App\Domains\Appointments\Services\ListAppointmentsService;
use App\Domains\Appointments\Services\StoreAppointmentService;
use App\Domains\Appointments\Services\UpdateAppointmentService;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * @OA\Get(
     *      tags={"appointment"},
     *      summary="Retorna uma lista de agendamentos",
     *      description="Retorna uma lista de agendamentos que podem ser filtrados por data",
     *      path="/api/appointment",
     *      security={{ "bearerAuth": {} }},
     * @OA\Parameter(
     *          name="start_date",
     *          in="query",
     *          description="data de inicio",
     *          required=false
     *      ),
     * @OA\Parameter(
     *          name="end_date",
     *          in="query",
     *          description="data final",
     *          required=false
     *      ),
     * @OA\Response(
     *          response="200",
     *          description="Uma lista de agendamentos seguindo o formato",
     * @OA\JsonContent(ref="#/components/schemas/appointment")
     *      )
     * ),
     */
    public function index(Request $request, ListAppointmentsService $service)
    {
        $request->validate(
            [
            'start_date' => 'date_format:Y-m-d H:i:s',
            'end_date' => 'date_format:Y-m-d H:i:s',
            ]
        );

        $dto = ListAppointmentsDto::fromRequest($request);
        return $service->execute($dto);
    }

    /**
     * @OA\Post(
     *      tags={"appointment"},
     *      summary="Cria um agendamento",
     *      security={{ "bearerAuth": {} }},
     *      description="Cria um agendamento, caso não tenha um marcado na data e não seja no fim de semana",
     *      path="/api/appointment",
     * @OA\RequestBody(
     *          required=true,
     * @OA\JsonContent(ref="#/components/schemas/appointment")
     *      ),
     * @OA\Response(response="400",                            description="Erro de fim semana ou conflito de agenda"),
     * @OA\Response(response="201",                            description="Criação de agendamento")
     * ),
     */
    public function store(StoreAppointmentRequest $request, StoreAppointmentService $service)
    {
        try {
            $dto = AppointmentDto::fromRequest($request);
            $appointment = $service->execute($dto);

            return response(['appointment' => $appointment->id], 201);
        } catch (InvalidDateException $e) {
            Log::error("[AGENDA-ERROR] {$e->getFile()}:{$e->getLine()} - {$e->getMessage()}");
            return response($e->getMessage(), 400);
        } catch (\Exception $e) {
            Log::error("[AGENDA-ERROR] {$e->getFile()}:{$e->getLine()} - {$e->getMessage()}");
            return response("Erro inesperado", 500);
        }
    }

    /**
     * @OA\Get(
     *      tags={"appointment"},
     *      summary="Retorna um agendamento",
     *      description="Retorna um apontamento",
     *      path="/api/appointment/{appointmentId}",
     *      security={{ "bearerAuth": {} }},
     * @OA\Parameter(
     *          name="appointmentId",
     *          in="path",
     *          description="ID do agendamento a ser atualizado",
     *          required=true
     *      ),
     * @OA\Response(
     *          response="404",
     *          description="Agendamento não encontrado"
     *      ),
     * @OA\Response(
     *          response="200",
     *          description="Agendamento",
     * @OA\JsonContent(ref="#/components/schemas/appointment"),
     *      ),
     * ),
     */
    public function show(Appointment $appointment)
    {
        return $appointment;
    }

    /**
     * @OA\Put(
     *      tags={"appointment"},
     *      summary="Atualiza um agendamento",
     *      security={{ "bearerAuth": {} }},
     *      description="Atualiza um agendamento, caso não tenha um marcado na data e não seja no fim de semana",
     *      path="/api/appointment/{appointmentId}",
     * @OA\Parameter(
     *          name="appointmentId",
     *          in="path",
     *          description="ID do agendamento a ser atualizado",
     *          required=true
     *      ),
     * @OA\RequestBody(
     *          required=true,
     * @OA\JsonContent(ref="#/components/schemas/appointment")
     *      ),
     * @OA\Response(
     *          response="404",
     *          description="Agendamento não encontrado"
     *      ),
     * @OA\Response(response="400",                            description="Erro de fim semana ou conflito de agenda"),
     * @OA\Response(response="401",                            description="Usuário não é dono do agendamento"),
     * @OA\Response(response="204",                            description="Agendamento atualizado")
     * ),
     */
    public function update(
        UpdateAppointmentRequest $request,
        Appointment $appointment,
        UpdateAppointmentService $service
    ) {
        try {
            $dto = UpdateAppointmentDto::fromRequest($request);
            $service->execute($appointment, $dto);

            return response('', 204);
        } catch (InvalidDateException $e) {
            Log::error("[AGENDA-ERROR] {$e->getFile()}:{$e->getLine()} - {$e->getMessage()}");
            return response($e->getMessage(), 400);
        } catch (NotOwnerException $e) {
            Log::error("[AGENDA-ERROR] {$e->getFile()}:{$e->getLine()} - {$e->getMessage()}");
            return response($e->getMessage(), 401);
        } catch (\Exception $e) {
            Log::error("[AGENDA-ERROR] {$e->getFile()}:{$e->getLine()} - {$e->getMessage()}");
            return response("Erro inesperado", 500);
        }
    }

    /**
     * @OA\Delete(
     *      tags={"appointment"},
     *      summary="Exclui um agendamento",
     *      description="Exclui um apontamento",
     *      path="/api/appointment/{appointmentId}",
     *      security={{ "bearerAuth": {} }},
     * @OA\Parameter(
     *          name="appointmentId",
     *          in="path",
     *          description="ID do agendamento a ser excluido",
     *          required=true
     *      ),
     * @OA\Response(
     *          response="404",
     *          description="Agendamento não encontrado"
     *      ),
     * @OA\Response(response="204", description="Agendamento excluido")
     * ),
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response('', 204);
    }
}
