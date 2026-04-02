<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    protected $fillable = [
        'nombre_padre',
        'apellido_padre',
        'edad_padre',
        'telefono_padre',
        'email_padre',
        'cantidad_hijos',
        'estado',
        'gestion'
    ];

    public function hijos()
    {
        return $this->hasMany(ReservaHijo::class);
    }
}
