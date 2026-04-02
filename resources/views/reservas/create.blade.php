@extends('layouts.guest')
@section('titulo', 'Solicitud de Admisión')

@section('content')
<div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
    
    <!-- Form Header (Premium Academic Gradient) -->
    <div class="px-8 pt-12 pb-10 text-center relative overflow-hidden" style="background: linear-gradient(135deg, #022c22 0%, #065f46 50%, #047857 100%);">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl -ml-20 -mb-20 pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col items-center gap-4">
            <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner border border-white/20 transform -rotate-3">
                <span class="material-symbols-outlined text-4xl text-green-300" style="font-variation-settings: 'FILL' 1;">assignment_ind</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight font-['Public_Sans']">Solicitud de Reserva Escolar</h1>
            <p class="text-emerald-100/90 font-medium max-w-xl mx-auto text-sm sm:text-base leading-relaxed tracking-wide">
                Registra a tus dependientes de manera digital para iniciar el proceso de matrícula de la Gestión <span class="font-bold text-white">{{ date('Y') }}</span>.
            </p>
        </div>
    </div>

    <!-- Form Content -->
    <div class="px-6 sm:px-12 py-10 bg-[#f8f9fd]">
        
        @if($errors->any())
            <div class="mb-8 p-5 rounded-xl bg-red-50 text-red-800 border-l-4 border-red-600 flex flex-col gap-2 shadow-sm animate-pulse">
                <div class="flex items-center gap-2 font-black text-lg mb-1">
                    <span class="material-symbols-outlined border-2 border-red-600 rounded-full">error</span> Verifica los siguientes campos:
                </div>
                <div class="space-y-1.5 pl-2">
                    @foreach($errors->all() as $error)
                        <div class="flex items-start gap-2 text-sm font-semibold">
                            <span class="text-red-500 font-bold">•</span>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('reservas.store') }}" id="formReserva" class="space-y-12">
            @csrf

            <!-- SECCIÓN 1: DATOS DEL PADRE -->
            <section class="space-y-6">
                <div class="flex items-center gap-3 border-b-2 border-green-800/10 pb-3">
                    <div class="bg-green-800 p-2 rounded-lg text-white flex items-center shadow-md">
                        <span class="material-symbols-outlined text-2xl">family_restroom</span>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">1. Datos del Tutor de Familia</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 relative">
                        <label for="nombre_padre" class="block text-sm font-bold text-gray-700 ml-1 uppercase tracking-wider text-xs">Nombre(s) *</label>
                        <div class="relative group">
                            <input type="text" id="nombre_padre" name="nombre_padre" value="{{ old('nombre_padre') }}" required 
                                class="block w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600/30 focus:border-green-600 text-gray-900 font-medium transition-all duration-200 shadow-sm" placeholder="Ej: Juan Carlos">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-400 group-focus-within:text-green-600 transition-colors">badge</span>
                        </div>
                    </div>
                    <div class="space-y-2 relative">
                        <label for="apellido_padre" class="block text-sm font-bold text-gray-700 ml-1 uppercase tracking-wider text-xs">Apellidos *</label>
                        <div class="relative group">
                            <input type="text" id="apellido_padre" name="apellido_padre" value="{{ old('apellido_padre') }}" required 
                                class="block w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600/30 focus:border-green-600 text-gray-900 font-medium transition-all duration-200 shadow-sm" placeholder="Ej: Mamani Condori">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-400 group-focus-within:text-green-600 transition-colors">badge</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2 relative">
                        <label for="edad_padre" class="block text-sm font-bold text-gray-700 ml-1 uppercase tracking-wider text-xs">Edad *</label>
                        <div class="relative group">
                            <input type="number" id="edad_padre" name="edad_padre" min="18" max="99" value="{{ old('edad_padre') }}" required 
                                class="block w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600/30 focus:border-green-600 text-gray-900 font-bold transition-all duration-200 shadow-sm">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-400 group-focus-within:text-green-600 transition-colors">cake</span>
                        </div>
                    </div>
                    <div class="space-y-2 relative">
                        <label for="telefono_padre" class="block text-sm font-bold text-gray-700 ml-1 uppercase tracking-wider text-xs">Teléfono/Celular *</label>
                        <div class="relative group">
                            <input type="tel" id="telefono_padre" name="telefono_padre" value="{{ old('telefono_padre') }}" required 
                                class="block w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600/30 focus:border-green-600 text-gray-900 font-bold transition-all duration-200 shadow-sm" placeholder="Ej: 76123456">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-400 group-focus-within:text-green-600 transition-colors">call</span>
                        </div>
                    </div>
                    <div class="space-y-2 relative">
                        <label for="email_padre" class="block text-sm font-bold text-gray-700 ml-1 uppercase tracking-wider text-xs">Email <span class="lowercase text-[10px] text-gray-400">(opcional)</span></label>
                        <div class="relative group">
                            <input type="email" id="email_padre" name="email_padre" value="{{ old('email_padre') }}" 
                                class="block w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600/30 focus:border-green-600 text-gray-900 font-medium transition-all duration-200 shadow-sm" placeholder="correo@ejemplo.com">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-400 group-focus-within:text-green-600 transition-colors">mail</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECCIÓN 2: CANTIDAD DE HIJOS -->
            <section class="space-y-6 bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-[1.25rem] border border-green-200/60 shadow-inner">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-green-700 rounded-full flex items-center justify-center text-white shadow-md">
                            <span class="material-symbols-outlined text-3xl">child_care</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Número de Postulantes</h2>
                            <p class="text-sm font-medium text-green-800">Seleccione cuántos estudiantes va a matricular bajo su cargo.</p>
                        </div>
                    </div>
                    <div class="w-full sm:w-64 relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none border-r border-gray-300 pr-3 h-full">
                            <span class="material-symbols-outlined text-green-700 font-bold group-focus-within:text-green-900 transition-colors">groups</span>
                        </div>
                        <select id="cantidad_hijos" name="cantidad_hijos" required onchange="generarFormulariosHijos()" 
                            class="block w-full pl-16 pr-8 py-4 bg-white border-2 border-green-600/30 rounded-xl focus:ring-2 focus:ring-green-600/40 focus:border-green-700 text-gray-900 font-black text-lg cursor-pointer shadow-sm appearance-none transition-all">
                            <option value="" class="text-gray-400 font-normal">-- Seleccione --</option>
                            <option value="1" {{ old('cantidad_hijos') == '1' ? 'selected' : '' }}>1 Estudiante</option>
                            <option value="2" {{ old('cantidad_hijos') == '2' ? 'selected' : '' }}>2 Estudiantes</option>
                            <option value="3" {{ old('cantidad_hijos') == '3' ? 'selected' : '' }}>3 Estudiantes</option>
                            <option value="4" {{ old('cantidad_hijos') == '4' ? 'selected' : '' }}>4 Estudiantes</option>
                            <option value="5" {{ old('cantidad_hijos') == '5' ? 'selected' : '' }}>5 Estudiantes</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                            <span class="material-symbols-outlined text-green-700 font-bold">expand_more</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECCIÓN 3: FORMULARIOS HIJOS (DINÁMICO) -->
            <div id="hijosContainer" class="space-y-8"></div>

            <!-- ACTION BUTTONS -->
            <div class="pt-8 border-t-2 border-gray-200 border-dashed flex flex-col items-center gap-4 pb-4">
                <button type="submit" class="w-full md:w-auto min-w-[300px] flex justify-center items-center py-5 px-8 rounded-xl shadow-xl hover:shadow-2xl text-xl font-bold text-white hover:scale-[1.02] active:scale-[0.98] transition-all duration-200" style="background: linear-gradient(135deg, #022c22 0%, #065f46 50%, #047857 100%);">
                    Cargar Formulario Interno
                    <span class="material-symbols-outlined ml-3 text-3xl">send_and_archive</span>
                </button>
                <a href="{{ route('login') }}" class="text-sm font-bold text-gray-500 hover:text-red-600 uppercase tracking-widest flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                    Cancelar y salir
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const grados = {!! json_encode($grados->map(function($g) { return ['id' => $g->id, 'nivel' => $g->nivel->nombre, 'nombre' => $g->nombre]; })) !!};

    function generarFormulariosHijos() {
        const cantidad = document.getElementById('cantidad_hijos').value;
        const container = document.getElementById('hijosContainer');
        container.innerHTML = '';

        if (!cantidad) return;

        const titulo = document.createElement('div');
        titulo.className = 'flex items-center gap-3 border-b-2 border-gray-300 pb-3 mt-10 mb-8';
        titulo.innerHTML = `
            <div class="bg-gray-800 p-2 rounded-lg text-white flex items-center shadow-md">
                <span class="material-symbols-outlined text-2xl">school</span>
            </div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">3. Expediente del Estudiante</h2>
        `;
        container.appendChild(titulo);

        for (let i = 1; i <= parseInt(cantidad); i++) {
            const oldValueNombre = `{{ old('hijos.${i-1}.nombre', '') }}`;
            const oldValueApellido = `{{ old('hijos.${i-1}.apellido', '') }}`;
            const oldValueFecha = `{{ old('hijos.${i-1}.fecha_nacimiento', '') }}`;
            
            const hijo = document.createElement('div');
            hijo.className = 'bg-white p-6 sm:p-10 rounded-[1.25rem] border-2 border-gray-200 shadow-[0_4px_12px_rgba(0,0,0,0.02)] relative overflow-hidden group hover:border-green-600 transition-colors';
            
            hijo.innerHTML = `
                <div class="absolute top-0 left-0 w-2 h-full bg-green-600 group-hover:bg-green-500 transition-colors"></div>
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
                    <h3 class="text-xl font-black text-green-800 uppercase tracking-widest flex items-center gap-3">
                        <span class="w-10 h-10 rounded-lg bg-green-100 border border-green-200 text-green-800 flex items-center justify-center text-xl shadow-sm">${i}</span>
                        Aspirante Escolar
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    <div class="space-y-2 relative">
                        <label class="block text-sm font-bold text-gray-700 ml-1 uppercase tracking-wider text-xs">Nombre Completo *</label>
                        <div class="relative group">
                            <input type="text" name="hijos[${i-1}][nombre]" class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-600/30 focus:border-green-600 text-gray-900 font-medium transition-all shadow-sm" required value="${oldValueNombre}" placeholder="Nombres">
                            <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400 group-focus-within:text-green-600">face</span>
                        </div>
                    </div>
                    <div class="space-y-2 relative">
                        <label class="block text-sm font-bold text-gray-700 ml-1 uppercase tracking-wider text-xs">Apellidos *</label>
                        <div class="relative group">
                            <input type="text" name="hijos[${i-1}][apellido]" class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-600/30 focus:border-green-600 text-gray-900 font-medium transition-all shadow-sm" required value="${oldValueApellido}" placeholder="Apellidos Reales">
                            <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400 group-focus-within:text-green-600">badge</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2 relative">
                        <label class="block text-sm font-bold text-gray-700 ml-1 uppercase tracking-wider text-xs">Fecha Nacimiento *</label>
                        <div class="relative group">
                            <input type="date" name="hijos[${i-1}][fecha_nacimiento]" class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-600/30 focus:border-green-600 text-gray-900 font-bold transition-all shadow-sm" required value="${oldValueFecha}">
                            <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400 group-focus-within:text-green-600">event</span>
                        </div>
                    </div>
                    <div class="space-y-2 relative">
                        <label class="block text-sm font-bold text-blue-700 ml-1 uppercase tracking-wider text-xs">Cálculo de Edad</label>
                        <div class="relative group">
                            <input type="text" class="edad-display block w-full pl-12 pr-4 py-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 font-black text-center cursor-not-allowed shadow-inner" readonly placeholder="-- años">
                            <span class="material-symbols-outlined absolute left-4 top-3 text-blue-400">history_toggle_off</span>
                        </div>
                    </div>
                    <div class="space-y-2 relative">
                        <label class="block text-sm font-bold text-gray-700 ml-1 uppercase tracking-wider text-xs">Nivel al que postula *</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-green-700 group-focus-within:text-green-900 transition-colors">domain</span>
                            </div>
                            <select name="hijos[${i-1}][grado_id]" class="block w-full pl-12 pr-8 py-3 bg-white border-2 border-green-600/40 rounded-xl focus:ring-2 focus:ring-green-600/40 focus:border-green-700 font-bold text-gray-800 cursor-pointer transition-all shadow-sm" required>
                                <option value="" class="text-gray-400 font-normal">-- Seleccionar Curso --</option>
                                ${grados.map(g => `<option value="${g.id}">${g.nivel} - ${g.nombre}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(hijo);

            // Bind age calculation
            const fechaInput = hijo.querySelector('input[type="date"]');
            const edadDisplay = hijo.querySelector('.edad-display');
            
            // Initial calculation if value exists
            if (fechaInput.value) {
                edadDisplay.value = calcularEdad(new Date(fechaInput.value)) + ' años';
            }

            fechaInput.addEventListener('change', function() {
                if (this.value) {
                    const edad = calcularEdad(new Date(this.value));
                    edadDisplay.value = edad + ' años';
                } else {
                    edadDisplay.value = '-- años';
                }
            });
        }
    }

    function calcularEdad(fecha) {
        // Corrección de huso horario para evitar desfases de 1 día
        const utcDate = new Date(fecha.getTime() + fecha.getTimezoneOffset() * 60000);
        const hoy = new Date();
        let edad = hoy.getFullYear() - utcDate.getFullYear();
        const mes = hoy.getMonth() - utcDate.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < utcDate.getDate())) {
            edad--;
        }
        return Math.max(0, edad);
    }

    // Trigger on load for validation persistence
    document.addEventListener('DOMContentLoaded', function() {
        const cantidad = document.getElementById('cantidad_hijos').value;
        if (cantidad) {
            generarFormulariosHijos();
        }
    });
</script>
@endsection
