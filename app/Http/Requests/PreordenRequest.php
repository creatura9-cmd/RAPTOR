<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreordenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $idPreorden = $this->route('id');

        return [
            'id_diagnostico' => [
                'required',
                'exists:diagnostico,id',
                Rule::unique('preorden', 'id_diagnostico')
                    ->ignore($idPreorden, 'id'),
            ],

            'descripcion' => [
                'nullable',
                'string',
            ],

            'mano_obra' => [
                'required',
                'numeric',
                'min:0',
            ],

            'estado' => [
                'required',
                'in:pendiente,aprobada,rechazada',
            ],

            'repuestos' => [
                'nullable',
                'array',
            ],

            'repuestos.*.id_repuesto' => [
                'required',
                'exists:repuesto,id',
                'distinct',
            ],

            'repuestos.*.cantidad' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_diagnostico.required' => 'El diagnóstico es obligatorio.',
            'id_diagnostico.exists' => 'El diagnóstico seleccionado no existe.',
            'id_diagnostico.unique' => 'Este diagnóstico ya tiene una preorden asociada.',

            'descripcion.string' => 'La descripción debe ser texto.',

            'mano_obra.required' => 'La mano de obra es obligatoria.',
            'mano_obra.numeric' => 'La mano de obra debe ser un valor numérico.',
            'mano_obra.min' => 'La mano de obra no puede ser negativa.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',

            'repuestos.array' => 'Los repuestos enviados no tienen un formato válido.',
            'repuestos.*.id_repuesto.required' => 'Debes seleccionar un repuesto.',
            'repuestos.*.id_repuesto.exists' => 'Uno de los repuestos seleccionados no existe.',
            'repuestos.*.id_repuesto.distinct' => 'No puedes agregar el mismo repuesto dos veces. Modifica su cantidad.',

            'repuestos.*.cantidad.required' => 'La cantidad del repuesto es obligatoria.',
            'repuestos.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'repuestos.*.cantidad.min' => 'La cantidad debe ser como mínimo 1.',
        ];
    }
}