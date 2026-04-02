@extends('layouts.app')

@section('titulo', 'Detalle del Estudiante - Sistema Tiquipaya')

@section('content')
@php
    // Mapa de colores Tailwind para los estados
    $estadoClases = [
        'pendiente'  => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'aprobada'   => 'bg-[#e7f3e8] text-[#0d631b] border-[#0d631b]/30',
        'rechazada'  => 'bg-red-100 text-red-800 border-red-200',
        'abandono'   => 'bg-gray-800 text-gray-100 border-gray-900',
        'retirado'   => 'bg-gray-200 text-gray-700 border-gray-300',
        'promovido'  => 'bg-blue-100 text-blue-800 border-blue-200',
        'convertida' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
    ];
@endphp

<div class="max-w-7xl mx-auto font-sans">
    
    <!-- Top Header & Actions -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-16 h-16 bg-[#0d631b]/10 rounded-full flex items-center justify-center text-[#0d631b]">
                    <span class="material-symbols-outlined text-[32px]">face</span>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#191c1f] font-['Public_Sans']">
                        {{ $estudiante->nombre }} {{ $estudiante->apellido }}
                    </h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $estudiante->tipo === 'nuevo' ? 'bg-[#94f990]/30 text-[#006e1c]' : 'bg-gray-100 text-gray-600' }}">
                            {{ $estudiante->tipo === 'nuevo' ? 'Alumno Nuevo' : 'Alumno Antiguo' }}
                        </span>
                        <span class="text-[#707a6c] text-sm font-medium">| Ficha Académica</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('estudiantes.edit', $estudiante) }}" class="px-5 py-2.5 bg-[#f2f3f7] hover:bg-[#e1e2e6] text-[#191c1f] font-bold rounded-xl transition-colors flex items-center gap-2 shadow-sm border border-gray-200">
                <span class="material-symbols-outlined text-[20px]">edit</span> Editar Perfil
            </a>
            <a href="{{ route('estudiantes.index') }}" class="px-5 py-2.5 bg-white border-2 border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span> Volver
            </a>
        </div>
    </div>

    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- MAIN COLUMN (Left: 2 cols) -->
        <div class="xl:col-span-2 space-y-8">
            
            <!-- Student Data Card -->
            <section class="bg-white rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
                <h2 class="text-xl font-bold text-[#0d631b] flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                    <span class="material-symbols-outlined">badge</span>
                    Identidad Personal
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nombre Completo</label>
                        <div class="text-[#191c1f] font-semibold text-lg">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Documento (Carnet)</label>
                        <div class="text-[#191c1f] font-semibold text-lg flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">id_card</span>
                            {{ $estudiante->ci ?? 'No registrado' }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nacimiento y Edad</label>
                        <div class="text-[#191c1f] font-semibold text-lg">
                            @if($estudiante->fecha_nacimiento)
                                {{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d / m / Y') }}
                                <span class="text-[#0d631b] bg-[#e7f3e8] px-2 py-0.5 rounded text-sm ml-2 font-bold">{{ $estudiante->edad }} años</span>
                            @else
                                <span class="text-gray-400 italic">No registrado</span>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Género</label>
                        <div class="text-[#191c1f] font-semibold text-lg flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">{{ $estudiante->genero === 'M' ? 'male' : 'female' }}</span>
                            {{ $estudiante->genero === 'M' ? 'Masculino' : 'Femenino' }}
                        </div>
                    </div>
                </div>
            </section>

            <!-- Parent / Tutor Data Card -->
            <section class="bg-white rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-xl font-bold text-[#0d631b] flex items-center gap-2">
                        <span class="material-symbols-outlined">family_restroom</span>
                        Tutor Responsable
                    </h2>
                    @if($estudiante->padre)
                        <a href="{{ route('padres.show', $estudiante->padre) }}" class="text-sm font-bold text-[#0d631b] hover:text-[#006e1c] flex items-center gap-1 transition-colors">
                            Ir a la ficha del tutor <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    @endif
                </div>

                @if($estudiante->padre)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nombre del Tutor</label>
                            <div class="text-[#191c1f] font-semibold text-lg">{{ $estudiante->padre->nombre }} {{ $estudiante->padre->apellido }}</div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Carnet de Identidad</label>
                            <div class="text-[#191c1f] font-semibold text-lg">{{ $estudiante->padre->ci ?? 'No registrado' }}</div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Teléfono Celular</label>
                            <div class="text-[#191c1f] font-semibold text-lg flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-400 text-[20px]">phone_iphone</span>
                                {{ $estudiante->padre->telefono ?? '—' }}
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dirección Familiar</label>
                            <div class="text-[#191c1f] font-semibold text-lg flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-400 text-[20px]">home</span>
                                {{ $estudiante->padre->direccion ?? '—' }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-[#f8f9fd] border border-gray-200 border-dashed rounded-xl p-8 flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-gray-300 text-[48px] mb-2">person_off</span>
                        <p class="text-gray-500 font-medium">Este estudiante aún no tiene un perfil de tutor formalmente vinculado.</p>
                    </div>
                @endif
            </section>

            <!-- Siblings Card -->
            @if($estudiante->padre)
                @php
                    $hermanos = $estudiante->padre->estudiantes->where('id', '!=', $estudiante->id);
                @endphp
                @if($hermanos->count())
                <section class="bg-white rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
                    <h2 class="text-xl font-bold text-[#0d631b] flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                        <span class="material-symbols-outlined">group</span>
                        Hermanos Registrados (Familia)
                    </h2>
                    
                    <div class="space-y-3">
                        @foreach($hermanos as $hermano)
                        <div class="flex items-center justify-between p-4 bg-[#f8f9fd] rounded-xl border border-transparent hover:border-[#0d631b]/20 hover:bg-[#e7f3e8]/30 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center text-[#0d631b] font-bold">
                                    {{ substr($hermano->nombre, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-[#191c1f]">{{ $hermano->nombre }} {{ $hermano->apellido }}</div>
                                    <div class="text-xs font-semibold text-gray-500 mt-0.5">
                                        @if($hermano->inscripcionActual)
                                            Inscrito en: {{ $hermano->inscripcionActual->grado->nombre ?? 'N/A' }}
                                        @else
                                            <span class="text-red-500">Sin inscripción actual</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('estudiantes.show', $hermano) }}" class="px-4 py-2 bg-white text-[#0d631b] font-bold text-sm rounded-lg border border-[#bfcaba] hover:bg-[#e7f3e8] hover:border-[#0d631b] transition-all">
                                Ver Ficha
                            </a>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            @endif

        </div>

        <!-- SIDE COLUMN (Right: 1 col) -->
        <div class="space-y-8">
            
            <!-- Active Inscription Highlight Panel -->
            <section class="rounded-2xl p-8 shadow-xl text-white relative overflow-hidden {{ $estudiante->inscripcionActual ? 'bg-gradient-to-br from-[#0d631b] to-[#006e1c]' : 'bg-gradient-to-br from-gray-700 to-gray-900' }}">
                <div class="absolute -right-10 -top-10 opacity-10">
                    <span class="material-symbols-outlined text-[150px]">school</span>
                </div>
                
                <h2 class="text-2xl font-bold flex items-center gap-2 mb-6 relative z-10">
                    <span class="material-symbols-outlined">contract_edit</span>
                    Gestión Académica {{ date('Y') }}
                </h2>

                @if($estudiante->inscripcionActual)
                    @php 
                        $ins = $estudiante->inscripcionActual; 
                        $badgeClasses = $estadoClases[$ins->estado] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    
                    <div class="relative z-10 space-y-6">
                        <!-- Nivel y Grado -->
                        <div class="bg-white/10 rounded-xl p-5 backdrop-blur-sm border border-white/20">
                            <p class="text-white/70 text-xs font-bold uppercase tracking-wider mb-1">Cuso Asignado</p>
                            <p class="text-2xl font-black">{{ $ins->grado->nombre ?? '—' }}</p>
                            @if($ins->grado->nivel)
                                <p class="text-white/90 text-sm font-medium">{{ $ins->grado->nivel->nombre }}</p>
                            @endif
                        </div>

                        <!-- Estado y Formulario -->
                        <div>
                            <p class="text-white/70 text-xs font-bold uppercase tracking-wider mb-2">Estado del Trámite</p>
                            <form method="POST" action="{{ route('inscripciones.estado', $ins) }}" class="relative">
                                @csrf @method('PATCH')
                                <select name="estado" class="w-full appearance-none bg-white font-bold text-lg rounded-xl px-4 py-3 border-2 focus:ring-0 cursor-pointer shadow-lg transition-colors {{ str_replace('bg-', 'text-', explode(' ', $badgeClasses)[1]) }} border-transparent focus:border-[#0d631b]" onchange="this.form.submit()">
                                    @foreach(['pendiente','aprobada','rechazada','abandono','retirado','promovido'] as $e)
                                        <option value="{{ $e }}" {{ $ins->estado === $e ? 'selected' : '' }} class="text-gray-900">{{ ucfirst($e) }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </form>
                        </div>

                        <!-- Detalles rápidos -->
                        <div class="grid grid-cols-2 gap-4 border-t border-white/20 pt-4 mt-2">
                            <div>
                                <p class="text-white/70 text-[10px] font-bold uppercase tracking-wider">Fecha Alta</p>
                                <p class="font-bold">{{ $ins->fecha_inscripcion ? \Carbon\Carbon::parse($ins->fecha_inscripcion)->format('d M, Y') : 'N/D' }}</p>
                            </div>
                            <div>
                                <p class="text-white/70 text-[10px] font-bold uppercase tracking-wider">Documentos</p>
                                <p class="font-bold">{{ $ins->documentos->where('presentado', true)->count() }} / {{ $ins->documentos->count() > 0 ? $ins->documentos->count() : '5' }} entregados</p>
                            </div>
                        </div>

                        <a href="{{ route('inscripciones.edit', $ins) }}" class="w-full block text-center py-3 px-4 bg-white/20 hover:bg-white/30 text-white font-bold rounded-xl transition-colors text-sm border border-white/30 mt-4 backdrop-blur-sm">
                            Ficha de Inscripción Completa
                        </a>
                    </div>
                @else
                    <div class="relative z-10 text-center py-6">
                        <span class="material-symbols-outlined text-[48px] text-white/50 mb-4">folder_off</span>
                        <p class="text-white/90 font-medium mb-6">El estudiante no cuenta con un registro para la presente gestión escolar.</p>
                        
                        <a href="{{ route('inscripciones.create') }}?estudiante_id={{ $estudiante->id }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-colors shadow-lg">
                            <span class="material-symbols-outlined text-[20px]">add_circle</span> Crear Inscripción
                        </a>
                    </div>
                @endif
            </section>

            <!-- Inscription History -->
            @if($estudiante->inscripciones && $estudiante->inscripciones->count() > ($estudiante->inscripcionActual ? 1 : 0))
            <section class="bg-white rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-[#191c1f] flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#0d631b]">history</span>
                        Historial Pasado
                    </h2>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @foreach($estudiante->inscripciones->sortByDesc('gestion') as $hist)
                        @if(!$estudiante->inscripcionActual || $hist->id !== $estudiante->inscripcionActual->id)
                            @php 
                                $c = $estadoClases[$hist->estado] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <div class="p-5 hover:bg-[#f8f9fd] transition-colors flex justify-between items-center group">
                                <div>
                                    <div class="font-bold text-[#191c1f] text-lg">{{ $hist->gestion }}</div>
                                    <div class="text-sm font-medium text-gray-500">{{ $hist->grado->nombre ?? 'Sin Grado' }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider border {{ $c }}">
                                        {{ $hist->estado }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
            @endif

        </div>
    </div>
</div>
@endsection
