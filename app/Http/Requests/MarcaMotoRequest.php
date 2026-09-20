<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MarcaMotoRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_marca' => 'required|string|max:100|unique:marca_moto,nombre_marca',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_marca.required' => 'El nombre de la marca es obligatorio.',
            'nombre_marca.max' => 'El nombre de la marca no puede superar los 100 caracteres.',
            'nombre_marca.unique' => 'Esta marca ya existe.',
        ];
    }
}
