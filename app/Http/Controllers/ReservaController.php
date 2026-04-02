<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\ReservaHijo;
use App\Models\Grado;
use App\Models\Padre;
use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Formulario público
    public function create()
    {
        $grados = Grado::distinct()->with('nivel')->orderBy('nivel_id')->get();
        return view('reservas.create', compact('grados'));
    }

    // Guardar reserva con N hijos
    public function store(Request $request)
    {
        $request->validate([
            'nombre_padre'    => 'required|string|max:100',
            'apellido_padre'  => 'required|string|max:100',
            'edad_padre'      => 'required|integer|min:18|max:99',
            'telefono_padre'  => 'required|string|max:20',
            'email_padre'     => 'nullable|email',
            'cantidad_hijos'  => 'required|integer|min:1|max:10',
            'hijos.*.nombre'           => 'required|string|max:100',
            'hijos.*.apellido'         => 'required|string|max:100',
            'hijos.*.fecha_nacimiento' => 'required|date',
            'hijos.*.grado_id'         => 'required|exists:grados,id',
        ], [
            'nombre_padre.required'    => 'El nombre del padre es obligatorio.',
            'apellido_padre.required'  => 'El apellido del padre es obligatorio.',
            'edad_padre.required'      => 'La edad del padre es obligatoria.',
            'telefono_padre.required'  => 'El teléfono es obligatorio.',
            'hijos.*.nombre.required'           => 'El nombre del hijo es obligatorio.',
            'hijos.*.apellido.required'         => 'El apellido del hijo es obligatorio.',
            'hijos.*.fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'hijos.*.grado_id.required'         => 'El grado es obligatorio.',
        ]);

        // Crear la reserva del padre
        $reserva = Reserva::create([
            'nombre_padre'   => $request->nombre_padre,
            'apellido_padre' => $request->apellido_padre,
            'edad_padre'     => $request->edad_padre,
            'telefono_padre' => $request->telefono_padre,
            'email_padre'    => $request->email_padre,
            'cantidad_hijos' => $request->cantidad_hijos,
            'estado'         => 'pendiente',
            'gestion'        => date('Y'),
        ]);

        // Crear cada hijo
        foreach ($request->hijos as $hijo) {
            ReservaHijo::create([
                'reserva_id'         => $reserva->id,
                'nombre'             => $hijo['nombre'],
                'apellido'           => $hijo['apellido'],
                'fecha_nacimiento'   => $hijo['fecha_nacimiento'],
                'grado_solicitado_id'=> $hijo['grado_id'],
            ]);
        }

        return redirect()->route('reservas.confirmacion')
                         ->with('success', 'Reserva registrada correctamente. El colegio se contactará pronto.');
    }

    public function confirmacion()
    {
        return view('reservas.confirmacion');
    }

    // Panel admin
    public function index()
    {
        $reservas = Reserva::with('hijos.grado.nivel')->paginate(15);
        return view('reservas.index', compact('reservas'));
    }

    public function show(Reserva $reserva)
    {
        $reserva->load('hijos.grado.nivel');
        return view('reservas.show', compact('reserva'));
    }

    /**
     * Convierte una reserva aprobada en registros reales de Padre + Estudiantes.
     * Esto ocurre cuando el padre se presenta físicamente al colegio con sus documentos.
     */
    public function convertirAEstudiantes(Reserva $reserva)
    {
        if (!in_array($reserva->estado, ['aprobada', 'confirmada'])) {
            return back()->withErrors(['error' => 'Solo se pueden convertir reservas en estado Aprobada.']);
        }

        // Buscar si ya existe un padre con ese teléfono para evitar duplicados
        $padre = Padre::whereHas('user', function($q) use ($reserva) {
            $q->where('email', $reserva->email_padre ?? '');
        })->first();

        if (!$padre) {
            // Crear el usuario del padre
            $email = $reserva->email_padre
                ?? strtolower(str_replace(' ', '.', $reserva->nombre_padre)) . '.' . $reserva->id . '@tiquipaya.edu.bo';

            $user = User::create([
                'name'     => $reserva->nombre_padre . ' ' . $reserva->apellido_padre,
                'email'    => $email,
                'password' => Hash::make($reserva->telefono_padre), // Contraseña inicial = teléfono
                'role'     => 'padre',
                'active'   => 1,
            ]);

            $padre = Padre::create([
                'user_id'   => $user->id,
                'nombre'    => $reserva->nombre_padre,
                'apellido'  => $reserva->apellido_padre,
                'telefono'  => $reserva->telefono_padre,
                // Placeholders: el admin completará estos datos cuando el padre se presente físicamente
                'ci'        => 'PENDIENTE-R' . $reserva->id,
                'direccion' => 'Pendiente de completar',
            ]);
        }

        // Crear un Estudiante por cada hijo de la reserva
        $creados = 0;
        foreach ($reserva->hijos as $hijo) {
            // Evitar duplicar si ya fue convertido antes
            $existe = Estudiante::where('nombre', $hijo->nombre)
                ->where('apellido', $hijo->apellido)
                ->where('padre_id', $padre->id)
                ->exists();

            if (!$existe) {
                $est = Estudiante::create([
                    'padre_id'         => $padre->id,
                    'nombre'           => $hijo->nombre,
                    'apellido'         => $hijo->apellido,
                    'fecha_nacimiento' => $hijo->fecha_nacimiento,
                    'genero'           => $hijo->genero ?? 'M',
                    'tipo'             => 'nuevo',
                    'ci'               => 'PENDIENTE-R' . $reserva->id,
                ]);
                
                \App\Models\Inscripcion::create([
                    'estudiante_id'     => $est->id,
                    'grado_id'          => $hijo->grado_solicitado_id,
                    'gestion'           => date('Y'),
                    'estado'            => 'pendiente',
                    'fecha_inscripcion' => now(),
                    'observaciones'     => 'Inscripción generada desde reserva confirmada.',
                ]);
                $creados++;
            }
        }

        // Marcar la reserva como procesada usando 'cancelada' (único valor disponible del ENUM
        // que indica que ya fue gestionada). Esto evita doble conversión.
        $reserva->update(['estado' => 'cancelada']);

        $msg = $creados > 0
            ? "✅ {$creados} estudiante(s) agregados a la lista de Inscripciones Pendientes. Por favor confirme sus documentos en la tabla para finalizarlas."
            : 'ℹ El padre y los estudiantes ya existían en el sistema (sin duplicar).';

        return redirect()->route('inscripciones.index')->with('success', $msg);
    }

    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada,convertida',
        ]);

        $reserva->update(['estado' => $request->estado]);
        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy(Reserva $reserva)
    {
        \App\Models\ReservaHijo::where('reserva_id', $reserva->id)->delete();
        $reserva->delete();

        return redirect()->route('reservas.index')->with('success', 'La reserva ha sido eliminada permanentemente del sistema.');
    }
}
