<?php

namespace App\Domains\Appointments\Dto;

use App\Shared\Interfaces\DtoInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UpdateAppointmentDto implements DtoInterface
{
    public function __construct(
        public readonly ?Carbon $startDate,
        public readonly ?Carbon $endDate,
        public readonly ?Carbon $deadlineDate,
        public readonly ?bool $status = null,
        public readonly ?string $title = null,
        public readonly ?string $type = null,
        public readonly ?string $description = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'deadline_date' => $this->deadlineDate,
            'status' => $this->status,
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
        ];
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            !empty($request->get('start_date')) ? Carbon::parse($request->get('start_date')) : null,
            !empty($request->get('end_date')) ? Carbon::parse($request->get('end_date')) : null,
            !empty($request->get('deadline_date')) ? Carbon::parse($request->get('deadline_date')) : null,
            $request->get('status'),
            $request->get('title'),
            $request->get('type'),
            $request->get('description'),
        );
    }
}
