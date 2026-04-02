@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 text-3xl">contact_page</span>
                Expediente del Tutor
            </h1>
            <p class="text-sm text-gray-500 mt-1">Vista detallada de información y estudiantes bajo su tutela.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('padres.edit', $padre) }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Modificar Datos
            </a>
            <a href="{{ route('padres.index') }}" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Atrás
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-green-50 text-green-800 p-4 rounded-xl border border-green-200">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Tarjeta de Perfil Padre -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 flex flex-col items-center border-b border-gray-100 bg-gradient-to-b from-blue-50/50 to-white">
                    <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-600 border-[3px] border-white shadow-sm flex items-center justify-center font-bold text-3xl mb-4">
                        {{ substr($padre->nombre, 0, 1) }}{{ substr($padre->apellido, 0, 1) }}
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 text-center">{{ $padre->nombre }} {{ $padre->apellido }}</h2>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">id_card</span>
                        CI: {{ $padre->ci }}
                    </p>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-gray-400 mt-0.5">mail</span>
                        <div>
                            <p class="text-xs text-gray-500 font-medium tracking-wider uppercase mb-0.5">Correo Web</p>
                            <p class="text-sm text-gray-800">{{ $padre->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-gray-400 mt-0.5">call</span>
                        <div>
                            <p class="text-xs text-gray-500 font-medium tracking-wider uppercase mb-0.5">Teléfono</p>
                            <p class="text-sm text-gray-800">{{ $padre->telefono }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-gray-400 mt-0.5">home_pin</span>
                        <div>
                            <p class="text-xs text-gray-500 font-medium tracking-wider uppercase mb-0.5">Dirección Físca</p>
                            <p class="text-sm text-gray-800">{{ $padre->direccion ?? 'No especificada en el sistema' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-medium tracking-wider uppercase mb-1">Estado de la Cuenta</p>
                        @if($padre->user->active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold border border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Desactivado
                            </span>
                        @endif
                    </div>
                    
                    <form action="{{ route('padres.toggle', $padre) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="p-2 rounded-lg transition-colors flex items-center justify-center {{ $padre->user->active ? 'text-amber-600 bg-amber-50 hover:bg-amber-100' : 'text-green-600 bg-green-50 hover:bg-green-100' }}" title="{{ $padre->user->active ? 'Bloquear acceso web' : 'Restaurar acceso web' }}">
                            <span class="material-symbols-outlined text-[20px]">{{ $padre->user->active ? 'lock' : 'lock_open' }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Estudiantes Asociados -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-500 text-[20px]">family_restroom</span>
                        <h3 class="font-semibold text-gray-800">Carga Familiar Registrada</h3>
                    </div>
                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ $padre->estudiantes->count() }} Hijos</span>
                </div>
                
                @if($padre->estudiantes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="px-5 py-3">Estudiante</th>
                                    <th class="px-5 py-3">Gestión Actual ({{ date('Y') }})</th>
                                    <th class="px-5 py-3 text-right">Ficha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach($padre->estudiantes as $estudiante)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-gray-800">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">CI: {{ $estudiante->ci }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            @if($estudiante->inscripcionActual)
                                                <div class="font-medium text-gray-700 flex items-center gap-1.5 mb-1">
                                                    <span class="material-symbols-outlined text-[16px] text-gray-400">school</span>
                                                    {{ $estudiante->inscripcionActual->grado->nombre }}
                                                </div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold tracking-wide {{ $estudiante->inscripcionActual->estado === 'aprobada' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                                    {{ strtoupper($estudiante->inscripcionActual->estado) }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded border border-yellow-200 text-xs font-medium">
                                                    <span class="material-symbols-outlined text-[14px]">warning</span> Sin inscribir
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('estudiantes.show', $estudiante) }}" class="inline-flex items-center justify-center p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-100" title="Ver Perfil Estudiante">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-3xl text-gray-300">child_care</span>
                        </div>
                        <h4 class="text-gray-800 font-medium mb-1">Sin carga familiar</h4>
                        <p class="text-sm text-gray-500 max-w-sm">Este tutor legal no tiene estudiantes bajo su nombre en el sistema.</p>
                    </div>
                @endif
            </div>
            
            <div class="bg-blue-50 rounded-xl p-4 flex gap-3 border border-blue-100 items-start">
                <span class="material-symbols-outlined text-blue-500 mt-0.5">info</span>
                <div>
                    <h4 class="text-sm font-semibold text-blue-900">Sobre la Gestión de Tutores</h4>
                    <p class="text-sm text-blue-700 mt-1">Los padres asumen el control de las inscripciones y los peritajes de sus hijos a través de su propia <strong>Bandeja Web (Mi Panel)</strong>. Si un padre pierde su acceso, puede usar el botón circular de candado en la columna izquierda para inhabilitar temporalmente la entrada a la plataforma.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
