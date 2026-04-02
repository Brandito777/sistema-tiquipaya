<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaHijo extends Model
{
    protected $table = 'reserva_hijos';
    protected $fillable = [
        'reserva_id',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'grado_solicitado_id'
    ];

    protected $casts = [
        'grado_solicitado_id' => 'integer',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'grado_solicitado_id');
    }

    // Calcular edad automáticamente
    public function getEdadAttribute()
    {
        return \Carbon\Carbon::parse($this->fecha_nacimiento)->age;
    }
}
