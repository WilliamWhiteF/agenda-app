<?php

namespace App\Domains\Appointments\Dto;

use App\Models\User;
use App\Shared\Interfaces\DtoInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ListAppointmentsDto implements DtoInterface
{
    public function __construct(
        public readonly ?Carbon $startDate,
        public readonly ?Carbon $endDate
    ) {}

    public function toArray(): array
    {
        return [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            empty($request->get('start_date')) ? null : Carbon::parse($request->get('start_date')),
            empty($request->get('end_date')) ? null : Carbon::parse($request->get('end_date')),
        );
    }
}
