<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\User;
use App\Models\Padre;
use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = auth()->user()->notificaciones()->latest()->paginate(15);
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida(Notificacion $notificacion)
    {
        $notificacion->update(['leida' => 1]);
        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function crear(Request $request)
    {
        // GET: mostrar formulario
        if ($request->isMethod('get')) {
            $niveles = Nivel::all();
            $grados = Grado::distinct()->with('nivel')->orderBy('nivel_id')->get();
            // ✅ CORRECCIÓN: Obtener padres reales (tabla padres) en lugar de todos los usuarios
            $padres = Padre::with('user')->orderBy('apellido')->get();
            
            return view('notificaciones.crear', compact('niveles', 'grados', 'padres'));
        }

        // POST: guardar notificación
        $request->validate([
            'titulo'  => 'required|string|max:150',
            'mensaje' => 'required|string',
            'destinatarios' => 'required|in:todos,grado,nivel,padre',
        ]);

        $titulo = $request->titulo;
        $mensaje = $request->mensaje;
        $tipo = 'general';
        $destinatarios = [];

        // Determinar a quiénes enviar
        if ($request->destinatarios === 'todos') {
            $destinatarios = User::where('role', 'padre')->pluck('id')->toArray();
        } 
        elseif ($request->destinatarios === 'grado' && $request->grado_id) {
            // Todos los padres cuyos hijos están en este grado
            $destinatarios = User::whereHas('padre.estudiantes.inscripcionActual', function ($q) use ($request) {
                $q->where('grado_id', $request->grado_id)
                  ->where('gestion', date('Y'));
            })->where('role', 'padre')->pluck('id')->toArray();
        }
        elseif ($request->destinatarios === 'nivel' && $request->nivel_id) {
            // Todos los padres cuyos hijos están en este nivel
            $destinatarios = User::whereHas('padre.estudiantes.inscripcionActual.grado', function ($q) use ($request) {
                $q->where('nivel_id', $request->nivel_id)
                  ->where('gestion', date('Y'));
            })->where('role', 'padre')->pluck('id')->toArray();
        }
        elseif ($request->destinatarios === 'padre' && $request->padre_id) {
            // ✅ CORRECCIÓN: Obtener el user_id del padre específico
            $padre = Padre::findOrFail($request->padre_id);
            $destinatarios = [$padre->user_id];
        }

        // Crear notificación para cada destinatario
        foreach ($destinatarios as $padreId) {
            Notificacion::create([
                'user_id' => $padreId,
                'titulo'  => $titulo,
                'mensaje' => $mensaje,
                'tipo'    => $tipo,
            ]);
        }

        return back()->with('success', 'Notificación enviada a ' . count($destinatarios) . ' padre(s).');
    }
}
