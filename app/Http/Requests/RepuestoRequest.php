<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepuestoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'marca' => [
                'nullable',
                'string',
                'max:100',
            ],

            'categoria' => [
                'required',
                'in:motor,transmision,frenos,electrico,combustible,suspension,direccion,ruedas,accesorios',
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del repuesto es obligatorio.',
            'nombre.string' => 'El nombre del repuesto debe ser texto.',
            'nombre.max' => 'El nombre del repuesto no puede tener más de 100 caracteres.',

            'marca.string' => 'La marca debe ser texto.',
            'marca.max' => 'La marca no puede tener más de 100 caracteres.',

            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.in' => 'La categoría seleccionada no es válida.',

            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
        ];
    }
}