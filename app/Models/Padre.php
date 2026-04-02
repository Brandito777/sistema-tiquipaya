<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Padre extends Model
{
    protected $table = 'padres';
    protected $fillable = [
        'user_id','nombre','apellido',
        'ci','telefono','direccion'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
}
