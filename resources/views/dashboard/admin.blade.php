@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('content')

<!-- KPI Grid -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12 mt-6">
    <!-- Estudiantes Registrados -->
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.03)] border-l-4 border-primary transition-all hover:shadow-lg hover:-translate-y-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-on-surface-variant text-sm font-semibold mb-1 uppercase tracking-wider">Estudiantes Registrados</p>
                <h3 class="text-4xl font-black text-primary">{{ $totalEstudiantes }}</h3>
            </div>
            <div class="w-12 h-12 bg-primary-fixed rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-2xl" data-icon="school">school</span>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-primary font-bold">
            <span class="material-symbols-outlined text-sm mr-1" data-icon="trending_up">trending_up</span>
            <span>Estudiantes activos en el sistema</span>
        </div>
    </div>

    <!-- Inscripciones Hoy -->
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.03)] border-l-4 border-secondary transition-all hover:shadow-lg hover:-translate-y-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-on-surface-variant text-sm font-semibold mb-1 uppercase tracking-wider">Inscripciones Hoy</p>
                <h3 class="text-4xl font-black text-secondary">{{ $inscripcionesHoy }}</h3>
            </div>
            <div class="w-12 h-12 bg-secondary-container rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-secondary text-2xl" data-icon="description">description</span>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-secondary font-bold">
            <span class="material-symbols-outlined text-sm mr-1" data-icon="bolt">bolt</span>
            <span>Actividad del día</span>
        </div>
    </div>

    <!-- Reservas Pendientes -->
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.03)] border-l-4 border-orange-500 transition-all hover:shadow-lg hover:-translate-y-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-on-surface-variant text-sm font-semibold mb-1 uppercase tracking-wider">Reservas Pendientes</p>
                <h3 class="text-4xl font-black text-orange-600">{{ $reservasPendientes }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-orange-600 text-2xl" data-icon="schedule">schedule</span>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-orange-700 font-bold">
            <span class="material-symbols-outlined text-sm mr-1" data-icon="warning">warning</span>
            <span>Requiere atención de secretaría</span>
        </div>
    </div>
</div>

<!-- Main Layout Bento-style -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Large Task Card -->
    <div class="lg:col-span-2 space-y-8">
        <section class="bg-surface-container-lowest p-8 rounded-[1.5rem] shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <h4 class="text-xl font-bold text-on-surface">Acciones Rápidas</h4>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('inscripciones.index') }}" class="flex flex-col items-center justify-center p-8 bg-surface-container-low hover:bg-primary-container hover:text-white rounded-[1.5rem] transition-all duration-300 group min-h-[160px] relative overflow-hidden">
                    <div class="absolute inset-0 bg-primary opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="material-symbols-outlined text-5xl mb-4 text-primary group-hover:text-white group-hover:scale-110 transition-transform">person_add</span>
                    <span class="text-lg font-bold group-hover:text-white">Nueva Inscripción</span>
                </a>
                
                <a href="{{ route('notificaciones.crear') }}" class="flex flex-col items-center justify-center p-8 bg-surface-container-low hover:bg-tertiary-container hover:text-white rounded-[1.5rem] transition-all duration-300 group min-h-[160px] relative overflow-hidden">
                    <div class="absolute inset-0 bg-tertiary opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="material-symbols-outlined text-5xl mb-4 text-tertiary group-hover:text-white group-hover:scale-110 transition-transform">mail</span>
                    <span class="text-lg font-bold group-hover:text-white">Enviar Comunicado</span>
                </a>
                
                <a href="{{ route('estudiantes.index') }}" class="flex flex-col items-center justify-center p-8 bg-surface-container-low hover:bg-secondary-container hover:text-on-secondary-container rounded-[1.5rem] transition-all duration-300 group min-h-[160px] relative overflow-hidden">
                    <div class="absolute inset-0 bg-secondary opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="material-symbols-outlined text-5xl mb-4 text-secondary group-hover:text-on-secondary-container group-hover:scale-110 transition-transform">groups</span>
                    <span class="text-lg font-bold">Estudiantes Registrados</span>
                </a>
                
                <a href="{{ route('reservas.index') }}" class="flex flex-col items-center justify-center p-8 bg-surface-container-low hover:bg-orange-100 hover:text-orange-800 rounded-[1.5rem] transition-all duration-300 group min-h-[160px] relative overflow-hidden">
                    <div class="absolute inset-0 bg-orange-500 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="material-symbols-outlined text-5xl mb-4 text-orange-600 group-hover:scale-110 transition-transform">schedule</span>
                    <span class="text-lg font-bold">Ver Reservas</span>
                </a>
            </div>
        </section>

        <!-- Recent Activity Section -->
        <section class="bg-surface-container-lowest p-8 rounded-[1.5rem] shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-xl font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">history</span>
                    Inscripciones Recientes
                </h4>
            </div>
            
            <div class="space-y-4">
                @forelse($recientes as $inscripcion)
                    @php
                        $estadoAprobado = $inscripcion->estado === 'aprobada' || $inscripcion->estado === 'promovido';
                        $cardBg = $estadoAprobado ? 'bg-secondary-fixed/30 hover:bg-secondary-fixed/50 border-l-4 border-secondary' : 'bg-orange-50 hover:bg-orange-100 border-l-4 border-orange-400';
                        $estadoClass = $estadoAprobado ? 'bg-secondary text-white' : 'bg-orange-500 text-white';
                        $avatarIcon = strtolower($inscripcion->estudiante->genero) === 'f' ? 'face_3' : 'face_6';
                        $avatarBg = $estadoAprobado ? 'bg-secondary-container text-on-secondary-container' : 'bg-orange-200 text-orange-800';
                    @endphp
                    <div class="flex items-center gap-4 p-5 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md cursor-pointer {{ $cardBg }}">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center shrink-0 shadow-sm {{ $avatarBg }}">
                            <span class="material-symbols-outlined text-2xl">{{ $avatarIcon }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-on-surface text-lg">{{ $inscripcion->estudiante->nombre }} {{ $inscripcion->estudiante->apellido }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant">school</span>
                                <p class="text-sm text-on-surface-variant font-medium">{{ $inscripcion->grado->nombre ?? 'Sin Grado' }} • Gestión {{ $inscripcion->gestion }}</p>
                            </div>
                        </div>
                        <div class="text-right flex flex-col items-end gap-2">
                            <span class="inline-flex items-center gap-1 px-3 py-1 {{ $estadoClass }} text-[11px] font-black rounded-full uppercase tracking-wider shadow-sm">
                                <span class="material-symbols-outlined text-[12px]">{{ $estadoAprobado ? 'check_circle' : 'pending' }}</span>
                                {{ ucfirst($inscripcion->estado) }}
                            </span>
                            <p class="text-xs font-bold text-on-surface-variant">{{ $inscripcion->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 bg-surface-container-low rounded-xl border border-dashed border-outline-variant">
                        <span class="material-symbols-outlined text-6xl text-outline mb-3">inbox</span>
                        <p class="text-lg font-bold text-on-surface-variant">No hay inscripciones recientes</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <!-- Sidebar Content (Small Cards) -->
    <div class="space-y-8">
        <!-- Tertiary Color Alert Card -->
        @if($reservasPendientes > 0)
        <div class="bg-surface-container-lowest p-6 rounded-[1.5rem] shadow-sm border-l-[6px] border-orange-500 overflow-hidden relative">
            <div class="absolute -right-6 -top-6 text-orange-100 opacity-50">
                <span class="material-symbols-outlined text-[120px]">warning</span>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-orange-600 text-3xl">notification_important</span>
                    <h5 class="font-bold text-orange-700 text-lg">Alerta de Reservas</h5>
                </div>
                <p class="text-sm text-on-surface mb-6 font-medium leading-relaxed">
                    Tiene <strong class="text-orange-600 text-lg">{{ $reservasPendientes }}</strong> reservas de cupo en estado pendiente que requieren revisión y/o validación.
                </p>
                <a href="{{ route('reservas.index') }}" class="block text-center w-full py-3 bg-orange-500 text-white font-bold rounded-xl text-sm transition-all hover:bg-orange-600 shadow-md hover:shadow-lg active:scale-95">
                    Gestionar Reservas
                </a>
            </div>
        </div>
        @endif

        <!-- Quick Contact Card -->
        <div class="bg-primary text-white p-6 rounded-[1.5rem] shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 bg-white/10 w-40 h-40 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <h5 class="font-bold mb-6 text-lg tracking-wide">Soporte y Ayuda</h5>
                <div class="flex items-center gap-4 bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-primary shadow-inner">
                        <span class="material-symbols-outlined text-2xl">support_agent</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold opacity-90">Dirección General</p>
                        <p class="text-lg font-black tracking-wider">+591 60795184</p>
                    </div>
                </div>
                <a href="https://wa.link/0yqg3g" target="_blank" class="w-full mt-4 flex items-center justify-center gap-2 py-3 bg-white text-primary font-black rounded-xl text-sm transition-all hover:bg-surface active:scale-95 shadow-lg">
                    <span class="material-symbols-outlined text-lg">forum</span> Contactar a Soporte
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
