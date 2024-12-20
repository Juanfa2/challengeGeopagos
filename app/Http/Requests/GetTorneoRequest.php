<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetTorneoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha' => 'nullable|date_format:d/m/Y',
            'tipo' => 'nullable|string|max:255',
            'nombreGanador' => 'nullable|string|max:255',
        ];
    }
}
