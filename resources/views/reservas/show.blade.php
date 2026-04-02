@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('reservas.index') }}" class="flex items-center justify-center text-primary bg-green-50 hover:bg-green-100 p-2 rounded-lg transition-colors border border-green-200 shadow-sm" title="Volver al Listado">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div class="flex flex-col">
                <h1 class="text-3xl font-extrabold tracking-tight text-on-surface">Expediente de Solicitud</h1>
                <p class="text-gray-500 text-sm mt-1">Nº Registro Interno: #{{ str_pad($reserva->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
        <div>
            @if($reserva->estado === 'pendiente')
                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-4 py-1.5 rounded-full font-bold border border-amber-200 shadow-sm text-sm">
                    <span class="material-symbols-outlined text-[18px]">hourglass_empty</span> Pendiente
                </span>
            @elseif(in_array($reserva->estado, ['aprobada', 'confirmada']))
                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-4 py-1.5 rounded-full font-bold border border-green-200 shadow-sm text-sm">
                    <span class="material-symbols-outlined text-[18px]">task_alt</span> Aprobada Validada
                </span>
            @elseif($reserva->estado === 'convertida')
                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full font-bold border border-blue-200 shadow-sm text-sm">
                    <span class="material-symbols-outlined text-[18px]">how_to_reg</span> Matriculada
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-4 py-1.5 rounded-full font-bold border border-red-200 shadow-sm text-sm">
                    <span class="material-symbols-outlined text-[18px]">cancel</span> Rechazada / Cancelada
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 flex items-center gap-3 bg-emerald-50 text-emerald-800 p-4 rounded-xl border border-emerald-200 shadow-sm">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-8 flex items-center gap-3 bg-red-50 text-red-800 p-4 rounded-xl border border-red-200 shadow-sm">
            <span class="material-symbols-outlined text-red-600">error</span>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-10">
        <!-- Content Section 1: Información del Padre/Tutor -->
        <section class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200">
            <div class="px-6 py-4 flex items-center gap-3" style="background-color: #0d631b;">
                <span class="material-symbols-outlined text-white opacity-90">person</span>
                <h2 class="text-white font-bold tracking-wide">Información del Tutor Legal</h2>
            </div>
            <div class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-8 gap-x-12">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Nombre Completo</span>
                    <span class="text-lg font-bold text-gray-800">{{ $reserva->nombre_padre }} {{ $reserva->apellido_padre }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Edad</span>
                    <span class="text-lg font-bold text-gray-800">{{ $reserva->edad_padre }} años</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Teléfono</span>
                    <span class="text-lg font-bold text-gray-800">{{ $reserva->telefono_padre }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Correo Electrónico</span>
                    <span class="text-lg font-bold text-gray-800">{{ $reserva->email_padre ?? 'Sin correo' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Gestión Académica</span>
                    <span class="text-lg font-bold text-gray-800">{{ $reserva->gestion }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Núcleo Familiar</span>
                    <span class="text-lg font-bold text-gray-800">{{ $reserva->cantidad_hijos }} Hijos a Inscribir</span>
                </div>
            </div>
        </section>

        <!-- Content Section 2: Hijos Registrados -->
        <section class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200">
            <div class="px-6 py-4 flex items-center gap-3" style="background-color: #0d631b;">
                <span class="material-symbols-outlined text-white opacity-90">child_care</span>
                <h2 class="text-white font-bold tracking-wide">Carga Familiar Reportada</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-widest border-b border-gray-100">
                            <th class="px-8 py-5">#</th>
                            <th class="px-8 py-5">Nombre Completo</th>
                            <th class="px-8 py-5">Nacimiento</th>
                            <th class="px-8 py-5">Edad</th>
                            <th class="px-8 py-5">Asignación Escolar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($reserva->hijos as $index => $hijo)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-6 font-bold text-green-700">{{ $index + 1 }}</td>
                                <td class="px-8 py-6 font-bold text-gray-800">{{ $hijo->nombre }} {{ $hijo->apellido }}</td>
                                <td class="px-8 py-6 text-gray-600 font-medium whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($hijo->fecha_nacimiento)->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-6">
                                    <span class="bg-green-50 text-green-800 border border-green-200 px-2.5 py-1 rounded font-bold">
                                        {{ $hijo->edad ?? \Carbon\Carbon::parse($hijo->fecha_nacimiento)->age }} años
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-400 font-semibold mb-0.5">{{ $hijo->grado->nivel->nombre ?? 'Nivel' }}</span>
                                        <span class="font-bold text-gray-800">{{ $hijo->grado->nombre ?? 'Grado Desconocido' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Footer Section & Call to Action -->
        <section class="space-y-8 mt-4 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                
                @if($reserva->estado === 'pendiente')
                    <form action="{{ route('reservas.estado', $reserva) }}" method="POST" class="w-full md:w-auto">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="estado" value="confirmada">
                        <button type="submit" class="w-full md:w-auto px-8 py-4 bg-white border-2 border-green-600 text-green-700 hover:bg-green-50 text-lg font-bold rounded-xl shadow-sm transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined text-[24px]">verified</span>
                            Aprobar Expediente
                        </button>
                    </form>

                    <form action="{{ route('reservas.estado', $reserva) }}" method="POST" class="w-full md:w-auto">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="estado" value="cancelada">
                        <button type="submit" class="w-full md:w-auto px-8 py-4 bg-white border border-gray-300 text-red-600 hover:bg-red-50 text-lg font-bold rounded-xl shadow-sm transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined text-[24px]">cancel</span>
                            Rechazar Petición
                        </button>
                    </form>

                @elseif(in_array($reserva->estado, ['aprobada', 'confirmada']))
                    <form action="{{ route('reservas.convertir', $reserva) }}" method="POST" class="w-full md:w-auto text-center" onsubmit="return confirm('Este paso inscribirá temporalmente al estudiante y asignará credenciales formales al padre. ¿Continuar?');">
                        @csrf
                        <!-- Gradient Button specific from HTML markup requested -->
                        <button type="submit" class="w-full md:w-auto px-12 py-5 text-white text-xl font-bold rounded-xl shadow-[0_10px_20px_rgba(13,99,27,0.25)] hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4" style="background: linear-gradient(135deg, #0d631b 0%, #2e7d32 100%);">
                            <span class="material-symbols-outlined text-3xl">person_add</span>
                            Convertir a Estudiantes (crear registros)
                        </button>
                    </form>

                @elseif($reserva->estado === 'convertida')
                    <div class="px-8 py-5 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl flex items-center justify-center gap-4 font-bold max-w-2xl text-center shadow-sm">
                        <span class="material-symbols-outlined text-3xl">how_to_reg</span>
                        Reserva ejecutada. Familias y estudiantes ya consolidados en el sistema central.
                    </div>
                @else
                    <div class="px-8 py-5 bg-gray-100 border border-gray-300 text-gray-500 rounded-xl flex items-center justify-center gap-4 font-bold max-w-2xl text-center shadow-sm">
                        <span class="material-symbols-outlined text-3xl">folder_off</span>
                        Aprobación denegada. Este expediente está inactivo.
                    </div>
                @endif

            </div>
        </section>
    </div>

    <!-- Extra Space for Breathing Room -->
    <div class="h-12"></div>
</div>
@endsection
