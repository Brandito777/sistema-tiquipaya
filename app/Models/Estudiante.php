<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    protected $fillable = [
        'padre_id','nombre','apellido',
        'ci','fecha_nacimiento','genero','tipo'
    ];

    public function padre()
    {
        return $this->belongsTo(Padre::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function inscripcionActual()
    {
        return $this->hasOne(Inscripcion::class)
                    ->where('gestion', date('Y'))
                    ->latest();
    }
    
    /**
     * Accessor para calcular la edad
     */
    public function getEdadAttribute()
    {
        if ($this->fecha_nacimiento) {
            return Carbon::parse($this->fecha_nacimiento)->age;
        }
        return null;
    }

    /**
     * Accessor que calcula tipo automáticamente basado en gestiones cursadas.
     * Si el estudiante tiene inscripciones en más de 1 gestión → "antiguo".
     * Si solo tiene 1 gestión o ninguna → "nuevo".
     *
     * Uso en vistas: $estudiante->tipo_calculado
     */
    public function getTipoCalculadoAttribute(): string
    {
        $gestiones = $this->inscripciones()->distinct('gestion')->count('gestion');
        return $gestiones > 1 ? 'antiguo' : 'nuevo';
    }
}
