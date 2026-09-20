<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100',

            'apellido' => 'required|string|max:100',

            'tipo_documento' => 'required|in:CC,CE,TI,NIT,PASAPORTE',

            'numero_documento' => [
                'required',
                'string',
                'max:50',
                'unique:cliente,numero_documento,NULL,id,tipo_documento,' . $this->tipo_documento,
            ],

            'telefono' => 'nullable|string|max:20',

            'correo_electronico' => 'nullable|email|max:150',

            'direccion' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',

            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.string' => 'El apellido debe ser texto.',
            'apellido.max' => 'El apellido no puede superar los 100 caracteres.',

            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento.in' => 'El tipo de documento seleccionado no es válido.',

            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.max' => 'El número de documento no puede superar los 50 caracteres.',
            'numero_documento.unique' => 'Ya existe un cliente con este tipo y número de documento.',

            'telefono.max' => 'El teléfono no puede superar los 20 caracteres.',

            'correo_electronico.email' => 'El correo electrónico no tiene un formato válido.',
            'correo_electronico.max' => 'El correo electrónico no puede superar los 150 caracteres.',

            'direccion.max' => 'La dirección no puede superar los 255 caracteres.',
        ];
    }
}
