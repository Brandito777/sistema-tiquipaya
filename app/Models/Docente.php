<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'docentes';
    protected $fillable = [
        'user_id','nombre','apellido',
        'ci','especialidad'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
