<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'date_format:Y-m-d H:i:s|required_with:end_date',
            'end_date' => 'date_format:Y-m-d H:i:s|required_with:start_date|after:start_date',
            'deadline_date' => 'date_format:Y-m-d H:i:s',
            'status' => 'boolean',
            'title' => 'string',
            'type' => 'string',
            'description' => 'string',
        ];
    }
}
