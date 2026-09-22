<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $table = 'diagnostico';

    protected $fillable = [
        'id_inventario',
        'id_mecanico',
        'descripcion',
        'fecha_diagnostico',
        'estado',
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'id_inventario');
    }

    public function mecanico()
    {
        return $this->belongsTo(Mecanico::class, 'id_mecanico', 'id_mecanico');
    }
}
