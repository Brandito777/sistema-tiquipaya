@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header de Reservas -->
    <header class="mb-10 flex items-center gap-3">
        <span class="material-symbols-outlined text-3xl text-primary" style="color: #2e7d32">assignment</span>
        <div>
            <h1 class="text-3xl font-extrabold text-on-surface tracking-tight font-headline">Registro de Reservas</h1>
            <p class="text-on-surface-variant text-base">Gestión y validación de nuevas solicitudes académicas para familias.</p>
        </div>
    </header>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-green-50 text-green-800 p-4 rounded-xl border border-green-200 shadow-sm">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-12">
        @forelse($reservas as $reserva)
            <section class="space-y-4">
                <!-- Tarjeta Principal: Padre/Tutor -->
                <div class="px-6 py-3 rounded-t-xl flex flex-wrap items-center justify-between gap-4" style="background-color: #2e7d32; color: white;">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-3xl opacity-90">person</span>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm font-medium">
                            <span class="font-bold text-lg tracking-wide">{{ $reserva->nombre_padre }} {{ $reserva->apellido_padre }}</span>
                            <span class="opacity-80">Edad: {{ $reserva->edad_padre ?? '-' }} años</span>
                            <span class="opacity-80 hidden sm:inline">|</span>
                            <span class="opacity-80">Tel: {{ $reserva->telefono_padre }}</span>
                            @if($reserva->email_padre)
                                <span class="opacity-80 hidden sm:inline">|</span>
                                <span class="opacity-80">Email: {{ $reserva->email_padre }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-sm font-bold bg-white/20 px-3 py-1 rounded-full shadow-inner">
                        Hijos: {{ $reserva->cantidad_hijos }} | Gestión: {{ $reserva->gestion }}
                    </div>
                </div>

                <!-- Detalles y Estudiantes de la Reserva -->
                <div class="bg-white rounded-b-xl shadow-sm border overflow-hidden" style="border-color: #e1e2e6;">
                    <div class="p-4 border-b flex items-center justify-between bg-slate-50 border-slate-200">
                        <h3 class="text-sm font-bold flex items-center gap-2" style="color: #2e7d32;">
                            <span class="material-symbols-outlined text-lg">family_restroom</span>
                            Infantes vinculados a la reserva:
                        </h3>
                    </div>

                    <!-- Tabla de Hijos -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs uppercase tracking-wider font-bold" style="background-color: #e8f5e9; color: #2e7d32;">
                                    <th class="px-6 py-3 border-r border-green-700/10 w-16 text-center">Nº</th>
                                    <th class="px-6 py-3 border-r border-green-700/10">Estudiante Registrado</th>
                                    <th class="px-6 py-3 border-r border-green-700/10">F. Nacimiento</th>
                                    <th class="px-6 py-3 border-r border-green-700/10 text-center">Edad</th>
                                    <th class="px-6 py-3">Grado Pre-asignado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($reserva->hijos as $index => $hijo)
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-4 font-bold text-gray-400 text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-bold text-gray-800">{{ $hijo->nombre }} {{ $hijo->apellido }}</td>
                                        <td class="px-6 py-4 text-gray-600 font-medium">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[16px] text-gray-400">cake</span>
                                                {{ \Carbon\Carbon::parse($hijo->fecha_nacimiento)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded font-bold text-xs border border-gray-200 shadow-sm">
                                                {{ $hijo->edad ?? \Carbon\Carbon::parse($hijo->fecha_nacimiento)->age }} años
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-semibold text-gray-500">{{ $hijo->grado->nivel->nombre ?? 'Nivel' }}</span>
                                                <span class="text-sm font-bold text-[#2e7d32]">{{ $hijo->grado->nombre ?? 'Grado Desconocido' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Base (Estados y Controles) -->
                    <div class="p-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-200 bg-white">
                        <div class="text-sm font-medium flex items-center gap-2">
                            <span class="text-gray-500 uppercase tracking-wide text-xs">Aprobación:</span>
                            @if($reserva->estado === 'pendiente')
                                <span class="text-amber-600 font-bold bg-amber-50 px-3 py-1 rounded-full border border-amber-200 shadow-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">hourglass_empty</span> Pendiente
                                </span>
                            @elseif($reserva->estado === 'confirmada')
                                <span class="text-green-700 font-bold bg-green-50 px-3 py-1 rounded-full border border-green-200 shadow-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">task_alt</span> Confirmada
                                </span>
                            @else
                                <span class="text-red-700 font-bold bg-red-50 px-3 py-1 rounded-full border border-red-200 shadow-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">cancel</span> Rechazada
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-3">
                            @if($reserva->estado === 'pendiente')
                                <span class="text-xs text-gray-400 hidden md:flex items-center gap-1 mr-3">
                                    <span class="material-symbols-outlined text-[16px]">info</span> Esperando validación centralizada
                                </span>
                            @else
                                <span class="text-xs text-gray-400 hidden md:flex items-center gap-1 mr-3 italic">
                                    Reserva gestionada en el sistema
                                </span>
                            @endif
                            
                            <a href="{{ route('reservas.show', $reserva) }}" class="px-5 py-2 rounded-lg font-bold text-sm bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 flex items-center gap-2 transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                                Desglosar Detalle
                            </a>

                            @if($reserva->estado === 'pendiente')
                                <form action="{{ route('reservas.estado', $reserva) }}" method="POST" class="inline-flex items-center gap-2 m-0 bg-gray-50 p-1.5 rounded-lg border border-gray-200 shadow-sm">
                                    @csrf
                                    @method('PATCH')
                                    <select name="estado" class="!my-0 !py-1 !pl-3 !pr-8 text-sm font-semibold rounded-md border-gray-300 text-gray-700 focus:ring-green-500 focus:border-green-500 shadow-sm h-full" style="height: 38px;">
                                        <option value="confirmada">✅ Otorgar Cupo</option>
                                        <option value="cancelada">❌ Rechazar Petición</option>
                                    </select>
                                    <button type="submit" class="px-4 py-1.5 rounded-md font-bold text-sm text-white transition-colors shadow-sm" style="background-color: #2e7d32; height: 38px;" title="Aplicar Acción">
                                        Ejecutar
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" onsubmit="return confirm('¿Estás seguro que deseas eliminar permanentemente esta reserva del sistema?');" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg font-bold text-[11px] uppercase tracking-wider text-red-600 bg-red-50 border border-red-200 hover:bg-red-600 hover:text-white flex items-center gap-1 transition-colors shadow-sm">
                                        <span class="material-symbols-outlined text-[15px]">delete</span>
                                        Eliminar Reserva
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <div class="flex flex-col items-center justify-center p-16 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-5xl text-gray-300">assignment_turned_in</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Cero reservas colgando</h3>
                <p class="mt-2 text-gray-500 font-medium text-center">Todas las reservas en línea han sido revisadas o no hay solicitudes nuevas por el momento.</p>
            </div>
        @endforelse

        <!-- Paginación Laravel -->
        <div class="mt-10 flex justify-center w-full pb-8">
            {{ $reservas->links() }}
        </div>
    </div>
</div>
@endsection
