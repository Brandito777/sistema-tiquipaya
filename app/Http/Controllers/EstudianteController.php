<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Padre;
use App\Models\Nivel;
use App\Models\Grado;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function index(Request $request)
    {
        $query = Estudiante::with(['padre', 'inscripcionActual.grado.nivel'])
            ->has('inscripciones'); // Ocultar estudiantes "en el limbo" (convertidos de reserva pero aún no inscritos)

        // Filtro por niveles
        if ($request->has('nivel_id') && $request->nivel_id) {
            $query->whereHas('inscripcionActual.grado', function ($q) use ($request) {
                $q->where('nivel_id', $request->nivel_id);
            });
        }

        // Filtro por grados
        if ($request->has('grado_id') && $request->grado_id) {
            $query->whereHas('inscripcionActual', function ($q) use ($request) {
                $q->where('grado_id', $request->grado_id);
            });
        }

        // Filtro por tipo (nuevo/antiguo)
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        // Búsqueda por nombre, apellido o CI
        if ($request->has('buscar') && $request->buscar) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%$buscar%")
                  ->orWhere('apellido', 'like', "%$buscar%")
                  ->orWhere('ci', 'like', "%$buscar%");
            });
        }

        $estudiantes = $query->paginate(15);
        
        // Datos para filtros (sidebars) - sin duplicados
        $niveles = Nivel::distinct()->orderBy('id')->get();
        $grados = Grado::distinct()->orderBy('nivel_id')->get();

        return view('estudiantes.index', compact('estudiantes', 'niveles', 'grados'));
    }

    public function create()
    {
        $padres = Padre::all();
        return view('estudiantes.create', compact('padres'));
    }

    /**
     * Endpoint AJAX para Select2.
     * Busca estudiantes tipo='antiguo' por nombre/apellido.
     * Devuelve JSON con id, text (nombre completo) y datos extra para auto-rellenar el formulario.
     */
    public function buscarAjax(Request $request)
    {
        $q    = $request->get('q', '');
        $tipo = $request->get('tipo', 'antiguo'); // 'antiguo' o 'nuevo'

        $query = Estudiante::with(['padre', 'inscripcionActual.grado.nivel'])
            ->where('tipo', $tipo)
            ->where(function($q2) use ($q) {
                $q2->where('nombre', 'like', "%{$q}%")
                   ->orWhere('apellido', 'like', "%{$q}%")
                   ->orWhere('ci', 'like', "%{$q}%");
            });

        // Para tipo=nuevo: solo los que no tienen inscripción en la gestión actual
        if ($tipo === 'nuevo') {
            $query->whereDoesntHave('inscripciones', function($q3) {
                $q3->where('gestion', date('Y'));
            });
        }

        $estudiantes = $query->limit(20)->get()->map(function($est) {
            return [
                'id'           => $est->id,
                'text'         => $est->nombre . ' ' . $est->apellido,
                'padre_nombre' => $est->padre ? $est->padre->nombre . ' ' . $est->padre->apellido : '—',
                'padre_tel'    => $est->padre?->telefono ?? '—',
                'grado_actual' => $est->inscripcionActual?->grado?->nombre ?? 'Sin inscripción previa',
                'nivel_actual' => $est->inscripcionActual?->grado?->nivel?->nombre ?? '',
                'grado_id'     => $est->inscripcionActual?->grado_id ?? null,
            ];
        });

        return response()->json(['results' => $estudiantes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:100',
            'apellido'         => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero'           => 'required|in:M,F',
            'padre_id'         => 'nullable|exists:padres,id',
        ], [
            'nombre.required'           => 'El nombre es obligatorio.',
            'apellido.required'         => 'El apellido es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'genero.required'           => 'El género es obligatorio.',
        ]);

        Estudiante::create([
            'nombre'           => $request->nombre,
            'apellido'         => $request->apellido,
            'ci'               => $request->ci,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero'           => $request->genero,
            'padre_id'         => $request->padre_id,
            'tipo'             => 'antiguo', // Este form es solo para alumnos que continúan
        ]);

        return redirect()->route('estudiantes.index')
                         ->with('success', 'Estudiante antiguo registrado. Ahora puede crear su inscripción.');
    }

    public function show(Estudiante $estudiante)
    {
        $estudiante->load(
            'padre.estudiantes.inscripcionActual.grado',
            'inscripcionActual.grado.nivel',
            'inscripciones.grado.nivel'
        );
        return view('estudiantes.show', compact('estudiante'));
    }

    public function edit(Estudiante $estudiante)
    {
        $padres = Padre::all();
        return view('estudiantes.edit', compact('estudiante', 'padres'));
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $estudiante->update($request->only(
            'nombre', 'apellido', 'ci', 'fecha_nacimiento', 'genero', 'tipo', 'padre_id'
        ));

        // Si el estudiante tiene una inscripción activa enviamos también las observaciones y documentos
        if ($estudiante->inscripcionActual && $request->has('documentos_form')) {
            $ins = $estudiante->inscripcionActual;
            $ins->update(['observaciones' => $request->observaciones]);

            // Resincronizar los documentos
            $ins->documentos()->delete();
            $todosDocs = \App\Http\Controllers\InscripcionController::DOCUMENTOS;
            
            foreach ($todosDocs as $doc) {
                \App\Models\DocumentoInscripcion::create([
                    'inscripcion_id' => $ins->id,
                    'tipo'           => $doc,
                    'presentado'     => in_array($doc, $request->documentos ?? []) ? 1 : 0,
                ]);
            }
        }

        return redirect()->route('estudiantes.show', $estudiante)
                         ->with('success', 'Ficha del estudiante actualizada correctamente.');
    }

    public function destroy(Estudiante $estudiante)
    {
        $padre = $estudiante->padre;
        $nombreEstudiante = $estudiante->nombre . ' ' . $estudiante->apellido;
        
        // Cascading de las inscripciones por seguridad
        foreach($estudiante->inscripciones as $ins) {
             \App\Models\DocumentoInscripcion::where('inscripcion_id', $ins->id)->delete();
             $ins->delete();
        }
        
        $estudiante->delete();

        // Verificamos si al padre le quedan hijos
        if ($padre) {
            $hijosRestantes = $padre->estudiantes()->count();
            
            if ($hijosRestantes === 0) {
                // Si ya no le quedan hijos, eliminamos toda la cuenta del padre
                $user = $padre->user;
                $padre->delete();
                if ($user) {
                    \App\Models\Notificacion::where('user_id', $user->id)->delete();
                    $user->delete();
                }
                return redirect()->route('estudiantes.index')
                         ->with('success', "Estudiante {$nombreEstudiante} eliminado. El registro y la cuenta web del tutor también fueron borrados permanentemente ya que no le quedan hijos en el sistema.");
            } else {
                // Si tiene otros hijos, mandarle notificacion
                if ($padre->user_id) {
                    \App\Models\Notificacion::create([
                        'user_id' => $padre->user_id,
                        'titulo'  => 'Baja de estudiante',
                        'mensaje' => "El estudiante {$nombreEstudiante} ha sido dado de baja permanentemente del sistema educativo.",
                        'leida'   => 0
                    ]);
                }
            }
        }

        return redirect()->route('estudiantes.index')
                         ->with('success', "Instancia del estudiante {$nombreEstudiante} eliminada correctamente de los registros.");
    }
}
