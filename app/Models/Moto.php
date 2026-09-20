<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moto extends Model
{
    protected $table = 'moto';

    protected $fillable = [
        'id_marca',
        'id_cliente',
        'modelo',
        'anio',
        'placa',
    ];

    public function marca()
    {
        return $this->belongsTo(MarcaMoto::class, 'id_marca');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function inventarios()
    {
       return $this->hasMany(Inventario::class, 'id_moto');
    }
}
