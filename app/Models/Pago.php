<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pago';

    protected $fillable = [
        'id_preorden',
        'id_ordentrabajo',
        'tipo_pago',
        'fecha_pago',
        'valor',
        'estado',
        'observaciones',
    ];

    public function preorden(): BelongsTo
    {
        return $this->belongsTo(Preorden::class, 'id_preorden', 'id');
    }

    public function ordenTrabajo(): BelongsTo
    {
        return $this->belongsTo(OrdenTrabajo::class, 'id_ordentrabajo', 'id');
    }
}