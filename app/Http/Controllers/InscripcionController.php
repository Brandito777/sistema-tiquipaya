<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\DocumentoInscripcion;
use App\Models\User;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    const DOCUMENTOS = [
        'Certificado de nacimiento',
        'Libreta anterior',
        'Fotocopia CI padre',
        'Foto carnet',
        'Certificado medico',
    ];

    public function index()
    {
        $inscripciones = Inscripcion::with(['estudiante','grado.nivel'])
                                    ->where('gestion', date('Y'))
                                    ->paginate(15);
        return view('inscripciones.index', compact('inscripciones'));
    }

    /**
     * Formulario de inscripción inteligente para alumnos ANTIGUOS.
     * Usa Select2 + AJAX para buscar el estudiante y auto-rellenar sus datos.
     */
    public function createAntiguo()
    {
        $grados = Grado::with('nivel')->orderBy('nivel_id')->get();
        return view('inscripciones.antiguo', compact('grados'));
    }

    /**
     * Guarda la inscripción de un alumno antiguo confirmado.
     */
    public function storeAntiguo(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'grado_id'      => 'required|exists:grados,id',
        ]);

        $estudiante = Estudiante::with('padre.user')->findOrFail($request->estudiante_id);

        // Verificar que sea antiguo
        if ($estudiante->tipo !== 'antiguo') {
            return back()->withErrors(['estudiante_id' => 'Este estudiante no está registrado como alumno antiguo.']);
        }

        // Evitar doble inscripción en la misma gestión
        $yaInscrito = $estudiante->inscripciones()
            ->where('gestion', date('Y'))
            ->exists();

        if ($yaInscrito) {
            return back()->withErrors(['estudiante_id' => "Este estudiante ya tiene una inscripción registrada para el año " . date('Y') . "."]);
        }

        $inscripcion = Inscripcion::create([
            'estudiante_id'     => $estudiante->id,
            'grado_id'          => $request->grado_id,
            'gestion'           => date('Y'),
            'estado'            => 'aprobada', // Antiguo ya confirmado: estado directo
            'observaciones'     => $request->observaciones,
            'fecha_inscripcion' => now()->toDateString(),
        ]);

        // Crear checklist de documentos
        foreach (self::DOCUMENTOS as $doc) {
            DocumentoInscripcion::create([
                'inscripcion_id' => $inscripcion->id,
                'tipo'           => $doc,
                'presentado'     => 0,
            ]);
        }

        // Notificar al padre si tiene cuenta
        if ($estudiante->padre?->user_id) {
            Notificacion::create([
                'user_id' => $estudiante->padre->user_id,
                'titulo'  => 'Inscripción confirmada',
                'mensaje' => "La inscripción de {$estudiante->nombre} {$estudiante->apellido} para la gestión " . date('Y') . " fue confirmada.",
                'tipo'    => 'inscripcion',
            ]);
        }

        return redirect()->route('inscripciones.index')
                         ->with('success', "Inscripción de {$estudiante->nombre} {$estudiante->apellido} registrada correctamente.");
    }

    public function create()
    {
        // Solo mostrar estudiantes nuevos que aún NO tienen inscripción en la gestión actual
        $estudiantes = Estudiante::with('padre')
            ->where('tipo', 'nuevo')
            ->whereDoesntHave('inscripciones', function ($q) {
                $q->where('gestion', date('Y'));
            })
            ->orderBy('apellido')
            ->get();

        $grados      = Grado::with('nivel')->orderBy('nivel_id')->get()->unique('id');
        $documentos  = self::DOCUMENTOS;
        return view('inscripciones.create', compact('estudiantes','grados','documentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id'     => 'required|exists:estudiantes,id',
            'grado_id'          => 'required|exists:grados,id',
            'fecha_inscripcion' => 'required|date',
            'est_nombre'        => 'nullable|string|max:255',
            'est_apellido'      => 'nullable|string|max:255',
            'padre_nombre'      => 'nullable|string|max:255',
            'padre_apellido'    => 'nullable|string|max:255',
        ]);

        $estudiante = Estudiante::with('padre')->findOrFail($request->estudiante_id);
        
        // Actualizar datos del estudiante si fueron reescritos
        if ($request->filled('est_nombre') || $request->filled('est_apellido')) {
            $estudiante->update([
                'nombre'   => $request->input('est_nombre', $estudiante->nombre),
                'apellido' => $request->input('est_apellido', $estudiante->apellido),
            ]);
        }
        
        // Actualizar datos del padre si fueron reescritos
        if ($estudiante->padre && ($request->filled('padre_nombre') || $request->filled('padre_apellido'))) {
            $estudiante->padre->update([
                'nombre'   => $request->input('padre_nombre', $estudiante->padre->nombre),
                'apellido' => $request->input('padre_apellido', $estudiante->padre->apellido),
            ]);
        }

        $inscripcion = Inscripcion::create([
            'estudiante_id'     => $request->estudiante_id,
            'grado_id'          => $request->grado_id,
            'gestion'           => date('Y'),
            'estado'            => 'pendiente',
            'observaciones'     => $request->observaciones,
            'fecha_inscripcion' => $request->fecha_inscripcion,
        ]);

        foreach (self::DOCUMENTOS as $doc) {
            DocumentoInscripcion::create([
                'inscripcion_id' => $inscripcion->id,
                'tipo'           => $doc,
                'presentado'     => in_array($doc, $request->documentos ?? []) ? 1 : 0,
            ]);
        }

        if ($estudiante->padre && $estudiante->padre->user_id) {
            Notificacion::create([
                'user_id' => $estudiante->padre->user_id,
                'titulo'  => 'Inscripción registrada',
                'mensaje' => "La inscripción de {$estudiante->nombre} {$estudiante->apellido} fue registrada. Estado: Pendiente.",
                'tipo'    => 'inscripcion',
            ]);
        }

        return redirect()->route('inscripciones.index')
                         ->with('success', 'Inscripción registrada correctamente.');
    }

    public function cambiarEstado(Request $request, Inscripcion $inscripcion)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,aprobada,rechazada,abandono,retirado,promovido',
        ]);

        $inscripcion->update(['estado' => $request->estado]);

        $estudiante = $inscripcion->estudiante;
        if ($estudiante->padre && $estudiante->padre->user_id) {
            Notificacion::create([
                'user_id' => $estudiante->padre->user_id,
                'titulo'  => 'Estado de inscripción actualizado',
                'mensaje' => "La inscripción de {$estudiante->nombre} cambió a: {$request->estado}.",
                'tipo'    => 'inscripcion',
            ]);
        }

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function edit(Inscripcion $inscripcion)
    {
        // Load the related models so the view has the necessary data to show the form
        $inscripcion->load(['estudiante.padre', 'grado.nivel', 'documentos']);
        $documentos = self::DOCUMENTOS;
        return view('inscripciones.edit', compact('inscripcion', 'documentos'));
    }

    public function update(Request $request, Inscripcion $inscripcion)
    {
        $request->validate([
            'fecha_inscripcion' => 'required|date',
            'est_nombre'        => 'nullable|string|max:255',
            'est_apellido'      => 'nullable|string|max:255',
            'padre_nombre'      => 'nullable|string|max:255',
            'padre_apellido'    => 'nullable|string|max:255',
        ]);

        // Actualizar datos del estudiante si fueron reescritos
        $estudiante = $inscripcion->estudiante;
        if ($request->filled('est_nombre') || $request->filled('est_apellido')) {
            $estudiante->update([
                'nombre'   => $request->input('est_nombre', $estudiante->nombre),
                'apellido' => $request->input('est_apellido', $estudiante->apellido),
            ]);
        }

        // Actualizar datos del padre si fueron reescritos
        if ($estudiante->padre && ($request->filled('padre_nombre') || $request->filled('padre_apellido'))) {
            $estudiante->padre->update([
                'nombre'   => $request->input('padre_nombre', $estudiante->padre->nombre),
                'apellido' => $request->input('padre_apellido', $estudiante->padre->apellido),
            ]);
        }

        $inscripcion->update([
            'fecha_inscripcion' => $request->fecha_inscripcion,
            'observaciones'     => $request->observaciones,
            'estado'            => $inscripcion->estado === 'pendiente' ? 'aprobada' : $inscripcion->estado,
        ]);

        // Actualizar documentos (borramos los viejos de esta inscripcion si queremos resetear o simplemente iteramos)
        // Para simplificar, eliminamos los actuales y recrear según los checkboxes:
        $inscripcion->documentos()->delete();
        
        foreach (self::DOCUMENTOS as $doc) {
            DocumentoInscripcion::create([
                'inscripcion_id' => $inscripcion->id,
                'tipo'           => $doc,
                'presentado'     => in_array($doc, $request->documentos ?? []) ? 1 : 0,
            ]);
        }

        // Notificar si pasa a aprobada
        if ($inscripcion->wasChanged('estado') && $inscripcion->estado === 'aprobada' && $estudiante->padre && $estudiante->padre->user_id) {
            Notificacion::create([
                'user_id' => $estudiante->padre->user_id,
                'titulo'  => 'Inscripción confirmada',
                'mensaje' => "La inscripción de {$estudiante->nombre} {$estudiante->apellido} fue registrada y aprobada formalmente.",
                'tipo'    => 'inscripcion',
            ]);
        }

        return redirect()->route('inscripciones.index')
                         ->with('success', 'Inscripción actualizada y guardada correctamente.');
    }
}
