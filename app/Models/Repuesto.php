<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
     protected $table = 'repuesto';

    protected $fillable = [
        'nombre',
        'marca',
        'categoria',
        'precio',
    ];
}
