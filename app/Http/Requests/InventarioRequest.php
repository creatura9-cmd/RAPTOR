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

            'estado_inventario' => [
                'required',
                'in:pendiente,en_proceso,finalizado',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_moto.required' => 'La moto es obligatoria.',
            'id_moto.exists' => 'La moto seleccionada no existe.',

            'descripcion.string' => 'La descripción debe ser un texto.',

            'fecha_registro.required' => 'La fecha de registro es obligatoria.',
            'fecha_registro.date' => 'La fecha de registro no es válida.',

            'condicion_general.required' => 'La condición general es obligatoria.',
            'condicion_general.in' => 'La condición general seleccionada no es válida.',

            'estado_inventario.required' => 'El estado del inventario es obligatorio.',
            'estado_inventario.in' => 'El estado del inventario seleccionado no es válido.',
        ];
    }
}
