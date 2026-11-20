<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_moto' => [
                'required',
                'integer',
                'exists:moto,id',
            ],

            'id_mecanico' => [
                'required',
                'integer',
                'exists:mecanicos,id_mecanico',
            ],

            'descripcion' => [
                'nullable',
                'string',
            ],

            'fecha_registro' => [
                'required',
                'date',
            ],

            'condicion_general' => [
                'required',
                'in:bueno,regular,malo',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_moto.required' => 'La moto es obligatoria.',
            'id_moto.integer' => 'La moto seleccionada no es válida.',
            'id_moto.exists' => 'La moto seleccionada no existe.',

            'id_mecanico.required' => 'El mecánico es obligatorio.',
            'id_mecanico.integer' => 'El mecánico seleccionado no es válido.',
            'id_mecanico.exists' => 'El mecánico seleccionado no existe.',

            'descripcion.string' => 'La descripción debe ser un texto.',

            'fecha_registro.required' => 'La fecha de registro es obligatoria.',
            'fecha_registro.date' => 'La fecha de registro no es válida.',

            'condicion_general.required' => 'La condición general es obligatoria.',
            'condicion_general.in' => 'La condición general seleccionada no es válida.',
        ];
    }
}
