<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_preorden' => [
                'required',
                Rule::exists('preorden', 'id')
                    ->where('estado', 'aprobada'),
            ],

            'id_ordentrabajo' => [
                'nullable',
                'integer',
                'exists:orden_trabajo,id',
            ],

            'tipo_pago' => 'required|in:efectivo,transferencia,tarjeta',

            'fecha_pago' => 'required|date',

            'valor' => 'required|numeric|min:0.01',

            'estado' => 'required|in:pendiente,confirmado,rechazado',

            'observaciones' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id_preorden.required' => 'La preorden es obligatoria.',
            'id_preorden.exists' => 'La preorden debe estar aprobada.',

            'id_ordentrabajo.integer' => 'La orden de trabajo seleccionada no es válida.',
            'id_ordentrabajo.exists' => 'La orden de trabajo seleccionada no existe.',

            'tipo_pago.required' => 'El tipo de pago es obligatorio.',
            'tipo_pago.in' => 'El tipo de pago seleccionado no es válido.',

            'fecha_pago.required' => 'La fecha del pago es obligatoria.',
            'fecha_pago.date' => 'La fecha del pago no es válida.',

            'valor.required' => 'El valor del pago es obligatorio.',
            'valor.numeric' => 'El valor del pago debe ser numérico.',
            'valor.min' => 'El valor del pago debe ser mayor a cero.',

            'estado.required' => 'El estado del pago es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',

            'observaciones.string' => 'Las observaciones deben ser texto.',
        ];
    }
}
