<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_marca' => [
                'required',
                'integer',
                'exists:marca_moto,id',
            ],

            'id_cliente' => [
                'required',
                'integer',
                'exists:cliente,id',
            ],

            'modelo' => [
                'required',
                'string',
                'max:100',
            ],

            'anio' => [
                'required',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],

            'placa' => [
                'required',
                'string',
                'max:20',
                Rule::unique('moto', 'placa')->ignore($this->route('id')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_marca.required' => 'La marca es obligatoria.',
            'id_marca.exists' => 'La marca seleccionada no existe.',

            'id_cliente.required' => 'El cliente es obligatorio.',
            'id_cliente.exists' => 'El cliente seleccionado no existe.',

            'modelo.required' => 'El modelo es obligatorio.',

            'anio.required' => 'El año es obligatorio.',
            'anio.min' => 'El año no puede ser menor a 1900.',
            'anio.max' => 'El año no puede ser mayor al año actual.',

            'placa.required' => 'La placa es obligatoria.',
            'placa.unique' => 'Esta placa ya está registrada.',
        ];
    }
}

