<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Preorden extends Model
{
    protected $table = 'preorden';

    protected $fillable = [
        'id_diagnostico',
        'descripcion',
        'mano_obra',
        'total',
        'estado',
    ];

    public function diagnostico(): BelongsTo
    {
        return $this->belongsTo(Diagnostico::class, 'id_diagnostico', 'id');
    }

    public function repuestos(): HasMany
    {
        return $this->hasMany(PreordenRepuesto::class, 'id_preorden', 'id');
    }

    public function ordenTrabajo(): HasOne
    {
        return $this->hasOne(OrdenTrabajo::class, 'id_preorden', 'id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_preorden', 'id');
    }
}