<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
            'deadline_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
            'status' => 'boolean',
            'title' => 'required|string',
            'type' => 'required|string',
            'description' => 'required|string',
        ];
    }
}
