@extends('layouts.app')
@section('titulo', 'Registrar Nueva Inscripción')

@section('content')
<div class="max-w-5xl mx-auto font-sans">
    
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#191c1f] mb-2 font-['Public_Sans']">Nueva Inscripción</h1>
        <p class="text-[#40493d] text-lg">Inscriba un estudiante nuevo partiendo de una solicitud de pre-inscripción.</p>
    </div>

    @if($errors->any())
        <div class="mb-8 p-5 rounded-xl bg-[#ffdad6] text-[#93000a] border border-[#ba1a1a]/20 flex flex-col gap-2 shadow-sm">
            <div class="flex items-center gap-2 font-bold mb-1">
                <span class="material-symbols-outlined">error</span> Por favor corrija los siguientes errores:
            </div>
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[14px]">arrow_right</span>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Buscador Select2 -->
    <div class="bg-white rounded-xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100 mb-8 relative z-20">
        <label class="block text-sm font-bold text-[#0d631b] uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">person_search</span> 
            Buscar Estudiante Pre-Inscrito
        </label>
        <div class="w-full">
            <select id="select-estudiante" name="estudiante_id_search" class="w-full text-lg" required>
                <option value="">Escriba el nombre o apellido del estudiante...</option>
            </select>
        </div>
        <p class="text-xs text-[#707a6c] mt-3">Solo aparecen los estudiantes que fueron confirmados desde una Reserva de Cupo y que aún no tienen registro para la gestión actual.</p>
    </div>

    <!-- Formulario Directo (Oculto hasta buscar) -->
    <form action="{{ route('inscripciones.store') }}" method="POST" id="seccion-inscripcion" style="display:none;" class="transition-all duration-500">
        @csrf
        <input type="hidden" name="estudiante_id" id="hidden_estudiante_id">
        
        <div class="grid grid-cols-12 gap-8 relative z-10">
            <!-- Left Column: Data & Documents -->
            <div class="col-span-12 lg:col-span-8 space-y-8">
                
                <!-- Student Data Panel -->
                <section class="bg-white rounded-xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-[#0d631b]/20 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-[#0d631b]"></div>
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4 ml-2">
                        <h2 class="text-xl font-bold text-[#0d631b] flex items-center gap-2">
                            <span class="material-symbols-outlined">person</span>
                            Confirmar y Editar Datos
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 ml-2">
                        <!-- Campos Estudiante -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nombres (Estudiante)</label>
                            <input type="text" name="est_nombre" id="est_nombre" value="{{ old('est_nombre') }}" 
                                class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Apellidos (Estudiante)</label>
                            <input type="text" name="est_apellido" id="est_apellido" value="{{ old('est_apellido') }}" 
                                class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>
                        
                        <!-- Campos Padre/Tutor -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre (Tutor)</label>
                            <input type="text" name="padre_nombre" id="padre_nombre" value="{{ old('padre_nombre') }}" 
                                class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Apellido (Tutor)</label>
                            <input type="text" name="padre_apellido" id="padre_apellido" value="{{ old('padre_apellido') }}" 
                                class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-[#0d631b] uppercase tracking-wider">Grado Escolar Solicitado *</label>
                            <select name="grado_id" id="select-grado" class="w-full bg-[#e7f3e8] border border-[#0d631b]/30 px-4 py-3.5 rounded-lg text-[#0d631b] font-bold focus:ring-[#0d631b] focus:border-[#0d631b] transition-colors" required>
                                <option value="">-- Seleccionar grado --</option>
                                @foreach($grados->groupBy(fn($g) => $g->nivel->nombre ?? 'Sin nivel') as $nivel => $gradosNivel)
                                    <optgroup label="{{ $nivel }}">
                                        @foreach($gradosNivel as $grado)
                                            <option value="{{ $grado->id }}" {{ old('grado_id') == $grado->id ? 'selected' : '' }}>{{ $grado->nombre }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Fecha Inscripción -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha de Oficialización</label>
                            <input type="date" name="fecha_inscripcion" value="{{ old('fecha_inscripcion', date('Y-m-d')) }}" 
                                class="w-full bg-white px-4 py-3 rounded-lg text-[#191c1f] font-medium border border-[#bfcaba] focus:border-[#0d631b] focus:ring-1 focus:ring-[#0d631b] transition-colors" required>
                        </div>
                    </div>
                </section>

                <!-- Documents Checklist -->
                <section class="bg-white rounded-xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
                    <h2 class="text-xl font-bold text-[#0d631b] flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                        <span class="material-symbols-outlined">task</span>
                        Lista de Requisitos Físicos
                    </h2>
                    
                    <div class="space-y-3">
                        @foreach($documentos as $index => $doc)
                        <label class="flex items-center p-4 bg-[#f8f9fd] rounded-xl cursor-pointer hover:bg-[#e7f3e8]/50 transition-colors border border-transparent hover:border-[#0d631b]/20">
                            <input type="checkbox" name="documentos[]" value="{{ $doc }}" 
                                class="w-5 h-5 border-2 border-[#bfcaba] rounded text-[#0d631b] focus:ring-[#0d631b] focus:ring-offset-2 transition-all cursor-pointer">
                            <span class="ml-4 font-medium text-[#191c1f]">{{ $doc }}</span>
                        </label>
                        @endforeach
                    </div>
                </section>
                
            </div>

            <!-- Right Column: Notes & Action -->
            <div class="col-span-12 lg:col-span-4 space-y-8">
                <!-- Observations Panel -->
                <section class="bg-white rounded-xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100 h-full flex flex-col">
                    <h2 class="text-xl font-bold text-[#0d631b] flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                        <span class="material-symbols-outlined">description</span>
                        Observaciones
                    </h2>
                    
                    <div class="flex-1 flex flex-col gap-4">
                        <p class="text-sm text-[#707a6c]">Incluya notas adicionales que Secretaría deba conocer sobre este ingreso.</p>
                        <textarea name="observaciones" class="w-full flex-1 min-h-[220px] p-4 bg-[#f2f3f7] rounded-xl border border-transparent focus:border-[#0d631b] focus:ring-1 focus:ring-[#0d631b] text-[#191c1f] placeholder-[#bfcaba] resize-none font-medium transition-all" 
                                placeholder="Escriba aquí las observaciones finales...">{{ old('observaciones') }}</textarea>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <button type="submit" class="w-full py-4 px-6 rounded-xl bg-gradient-to-br from-[#0d631b] to-[#006e1c] text-white font-bold text-lg shadow-lg shadow-[#0d631b]/20 hover:opacity-95 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                            Registrar Inscripción
                        </button>
                        
                        <a href="{{ route('inscripciones.index') }}" class="mt-4 w-full py-3 px-6 rounded-xl border-2 border-gray-200 text-gray-600 font-bold text-center block hover:bg-gray-50 transition-all">
                            Cancelar y Volver
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Tailwind overwrites for Select2 */
    .select2-container--default .select2-selection--single {
        height: 54px;
        padding: 12px;
        border: 2px solid #e1e2e6;
        border-radius: 0.75rem;
        transition: all 0.2s;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #0d631b !important;
        outline: none;
        box-shadow: 0 0 0 4px rgba(13, 99, 27, 0.1);
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 52px;
        right: 14px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
        font-weight: 600;
        color: #191c1f;
    }
</style>

<script>
$(document).ready(function() {
    $('#select-estudiante').select2({
        placeholder: 'Haga clic para buscar...',
        minimumInputLength: 2,
        language: {
            inputTooShort: () => 'Escriba al menos 2 caracteres...',
            noResults:     () => 'No se encontraron estudiantes pendientes de inscripción.',
            searching:     () => 'Buscando en la base de datos...',
        },
        ajax: {
            url: '{{ route("estudiantes.buscar") }}',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    q: params.term,
                    tipo: 'nuevo'
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true,
        },
    });

    $('#select-estudiante').on('select2:select', function(e) {
        const d = e.params.data;
        
        // El backend en estudiantes.buscar retorna "text" (nombre + apellido), pero no los devuelve partidos.
        // Lo partimos por el primer espacio asumiendo Nombres y Apellidos en caso de no tener la data partida.
        // Lo ideal sería que el backend los mande partidos, pero nos adaptamos:
        let nombres = d.text;
        let apellidos = " ";
        const partes = d.text.split(" ");
        if(partes.length > 1) {
            nombres = partes.slice(0, Math.ceil(partes.length / 2)).join(" ");
            apellidos = partes.slice(Math.ceil(partes.length / 2)).join(" ");
        }

        let pNombre = d.padre_nombre;
        let pApellido = " ";
        if(d.padre_nombre){
             const pt = d.padre_nombre.split(" ");
             if(pt.length > 1) {
                 pNombre = pt.slice(0, Math.ceil(pt.length / 2)).join(" ");
                 pApellido = pt.slice(Math.ceil(pt.length / 2)).join(" ");
             }
        }

        $('#hidden_estudiante_id').val(d.id);
        $('#est_nombre').val(nombres);
        $('#est_apellido').val(apellidos);
        $('#padre_nombre').val(pNombre);
        $('#padre_apellido').val(pApellido);
        
        // Show the form
        $('#seccion-inscripcion').fadeIn(400);
    });

    $('#select-estudiante').on('select2:clear', function() {
        $('#seccion-inscripcion').fadeOut(200);
    });
    
    // Auto-reveal if old input exists
    if ("{{ old('estudiante_id') }}") {
        $('#hidden_estudiante_id').val("{{ old('estudiante_id') }}");
        $('#seccion-inscripcion').show();
        // Option injection for Select2 to show the selected text
        var $newOption = $("<option selected='selected'></option>").val("{{ old('estudiante_id') }}").text("{{ old('est_nombre') }} {{ old('est_apellido') }}");
        $("#select-estudiante").append($newOption).trigger('change');
    }
});
</script>
@endsection
