@extends('layouts.app')

@section('titulo', 'Panel del Padre')

@section('content')
<style>
    .padre-header {
        border-bottom: 2px solid #1a5276;
        padding-bottom: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .bell-icon {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 8px 15px;
        border-radius: 20px;
        text-decoration: none;
        color: #333;
        font-weight: bold;
    }
    .bell-icon span {
        background-color: #e74c3c;
        color: white;
        padding: 2px 6px;
        border-radius: 50%;
        margin-left: 5px;
        font-size: 0.85em;
    }
    .hijos-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .card-hijo {
        background: #fff;
        border: 1px solid #cfd8dc;
        border-radius: 8px;
        width: 320px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .card-hijo-header {
        background: #f8f9fa;
        padding: 15px;
        border-bottom: 1px solid #cfd8dc;
        font-weight: bold;
        font-size: 1.1em;
        color: #2c3e50;
    }
    .card-hijo-body {
        padding: 15px;
        line-height: 1.8;
        color: #34495e;
    }
    .estado-aprobada { color: #27ae60; font-weight: bold; }
    .estado-pendiente { color: #f39c12; font-weight: bold; }
    .estado-rechazada { color: #e74c3c; font-weight: bold; }
    
    .docs-badge-ok {
        background-color: #27ae60; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.85em;
    }
    .docs-badge-warn {
        background-color: #e74c3c; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.85em; font-weight:bold;
    }
</style>

<div class="padre-header">
    <h2>Panel del Padre - {{ auth()->user()->name }}</h2>
    <a href="{{ route('notificaciones.index') }}" class="bell-icon">
        🔔 Notificaciones sin leer
        @if($notificaciones->count() > 0)
            <span>{{ $notificaciones->count() }}</span>
        @endif
    </a>
</div>

<h3 style="margin-bottom: 15px; color:#1a5276;">Mis hijos:</h3>

<div class="hijos-container">
    @forelse($hijos as $hijo)
        @php
            $inscripcion = $hijo->inscripcionActual;
            // Emoji de género
            $emoji = strtolower($hijo->genero ?? 'm') === 'f' ? '👧' : '👦';
        @endphp
        
        <div class="card-hijo">
            <div class="card-hijo-header">
                {{ $emoji }} {{ $hijo->nombre }} {{ $hijo->apellido }}
            </div>
            <div class="card-hijo-body">
                @if($inscripcion)
                    @php
                        $pendientes = $inscripcion->documentos->where('presentado', 0)->count();
                        $estado = strtolower($inscripcion->estado);
                        
                        $estadoClass = 'estado-aprobada';
                        $estadoEmoji = '✅';
                        $estadoTexto = 'Inscripción Aprobada';
                        
                        if ($estado === 'pendiente') {
                            $estadoClass = 'estado-pendiente';
                            $estadoEmoji = '⏳';
                            $estadoTexto = 'Inscripción Pendiente';
                        } elseif ($estado === 'rechazada') {
                            $estadoClass = 'estado-rechazada';
                            $estadoEmoji = '🔴';
                            $estadoTexto = 'Rechazada';
                        }
                    @endphp
                    <div><strong>Grado:</strong> {{ $inscripcion->grado->nombre ?? 'N/A' }}</div>
                    <div><strong>Estado:</strong> <span class="{{ $estadoClass }}">{{ $estadoEmoji }} {{ $estadoTexto }}</span></div>
                    <div><strong>Gestión:</strong> {{ $inscripcion->gestion }}</div>
                    <div style="margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 10px;">
                        <strong>Documentos pendientes:</strong> 
                        @if($pendientes > 0)
                            <span class="docs-badge-warn">{{ $pendientes }}</span>
                        @else
                            <span class="docs-badge-ok">Al día</span>
                        @endif
                    </div>
                    @if($inscripcion->observaciones)
                    <div style="margin-top: 12px; background-color: #fdf2e9; border-left: 4px solid #e74c3c; border-radius: 4px; padding: 10px;">
                        <strong style="color: #c0392b; font-size: 0.9em; display: flex; align-items: center; gap: 5px;">⚠️ Observación de Secretaría:</strong>
                        <p style="color: #a04000; font-size: 0.85em; margin: 5px 0 0 0; line-height: 1.4;">
                            {{ $inscripcion->observaciones }}
                        </p>
                    </div>
                    @endif
                @else
                    <div style="color: #7f8c8d; text-align: center; padding: 20px 0;">
                        📄 Sin inscripción actual registrada.
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div style="background: #eaf2f8; padding: 20px; border-radius: 5px; color: #1a5276; width: 100%;">
            No se encontraron hijos registrados. Por favor comuníquese con secretaría.
        </div>
    @endforelse
</div>
@endsection
