<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Inventario extends Model
{
    protected $table = 'inventario';

    protected $fillable = [
        'recepcion',
        'id_moto',
        'id_mecanico',
        'descripcion',
        'fecha_registro',
        'condicion_general',
        'estado_inventario',
    ];

    public function moto(): BelongsTo
    {
        return $this->belongsTo(Moto::class, 'id_moto', 'id');
    }

    public function diagnosticos(): HasOne
    {
        return $this->hasOne(Diagnostico::class, 'id_inventario', 'id');
    }

    public function mecanico(): BelongsTo
    {
        return $this->belongsTo(
            Mecanico::class,
            'id_mecanico',
            'id_mecanico'
        );
    }

    public function ordenTrabajo(): HasMany
    {
        return $this->hasMany(OrdenTrabajo::class, 'id_inventario', 'id');
    }
}
