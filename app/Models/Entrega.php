<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrega extends Model
{
    protected $table = 'entrega';

    protected $primaryKey = 'id_entrega';

    protected $fillable = [
        'id_ordentrabajo',
        'fecha_entrega',
        'observaciones',
    ];


    public function ordenTrabajo(): BelongsTo
    {
        return $this->belongsTo(
            OrdenTrabajo::class,
            'id_ordentrabajo',
            'id'
        );
    }
}