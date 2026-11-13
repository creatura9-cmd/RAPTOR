<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Mecanico extends Model
{
    protected $table = 'mecanicos';

    protected $primaryKey = 'id_mecanico';

    protected $fillable = [
        'nombre',
        'apellido',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'correo_electronico',
        'especialidad',
    ];

    public function ordenesTrabajo(): HasMany
    {
        return $this->hasMany(
            OrdenTrabajo::class,
            'id_mecanico',
            'id_mecanico'
        );
    }
}
