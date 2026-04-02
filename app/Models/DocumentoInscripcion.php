<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoInscripcion extends Model
{
    protected $table = 'documentos_inscripcion';
    protected $fillable = ['inscripcion_id', 'tipo', 'presentado'];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }
}
