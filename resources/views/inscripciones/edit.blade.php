@extends('layouts.app')
@section('titulo', 'Finalizar Registro de Inscripción')

@section('content')
<div class="max-w-5xl mx-auto font-sans">
    
    <!-- Header Section -->
    <div class="mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#191c1f] mb-2 font-['Public_Sans']">Finalizar Registro de Inscripción</h1>
        <p class="text-[#40493d] text-lg">Verifique o actualice los datos del estudiante y confirme la recepción de documentos para concluir el proceso institucional.</p>
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

    <!-- Formulario Directo -->
    <form action="{{ route('inscripciones.update', $inscripcion) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-12 gap-8">
            <!-- Left Column: Data & Documents -->
            <div class="col-span-12 lg:col-span-8 space-y-8">
                
                <!-- Student Data Panel -->
                <section class="bg-white rounded-xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h2 class="text-xl font-bold text-[#0d631b] flex items-center gap-2">
                            <span class="material-symbols-outlined">person</span>
                            Datos del Estudiante y Tutor
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <!-- Campos Estudiante -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nombres (Estudiante)</label>
                            <input type="text" name="est_nombre" value="{{ old('est_nombre', $inscripcion->estudiante->nombre) }}" 
                                class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Apellidos (Estudiante)</label>
                            <input type="text" name="est_apellido" value="{{ old('est_apellido', $inscripcion->estudiante->apellido) }}" 
                                class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>
                        
                        <!-- Campos Padre/Tutor -->
                        @if($inscripcion->estudiante->padre)
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre (Tutor)</label>
                            <input type="text" name="padre_nombre" value="{{ old('padre_nombre', $inscripcion->estudiante->padre->nombre) }}" 
                                class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Apellido (Tutor)</label>
                            <input type="text" name="padre_apellido" value="{{ old('padre_apellido', $inscripcion->estudiante->padre->apellido) }}" 
                                class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>
                        @else
                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Padre / Tutor Responsable</label>
                            <div class="bg-[#f2f3f7] px-4 py-3 rounded-lg text-gray-500 italic">No registrado</div>
                        </div>
                        @endif

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Grado Escolar Asignado</label>
                            <div class="bg-[#e7f3e8] text-[#0d631b] px-4 py-3 rounded-lg font-bold border-b-2 border-[#0d631b]/30">
                                {{ $inscripcion->grado->nivel->nombre ?? '' }} - {{ $inscripcion->grado->nombre ?? 'Sin Grado' }}
                            </div>
                        </div>
                        
                        <!-- Fecha Inscripción -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha de Confirmación</label>
                            <input type="date" name="fecha_inscripcion" value="{{ old('fecha_inscripcion', $inscripcion->fecha_inscripcion ?? date('Y-m-d')) }}" 
                                class="w-full bg-white px-4 py-2.5 rounded-lg text-[#191c1f] font-medium border border-[#bfcaba] focus:border-[#0d631b] focus:ring-1 focus:ring-[#0d631b] transition-colors" required>
                        </div>
                    </div>
                </section>

                <!-- Documents Checklist -->
                <section class="bg-white rounded-xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
                    <h2 class="text-xl font-bold text-[#0d631b] flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                        <span class="material-symbols-outlined">task</span>
                        Documentos Presentados Físicamente
                    </h2>
                    
                    @php
                        $docsActuales = $inscripcion->documentos->where('presentado', 1)->pluck('tipo')->toArray();
                    @endphp
                    
                    <div class="space-y-3">
                        @foreach($documentos as $index => $doc)
                        <label class="relative flex items-center justify-between p-4 bg-[#f8f9fd] rounded-xl cursor-pointer group hover:bg-[#e7f3e8]/50 transition-colors border border-transparent hover:border-[#0d631b]/20">
                            <!-- Input Peer element -->
                            <input type="checkbox" name="documentos[]" value="{{ $doc }}" 
                                class="peer absolute w-6 h-6 border-2 border-[#bfcaba] rounded text-[#0d631b] focus:ring-[#0d631b] focus:ring-offset-2 transition-all cursor-pointer left-4 top-1/2 -translate-y-1/2 z-10 opacity-0"
                                {{ in_array($doc, $docsActuales) ? 'checked' : '' }}>
                            
                            <!-- Custom Checkbox visual -->
                            <div class="flex items-center gap-4">
                                <div class="w-6 h-6 border-2 border-[#bfcaba] rounded bg-white mt-0.5 peer-checked:bg-[#0d631b] peer-checked:border-[#0d631b] flex items-center justify-center transition-colors">
                                    <span class="material-symbols-outlined text-white text-[16px] opacity-0 peer-checked:opacity-100">check</span>
                                </div>
                                <span class="font-medium text-[#191c1f] group-hover:text-[#0d631b] transition-colors">{{ $doc }}</span>
                            </div>

                            <!-- Badge shown via peer-checked logic -->
                            <div class="opacity-0 peer-checked:opacity-100 px-3 py-1 bg-[#94f990]/30 text-[#006e1c] text-xs font-bold rounded uppercase tracking-wider transition-opacity mr-2">
                                Verificado
                            </div>
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
                        <p class="text-sm text-[#707a6c]">Incluya notas adicionales sobre el estado o acuerdos especiales.</p>
                        <textarea name="observaciones" class="w-full flex-1 min-h-[220px] p-4 bg-[#f2f3f7] rounded-xl border border-transparent focus:border-[#0d631b] focus:ring-1 focus:ring-[#0d631b] text-[#191c1f] placeholder-[#bfcaba] resize-none font-medium transition-all" 
                                placeholder="Escriba aquí las observaciones finales...">{{ old('observaciones', $inscripcion->observaciones) }}</textarea>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <button type="submit" class="w-full py-4 px-6 rounded-xl bg-gradient-to-br from-[#0d631b] to-[#006e1c] text-white font-bold text-lg shadow-lg shadow-[#0d631b]/20 hover:opacity-95 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">how_to_reg</span>
                            @if($inscripcion->estado === 'pendiente')
                                Finalizar Inscripción
                            @else
                                Guardar Cambios
                            @endif
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
