@extends('layouts.app')
@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600">assignment_turned_in</span>
            Inscripciones Activas
        </h1>
        <p class="text-sm text-gray-500 mt-1">Monitoreo y aprobaciones de matrículas para la Gestión {{ date('Y') }}.</p>
    </div>
    <a href="{{ route('inscripciones.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm">
        <span class="material-symbols-outlined text-[20px]">add_circle</span>
        Nueva Inscripción Manual
    </a>
</div>

<!-- Tabla de Inscripciones -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    <th class="px-6 py-4">Estudiante</th>
                    <th class="px-6 py-4">Grado Designado</th>
                    <th class="px-6 py-4">Fecha Inscripción</th>
                    <th class="px-6 py-4">Estado</th>
                    <th class="px-6 py-4 text-right">Acuerdos / Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($inscripciones as $ins)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $ins->estudiante->nombre }} {{ $ins->estudiante->apellido }}</div>
                            <div class="text-xs text-gray-500">Reg: #{{ str_pad($ins->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 font-medium text-gray-700">
                                <span class="material-symbols-outlined text-[16px] text-gray-400">school</span>
                                {{ $ins->grado->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $ins->fecha_inscripcion ? \Carbon\Carbon::parse($ins->fecha_inscripcion)->format('d / m / Y') : 'No finalizada' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($ins->estado === 'aprobada')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aprobada
                                </span>
                            @elseif($ins->estado === 'pendiente')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-medium border border-yellow-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Pendiente
                                </span>
                            @elseif($ins->estado === 'rechazada')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rechazada
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-50 text-gray-700 text-xs font-medium border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> {{ ucfirst($ins->estado) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($ins->estado === 'pendiente')
                                <a href="{{ route('inscripciones.edit', $ins) }}" class="inline-flex items-center justify-center gap-1 bg-amber-100 hover:bg-amber-200 text-amber-800 px-3 py-1.5 rounded-lg text-sm transition-colors font-medium">
                                    <span class="material-symbols-outlined text-[16px]">edit_document</span>
                                    Gestionar
                                </a>
                            @else
                                <form method="POST" action="{{ route('inscripciones.estado', $ins) }}" class="flex items-center justify-end gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="estado" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1.5">
                                        <option value="aprobada" {{ $ins->estado === 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                        <option value="rechazada" {{ $ins->estado === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                                        <option value="abandono" {{ $ins->estado === 'abandono' ? 'selected' : '' }} >Abandono</option>
                                        <option value="retirado" {{ $ins->estado === 'retirado' ? 'selected' : '' }}>Retirado</option>
                                        <option value="promovido" {{ $ins->estado === 'promovido' ? 'selected' : '' }}>Promovido</option>
                                    </select>
                                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-1.5 rounded-lg transition-colors border border-gray-200" title="Cambiar Estado">
                                        <span class="material-symbols-outlined text-[16px]">sync_alt</span>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center pt-4">
                                <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">inbox</span>
                                <p>No existen inscripciones registradas.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inscripciones->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $inscripciones->links('pagination::tailwind') }}
        </div>
    @endif
</div>

@endsection
