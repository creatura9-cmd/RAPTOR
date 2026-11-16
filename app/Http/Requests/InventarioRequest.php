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

            'estado_inventario' => [
                'required',
                'in:pendiente,en_proceso,finalizado',
            ],

            'condicion_general' => [
                'exclude_unless:estado_inventario,finalizado',
                'required_if:estado_inventario,finalizado',
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

            'condicion_general.required_if' => 'La condición general es obligatoria cuando el inventario está finalizado.',
            'condicion_general.in' => 'La condición general seleccionada no es válida.',

            'estado_inventario.required' => 'El estado del inventario es obligatorio.',
            'estado_inventario.in' => 'El estado del inventario seleccionado no es válido.',
        ];
    }
}
