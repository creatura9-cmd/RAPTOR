<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class, 'id_inventario', 'id');
    }

    public function mecanico(): BelongsTo
    {
        return $this->belongsTo(Mecanico::class, 'id_mecanico', 'id_mecanico');
    }

    public function preordenes(): HasOne
    {
        return $this->hasOne(Preorden::class, 'id_diagnostico', 'id');
    }
}