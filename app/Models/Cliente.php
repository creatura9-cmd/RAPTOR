<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';

    protected $fillable = [
        'nombre',
        'apellido',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'correo_electronico',
        'direccion',
    ];

     public function motos()
    {
        return $this->hasMany(Moto::class, 'id_cliente');
    }
}
