<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrdenTrabajo extends Model
{
    protected $table = 'orden_trabajo';

    protected $fillable = [
        'id_inventario',
        'id_preorden',
        'id_mecanico',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'observaciones',
    ];

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class, 'id_inventario');
    }

    public function preorden(): BelongsTo
    {
        return $this->belongsTo(Preorden::class, 'id_preorden');
    }

    public function mecanico(): BelongsTo
    {
        return $this->belongsTo(
            Mecanico::class,
            'id_mecanico',
            'id_mecanico'
        );
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_ordentrabajo');
    }

    public function entrega(): HasOne
    {
        return $this->hasOne(Entrega::class, 'id_ordentrabajo', 'id');
    }
}
