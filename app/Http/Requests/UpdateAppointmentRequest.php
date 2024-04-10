<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'date_format:Y-m-d H:i:s|required_with_all:end_date,deadline_date',
            'end_date' => 'date_format:Y-m-d H:i:s|required_with_all:start_date,deadline_date|after:start_date',
            'deadline_date' => 'date_format:Y-m-d H:i:s|required_with_all:end_date,start_date|after:start_date',
            'status' => 'boolean',
            'title' => 'string',
            'type' => 'string',
            'description' => 'string',
        ];
    }
}
