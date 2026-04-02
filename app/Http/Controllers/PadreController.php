<?php

namespace App\Http\Controllers;

use App\Models\Padre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PadreController extends Controller
{
    // Lista de padres
    public function index()
    {
        // Solo mostrar padres que tengan al menos un estudiante con inscripción aprobada en la gestión actual
        $padres = Padre::whereHas('estudiantes.inscripciones', function ($q) {
            $q->whereIn('estado', ['aprobada', 'promovido'])
              ->where('gestion', date('Y'));
        })->with([
            'user',
            'estudiantes.inscripcionActual'
        ])->paginate(15);
        
        return view('padres.index', compact('padres'));
    }

    // Formulario crear padre
    public function create()
    {
        return view('padres.create');
    }

    // Guardar padre + su cuenta de usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'apellido'  => 'required|string|max:100',
            'ci'        => 'required|string|max:20|unique:padres,ci',
            'telefono'  => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6',
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'ci.required'       => 'El CI es obligatorio.',
            'ci.unique'         => 'Este CI ya está registrado.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        // Crear usuario
        $user = User::create([
            'name'     => $request->nombre . ' ' . $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'padre',
            'active'   => 1,
        ]);

        // Crear padre vinculado al usuario
        Padre::create([
            'user_id'   => $user->id,
            'nombre'    => $request->nombre,
            'apellido'  => $request->apellido,
            'ci'        => $request->ci,
            'telefono'  => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        return redirect()->route('padres.index')
                         ->with('success', "Cuenta creada. Correo: {$request->email} | Contraseña: {$request->password}");
    }

    // Ver detalle del padre y sus hijos
    public function show(Padre $padre)
    {
        $padre->load('user', 'estudiantes.inscripcionActual.grado.nivel');
        return view('padres.show', compact('padre'));
    }

    // Activar o desactivar cuenta
    public function toggleActivo(Padre $padre)
    {
        $padre->user->update([
            'active' => !$padre->user->active
        ]);

        $estado = $padre->user->active ? 'activada' : 'desactivada';
        return back()->with('success', "Cuenta {$estado} correctamente.");
    }

    // Formulario editar padre
    public function edit(Padre $padre)
    {
        return view('padres.edit', compact('padre'));
    }

    // Actualizar datos del padre
    public function update(Request $request, Padre $padre)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'apellido'  => 'required|string|max:100',
            'ci'        => 'required|string|max:20|unique:padres,ci,' . $padre->id,
            'telefono'  => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $padre->user_id,
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'ci.required'       => 'El CI es obligatorio.',
            'ci.unique'         => 'Este CI ya está registrado.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Este correo ya está registrado.',
        ]);

        // Actualizar datos del padre
        $padre->update([
            'nombre'    => $request->nombre,
            'apellido'  => $request->apellido,
            'ci'        => $request->ci,
            'telefono'  => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        // Actualizar datos del usuario
        $padre->user->update([
            'name'  => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
        ]);

        return redirect()->route('padres.index')
                         ->with('success', 'Datos del padre actualizados correctamente.');
    }

    // Eliminar completamente al padre, hijos y cuenta
    public function destroy(Padre $padre)
    {
        $nombre = $padre->nombre . ' ' . $padre->apellido;
        
        // Eliminación en cascada manual (por seguridad)
        foreach ($padre->estudiantes as $estudiante) {
            foreach ($estudiante->inscripciones as $ins) {
                // Borrar requisitos físicos
                \App\Models\DocumentoInscripcion::where('inscripcion_id', $ins->id)->delete();
                $ins->delete();
            }
            $estudiante->delete();
        }

        $user = $padre->user;
        $padre->delete();
        
        // Destruir credenciales
        if ($user) {
            \App\Models\Notificacion::where('user_id', $user->id)->delete();
            $user->delete();
        }

        return redirect()->route('padres.index')
                         ->with('success', "El perfil del padre {$nombre}, su cuenta de acceso y todo el registro de sus hijos (junto a sus inscripciones) han sido borrados de raíz.");
    }

    // Mostrar formulario para asignar usuario web a un padre existente sin cuenta
    public function createUser(Padre $padre)
    {
        if ($padre->user_id) {
            return redirect()->route('padres.index')->withErrors('Este padre ya tiene una cuenta asociada.');
        }
        return view('padres.create_user', compact('padre'));
    }

    // Procesar asignación de usuario web
    public function storeUser(Request $request, Padre $padre)
    {
        if ($padre->user_id) {
            return redirect()->route('padres.index')->withErrors('Este padre ya tiene una cuenta asociada.');
        }

        $request->validate([
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $user = User::create([
            'name'     => $padre->nombre . ' ' . $padre->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'padre',
            'active'   => 1,
        ]);

        $padre->update(['user_id' => $user->id]);

        return redirect()->route('padres.index')
                         ->with('success', "Cuenta web asignada correctamente al padre {$padre->nombre}. Puede iniciar sesión ahora.");
    }
}
