<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MecanicoRequest extends FormRequest
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

            'tipo_documento' => 'required|in:CC,CE,TI,PAS',

            'numero_documento' => 'required|string|max:20|unique:mecanicos,numero_documento,' . $this->route('mecanico') . ',id_mecanico',

            'telefono' => 'required|string|max:20',

            'correo_electronico' => 'required|email|max:150|unique:mecanicos,correo_electronico,' . $this->route('mecanico') . ',id_mecanico',

            'especialidad' => 'required|string|max:100',
        ];
    }
}