<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiagnosticoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $idDiagnostico = $this->route('diagnostico');

        $reglaInventario = Rule::unique('diagnostico', 'id_inventario');

        if ($idDiagnostico !== null) {
            $reglaInventario->ignore($idDiagnostico, 'id');
        }

        return [
            'id_inventario' => [
                'required',
                'exists:inventario,id',
                $reglaInventario,
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

        ];
    }

    public function messages(): array
    {
        return [
            'id_inventario.required' => 'El inventario es obligatorio.',
            'id_inventario.exists' => 'El inventario seleccionado no existe.',
            'id_inventario.unique' => 'Esta recepción ya tiene un diagnóstico registrado.',

            'id_mecanico.required' => 'El mecánico es obligatorio.',
            'id_mecanico.exists' => 'El mecánico seleccionado no existe.',

            'descripcion.required' => 'La descripción del diagnóstico es obligatoria.',
            'descripcion.string' => 'La descripción debe ser texto.',

            'fecha_diagnostico.required' => 'La fecha del diagnóstico es obligatoria.',
            'fecha_diagnostico.date' => 'La fecha del diagnóstico no es válida.',

        ];
    }
}
