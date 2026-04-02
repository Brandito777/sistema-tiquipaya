<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $fillable = [
        'estudiante_id','grado_id','gestion',
        'estado','observaciones','fecha_inscripcion'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoInscripcion::class);
    }
}
