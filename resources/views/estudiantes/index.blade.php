@extends('layouts.app')
@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600">group</span>
            Directorio de Estudiantes
        </h1>
        <p class="text-sm text-gray-500 mt-1">Gestión de todos los estudiantes registrados en el sistema.</p>
    </div>
    <a href="{{ route('inscripciones.antiguo.create') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm">
        <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
        Inscribir Alumno Antiguo – {{ date('Y') }}
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">filter_list</span>
            Filtros de Búsqueda
        </h3>
    </div>
    <div class="p-5">
        <form method="GET" action="{{ route('estudiantes.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Buscar</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">search</span>
                    <input type="text" name="buscar" placeholder="Nombre, apellido o CI..." value="{{ request('buscar') }}" 
                           class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>

            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nivel</label>
                <select name="nivel_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los niveles</option>
                    @foreach($niveles as $nivel)
                        <option value="{{ $nivel->id }}" {{ request('nivel_id') == $nivel->id ? 'selected' : '' }}>{{ $nivel->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Grado</label>
                <select name="grado_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los grados</option>
                    @foreach($grados as $grado)
                        <option value="{{ $grado->id }}" {{ request('grado_id') == $grado->id ? 'selected' : '' }}>
                            {{ $grado->nivel->nombre }} - {{ $grado->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[120px]">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tipo</label>
                <select name="tipo" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Ambos</option>
                    <option value="nuevo" {{ request('tipo') == 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                    <option value="antiguo" {{ request('tipo') == 'antiguo' ? 'selected' : '' }}>Antiguo</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-1">
                    Aplicar
                </button>
                <a href="{{ route('estudiantes.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Estudiantes -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    <th class="px-6 py-4">Estudiante</th>
                    <th class="px-6 py-4">Tutor / Padre</th>
                    <th class="px-6 py-4">Grado Actual</th>
                    <th class="px-6 py-4">Tipo</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($estudiantes as $est)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                    {{ substr($est->nombre, 0, 1) }}{{ substr($est->apellido, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $est->nombre }} {{ $est->apellido }}</div>
                                    <div class="text-xs text-gray-500">CI: {{ $est->ci }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($est->padre)
                                <div class="flex items-center gap-2 text-gray-700">
                                    <span class="material-symbols-outlined text-[18px] text-gray-400">person</span>
                                    {{ $est->padre->nombre }} {{ $est->padre->apellido }}
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs">Sin asignar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($est->inscripcionActual)
                                <div class="font-medium text-gray-800">{{ $est->inscripcionActual->grado->nombre }}</div>
                                <div class="text-xs text-gray-500">{{ $est->inscripcionActual->grado->nivel->nombre ?? '' }}</div>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 text-gray-600 text-xs font-medium border border-gray-200">
                                    Sin inscripción
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($est->tipo === 'nuevo')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium border border-emerald-200">
                                    Nuevo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-medium border border-indigo-200">
                                    Antiguo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('estudiantes.show', $est) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Ver Perfil">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                <a href="{{ route('estudiantes.edit', $est) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Editar">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                <form method="POST" action="{{ route('estudiantes.destroy', $est) }}" class="inline" onsubmit="return confirm('ATENCIÓN: ¿Desea eliminar este estudiante permanentemente? (Se borrarán sus inscripciones asociadas)')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">sentiment_dissatisfied</span>
                                <p>No se encontraron estudiantes registrados con esos filtros.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($estudiantes->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $estudiantes->links('pagination::tailwind') }}
        </div>
    @endif
</div>

@endsection
