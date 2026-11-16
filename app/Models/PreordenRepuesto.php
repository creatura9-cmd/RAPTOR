<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreordenRepuesto extends Model
{
    protected $table = 'preorden_repuesto';

    protected $fillable = [
        'id_preorden',
        'id_repuesto',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function preorden(): BelongsTo
    {
        return $this->belongsTo(Preorden::class, 'id_preorden', 'id');
    }

    public function repuesto(): BelongsTo
    {
        return $this->belongsTo(Repuesto::class, 'id_repuesto', 'id');
    }
}