<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';
    protected $fillable = ['nivel_id', 'nombre', 'tiene_tecnico'];

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}
