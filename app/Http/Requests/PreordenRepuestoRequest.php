<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreordenRepuestoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_preorden' => 'required|exists:preorden,id',
            'id_repuesto' => 'required|exists:repuesto,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'id_preorden.required' => 'La preorden es obligatoria.',
            'id_preorden.exists' => 'La preorden seleccionada no existe.',

            'id_repuesto.required' => 'El repuesto es obligatorio.',
            'id_repuesto.exists' => 'El repuesto seleccionado no existe.',

            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser mínimo 1.',

            'precio_unitario.required' => 'El precio unitario es obligatorio.',
            'precio_unitario.numeric' => 'El precio unitario debe ser un valor numérico.',
            'precio_unitario.min' => 'El precio unitario no puede ser negativo.',

            'subtotal.required' => 'El subtotal es obligatorio.',
            'subtotal.numeric' => 'El subtotal debe ser un valor numérico.',
            'subtotal.min' => 'El subtotal no puede ser negativo.',
        ];
    }
}