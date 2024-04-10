<?php

namespace App\Domains\Appointments\Models;

use App\Shared\Models\User;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @OA\Schema(
 *  schema="start_date",
 *  type="string",
 *  description="formato YYYY-mm-dd H:i:s",
 *  example="2024-04-09 00:00:00",
 * ),
 * @OA\Schema(
 *  schema="end_date",
 *  type="string",
 *  description="formato YYYY-mm-dd H:i:s",
 *  example="2024-04-09 00:00:00"
 * ),
 * @OA\Schema(
 *  schema="appointment",
 *  type="object",
 *  description="Um agendamento padrão",
 * @OA\Property(property="start_date",ref="#/components/schemas/start_date"),
 * @OA\Property(property="end_date",ref="#/components/schemas/end_date"),
 * @OA\Property(property="deadline_date",type="string",example="2024-04-09    00:00:00"),
 * @OA\Property(property="status",type="boolean"),
 * @OA\Property(property="title",type="string"),
 * @OA\Property(property="type",type="string"),
 * @OA\Property(property="description",type="string")
 * ),
 */
class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'deadline_date',
        'status',
        'title',
        'type',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): Factory
    {
        return AppointmentFactory::new();
    }
}
