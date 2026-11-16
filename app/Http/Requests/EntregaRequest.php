<?php

namespace App\Http\Requests;

use App\Models\Entrega;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EntregaRequest extends FormRequest
{
    /**
     * Determine if the user está autorizado para realizar la solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        $entrega = $this->route('entrega');

        return [
            'id_ordentrabajo' => [
                'required',
                Rule::unique('entrega', 'id_ordentrabajo')
                    ->ignore(
                        $entrega instanceof Entrega
                            ? $entrega->id_entrega
                            : $entrega,
                        'id_entrega'
                    ),
            ],

            'fecha_entrega' => [
                'required',
                'date',
            ],

            'observaciones' => [
                'nullable',
                'string',
            ],
        ];
    }
}