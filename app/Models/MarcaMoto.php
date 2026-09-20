<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcaMoto extends Model
{
    protected $table = 'marca_moto';

    protected $fillable = [
        'nombre_marca',
    ];

    public function motos()
    {
        return $this->hasMany(Moto::class, 'id_marca');
    }
}
