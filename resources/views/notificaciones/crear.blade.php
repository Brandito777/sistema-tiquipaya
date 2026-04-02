@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">campaign</span>
                Radiodifusión de Notificaciones
            </h1>
            <p class="text-sm text-gray-500 mt-1">Envía comunicados oficiales, alertas y recordatorios a los padres de familia.</p>
        </div>
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

    <form method="POST" action="{{ route('notificaciones.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf

        <!-- Columna Izquierda: Configuración de Destinatarios -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-[20px]">groups</span>
                    <h3 class="text-sm font-semibold text-gray-700">Audiencia Objetivo</h3>
                </div>
                
                <div class="p-5 space-y-4">
                    <!-- Opción: Todos -->
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-200 peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 hover:bg-gray-50 transition-colors">
                        <input type="radio" name="destinatarios" value="todos" id="dest_todos" checked class="peer sr-only">
                        <div class="flex w-full items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                    <span class="material-symbols-outlined">public</span>
                                </div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">Todos los Padres</p>
                                    <p class="text-gray-500">Aviso general para todo el colegio.</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity">check_circle</span>
                        </div>
                    </label>

                    <!-- Opción: Por Nivel -->
                    <label class="relative flex flex-col gap-3 cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-200 peer-checked:border-blue-500 hover:bg-gray-50 transition-colors">
                        <div class="flex w-full items-center justify-between">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="destinatarios" value="nivel" id="dest_nivel" class="peer sr-only">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <span class="material-symbols-outlined">account_tree</span>
                                </div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">Por Nivel Educativo</p>
                                    <p class="text-gray-500">Ej: Solo a Inicial o Primaria.</p>
                                </div>
                            </div>
                        </div>
                        <div class="pl-13 w-full">
                            <select name="nivel_id" id="nivel_select" disabled class="mt-2 w-full pl-3 pr-10 py-2 text-sm border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-400 transition-colors">
                                <option value="">-- Seleccionar nivel --</option>
                                @foreach($niveles as $nivel)
                                    <option value="{{ $nivel->id }}">{{ $nivel->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </label>

                    <!-- Opción: Por Grado -->
                    <label class="relative flex flex-col gap-3 cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-200 peer-checked:border-blue-500 hover:bg-gray-50 transition-colors">
                        <div class="flex w-full items-center justify-between">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="destinatarios" value="grado" id="dest_grado" class="peer sr-only">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                                    <span class="material-symbols-outlined">school</span>
                                </div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">Por Curso Específico</p>
                                    <p class="text-gray-500">Ej: 1ro de Primaria A.</p>
                                </div>
                            </div>
                        </div>
                        <div class="pl-13 w-full">
                            <select name="grado_id" id="grado_select" disabled class="mt-2 w-full pl-3 pr-10 py-2 text-sm border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-400 transition-colors">
                                <option value="">-- Seleccionar grado --</option>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id }}">{{ $grado->nivel->nombre }} - {{ $grado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </label>

                    <!-- Opción: Padre Específico -->
                    <label class="relative flex flex-col gap-3 cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-200 peer-checked:border-blue-500 hover:bg-gray-50 transition-colors">
                        <div class="flex w-full items-center justify-between">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="destinatarios" value="padre" id="dest_padre" class="peer sr-only">
                                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">Padre / Tutor Específico</p>
                                    <p class="text-gray-500">Mensaje directo y privado.</p>
                                </div>
                            </div>
                        </div>
                        <div class="pl-13 w-full">
                            <select name="padre_id" id="padre_select" disabled class="mt-2 w-full pl-3 pr-10 py-2 text-sm border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-400 transition-colors">
                                <option value="">-- Buscar padre --</option>
                                @foreach($padres as $padre)
                                    <option value="{{ $padre->id }}">{{ $padre->nombre }} {{ $padre->apellido }} ({{ $padre->user->email ?? 'sin email' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Redacción -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-[20px]">edit_document</span>
                    <h3 class="text-sm font-semibold text-gray-700">Contenido del Mensaje</h3>
                </div>
                
                <div class="p-5 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título de la Notificación</label>
                        <input type="text" name="titulo" placeholder="Ej: Suspensión de clases por feriado" required
                               class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje Detallado</label>
                        <textarea name="mensaje" rows="8" placeholder="Escribe el cuerpo del mensaje aquí..." required
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"></textarea>
                    </div>
                </div>
                
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 shadow-sm transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        Desplegar Notificación
                    </button>
                </div>
            </div>

            <!-- Hint box -->
            <div class="bg-blue-50 rounded-xl p-4 flex gap-3 border border-blue-100">
                <span class="material-symbols-outlined text-blue-500 mt-0.5">tips_and_updates</span>
                <div>
                    <h4 class="text-sm font-semibold text-blue-900">Consejo de uso</h4>
                    <p class="text-sm text-blue-700 mt-1">Las notificaciones enviadas no pueden ser editadas ni eliminadas, ya que se entregan instantáneamente al buzón de entrada (Mi Panel) de los padres seleccionados.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // UI Logic for enabling/disabling selects based on selected audience radio
        const radios = document.querySelectorAll('input[name="destinatarios"]');
        const selects = {
            'dest_nivel': document.getElementById('nivel_select'),
            'dest_grado': document.getElementById('grado_select'),
            'dest_padre': document.getElementById('padre_select')
        };

        function updateSelectState() {
            // Disable all first
            Object.values(selects).forEach(el => {
                if(el) {
                    el.disabled = true;
                    // Styling logic for disabled state
                    el.closest('div').style.opacity = '0.5';
                }
            });
            
            // Remove border highlight from all labels
            document.querySelectorAll('input[name="destinatarios"]').forEach(radio => {
                radio.closest('label').classList.remove('border-blue-500', 'ring-1', 'ring-blue-500');
            });

            // Find selected radio
            const selectedRadio = document.querySelector('input[name="destinatarios"]:checked');
            if (selectedRadio) {
                // Highlight its label
                selectedRadio.closest('label').classList.add('border-blue-500', 'ring-1', 'ring-blue-500');
                
                // Enable corresponding select if exists
                const targetSelect = selects[selectedRadio.id];
                if (targetSelect) {
                    targetSelect.disabled = false;
                    targetSelect.closest('div').style.opacity = '1';
                }
            }
        }

        radios.forEach(radio => {
            radio.addEventListener('change', updateSelectState);
        });

        updateSelectState(); // Initial check
    });
</script>
@endsection
