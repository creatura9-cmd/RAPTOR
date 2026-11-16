<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdenTrabajoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // La preorden se selecciona al crear la OT.
            // En la actualización no se permite modificarla.
            'id_preorden' => $this->isMethod('post')
                ? 'required|integer|exists:preorden,id'
                : 'prohibited',

            'id_inventario' => 'prohibited',

            'id_mecanico' => 'required|exists:mecanicos,id_mecanico',

            'fecha_inicio' => 'required|date',

            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',

            'estado' => 'required|in:abierta,en_proceso,finalizada',

            'observaciones' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id_preorden.required' => 'La preorden es obligatoria.',
            'id_preorden.integer' => 'La preorden seleccionada no es válida.',
            'id_preorden.exists' => 'La preorden seleccionada no existe.',
            'id_preorden.prohibited' => 'No se permite modificar la preorden de una orden de trabajo existente.',

            'id_inventario.prohibited' => 'El inventario se obtiene automáticamente desde la preorden.',

            'id_mecanico.required' => 'El mecánico es obligatorio.',
            'id_mecanico.exists' => 'El mecánico seleccionado no existe.',

            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio no es válida.',

            'fecha_fin.date' => 'La fecha de finalización no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha de finalización debe ser igual o posterior a la fecha de inicio.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',

            'observaciones.string' => 'Las observaciones deben ser texto.',
        ];
    }
}
