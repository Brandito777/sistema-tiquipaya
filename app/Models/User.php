<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name','email','password','role','active'
    ];

    protected $hidden = ['password','remember_token'];

    public function padre()
    {
        return $this->hasOne(Padre::class);
    }

    public function docente()
    {
        return $this->hasOne(Docente::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function esAdmin()     { return $this->role === 'admin'; }
    public function esSecretaria(){ return $this->role === 'secretaria'; }
    public function esPadre()     { return $this->role === 'padre'; }
    public function esDocente()   { return $this->role === 'docente'; }
}
