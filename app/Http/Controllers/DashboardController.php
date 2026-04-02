<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\Reserva;
use App\Models\Notificacion;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalEstudiantes  = Estudiante::has('inscripciones')->count();
        $inscripcionesHoy  = Inscripcion::whereDate('created_at', date('Y-m-d'))->count();
        $reservasPendientes = Reserva::where('estado', 'pendiente')->count();
        
        // Simulación de ingresos basados en inscripciones aprobadas (Ej: $1500 por alumno)
        $cuposOcupados = Inscripcion::where('gestion', date('Y'))->whereIn('estado', ['aprobada', 'promovido'])->count();
        $ingresos = $cuposOcupados * 1500; 
        
        $recientes = Inscripcion::with('estudiante')->latest('created_at')->take(4)->get();
        
        $cuposTotales = 500;
        $porcentajeCupos = $cuposTotales > 0 ? round(($cuposOcupados / $cuposTotales) * 100) : 0;
        $cuposDisponibles = $cuposTotales - $cuposOcupados;

        return view('dashboard.admin', compact(
            'totalEstudiantes', 'inscripcionesHoy', 'reservasPendientes', 
            'ingresos', 'recientes', 'cuposOcupados', 'cuposTotales', 
            'porcentajeCupos', 'cuposDisponibles'
        ));
    }

    public function padre()
    {
        $padre = auth()->user()->padre;
        $hijos = $padre ? $padre->estudiantes()->with(['inscripcionActual.grado', 'inscripcionActual.documentos'])->get() : collect();
        $notificaciones = auth()->user()->notificaciones()->where('leida', 0)->get();

        return view('dashboard.padre', compact('hijos', 'notificaciones'));
    }

    public function docente()
    {
        return view('dashboard.docente');
    }
}
