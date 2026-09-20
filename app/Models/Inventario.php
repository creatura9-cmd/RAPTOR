<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario';

    protected $fillable = [
        'id_moto',
        'descripcion',
        'fecha_registro',
        'condicion_general',
        'estado_inventario',
    ];

    public function moto()
    {
        return $this->belongsTo(Moto::class, 'id_moto');
    }
}
