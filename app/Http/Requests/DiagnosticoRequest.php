<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosticoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_inventario' => [
                'required',
                'exists:inventario,id',
            ],

            'id_mecanico' => [
                'required',
                'exists:mecanicos,id_mecanico',
            ],

            'descripcion' => [
                'required',
                'string',
            ],

            'fecha_diagnostico' => [
                'required',
                'date',
            ],

            'estado' => [
                'required',
                'in:pendiente,en_proceso,finalizado,cancelado',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_inventario.required' => 'El inventario es obligatorio.',
            'id_inventario.exists' => 'El inventario seleccionado no existe.',

            'id_mecanico.required' => 'El mecánico es obligatorio.',
            'id_mecanico.exists' => 'El mecánico seleccionado no existe.',

            'descripcion.required' => 'La descripción del diagnóstico es obligatoria.',
            'descripcion.string' => 'La descripción debe ser texto.',

            'fecha_diagnostico.required' => 'La fecha del diagnóstico es obligatoria.',
            'fecha_diagnostico.date' => 'La fecha del diagnóstico no es válida.',

            'estado.required' => 'El estado del diagnóstico es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}