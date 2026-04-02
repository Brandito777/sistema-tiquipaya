@extends('layouts.app')
@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600">family_restroom</span>
            Gestión de Padres de Familia
        </h1>
        <p class="text-sm text-gray-500 mt-1">Supervisión de tutores, credenciales de acceso y familias activas.</p>
    </div>
    <a href="{{ route('padres.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm">
        <span class="material-symbols-outlined text-[20px]">person_add</span>
        Nuevo Padre / Tutor
    </a>
</div>

@if(session('success'))
    <div class="mb-6 flex items-center gap-3 bg-green-50 text-green-800 p-4 rounded-xl border border-green-200">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 flex flex-col gap-1 bg-red-50 text-red-800 p-4 rounded-xl border border-red-200">
        @foreach($errors->all() as $error)
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-red-600">error</span>
                <p class="text-sm font-medium">{{ $error }}</p>
            </div>
        @endforeach
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    <th class="px-6 py-4">Tutor</th>
                    <th class="px-6 py-4">Contacto</th>
                    <th class="px-6 py-4">Hijos en el Sistema</th>
                    <th class="px-6 py-4">Estado Login</th>
                    <th class="px-6 py-4 text-right">Administración</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($padres as $padre)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center font-bold text-sm">
                                    {{ substr($padre->nombre, 0, 1) }}{{ substr($padre->apellido, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $padre->nombre }} {{ $padre->apellido }}</div>
                                    <div class="text-xs {{ str_starts_with($padre->ci, 'PENDIENTE') ? 'text-amber-500 italic' : 'text-gray-500' }}">
                                        CI: {{ str_starts_with($padre->ci, 'PENDIENTE') ? 'Falta completar' : $padre->ci }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5 text-gray-700">
                                    <span class="material-symbols-outlined text-[16px] text-gray-400">call</span>
                                    {{ $padre->telefono }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-gray-400">mail</span>
                                    @if($padre->user)
                                        <span class="text-gray-600">{{ $padre->user->email }}</span>
                                    @else
                                        <span class="text-red-500 text-xs font-medium">Sin cuenta Web</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $confirmados = $padre->estudiantes->filter(function($e) {
                                    return $e->inscripcionActual && in_array($e->inscripcionActual->estado, ['aprobada', 'promovido']);
                                })->count();
                            @endphp
                            
                            @if($confirmados > 0)
                                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold border border-blue-200">
                                    <span class="material-symbols-outlined text-[14px]">child_care</span>
                                    {{ $confirmados }} matriculado(s)
                                </span>
                            @else
                                <span class="text-gray-400 italic text-xs">Ninguno activo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($padre->user)
                                @if($padre->user->active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Acceso Habilitado
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Cuenta Bloqueada
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium border border-slate-200">
                                    Pendiente Integración
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('padres.show', $padre) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-100" title="Ver Expediente">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                <a href="{{ route('padres.edit', $padre) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors border border-transparent hover:border-amber-100" title="Editar Información">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>

                                <div class="w-px h-6 bg-gray-200 mx-1"></div>

                                @if($padre->user)
                                    <form action="{{ route('padres.toggle', $padre) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-1.5 {{ $padre->user->active ? 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' : 'text-green-600 hover:bg-green-50 border-green-100' }} rounded-lg transition-colors border border-transparent" title="{{ $padre->user->active ? 'Desactivar Login' : 'Restaurar Login' }}">
                                            <span class="material-symbols-outlined text-[20px]">{{ $padre->user->active ? 'lock' : 'lock_open' }}</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('padres.destroy', $padre) }}" method="POST" class="inline" onsubmit="return confirm('ATENCIÓN: ¿Desea eliminar este Padre, su cuenta Web, y todos sus hijos permanentemente del sistema?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-100" title="Destrucción Recursiva (Borrar Familia)">
                                            <span class="material-symbols-outlined text-[20px]">delete_forever</span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('padres.create_user', $padre) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors border border-transparent hover:border-indigo-100" title="Generar Login Web">
                                        <span class="material-symbols-outlined text-[20px]">key</span>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">badge</span>
                                <p>No existen perfiles de tutores registrados en la plataforma.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($padres->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $padres->links('pagination::tailwind') }}
        </div>
    @endif
</div>

@endsection
