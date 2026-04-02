@extends('layouts.app')

@section('titulo', 'Editar Estudiante - Sistema Tiquipaya')

@section('content')
<div class="max-w-7xl mx-auto font-sans">
    
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#191c1f] font-['Public_Sans'] flex items-center gap-3">
                <span class="material-symbols-outlined text-[#0d631b] text-[36px]">manage_accounts</span>
                Editar Ficha Académica
            </h1>
            <p class="text-[#40493d] text-lg mt-2">Corrija información personal, asigne tutor y gestione la inscripción actual del estudiante.</p>
        </div>
        <a href="{{ route('estudiantes.show', $estudiante) }}" class="px-5 py-2.5 bg-white border-2 border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span> Volver al Perfil
        </a>
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

    <form method="POST" action="{{ route('estudiantes.update', $estudiante) }}">
        @csrf
        @method('PUT')
        
        <!-- Bandera para indicar al controlador que estamos mandando checks de documentos -->
        <input type="hidden" name="documentos_form" value="1">

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Left Column: Personal Data -->
            <div class="xl:col-span-2 space-y-8">
                
                <section class="bg-white rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
                    <h2 class="text-xl font-bold text-[#0d631b] flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                        <span class="material-symbols-outlined">badge</span>
                        Información Personal
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre Completo</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $estudiante->nombre) }}" class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Apellidos</label>
                            <input type="text" name="apellido" value="{{ old('apellido', $estudiante->apellido) }}" class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cédula de Identidad (CI)</label>
                            <input type="text" name="ci" value="{{ old('ci', $estudiante->ci) }}" class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $estudiante->fecha_nacimiento) }}" class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border-b-2 border-transparent focus:border-[#0d631b] focus:bg-white focus:ring-0 transition-colors" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Género</label>
                            <select name="genero" class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border border-transparent focus:border-[#0d631b] transition-colors" required>
                                <option value="M" {{ old('genero', $estudiante->genero) == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('genero', $estudiante->genero) == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tipo de Alumno</label>
                            <select name="tipo" class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border border-transparent focus:border-[#0d631b] transition-colors" required>
                                <option value="nuevo" {{ old('tipo', $estudiante->tipo) == 'nuevo' ? 'selected' : '' }}>Estudiante Nuevo</option>
                                <option value="antiguo" {{ old('tipo', $estudiante->tipo) == 'antiguo' ? 'selected' : '' }}>Renovación (Antiguo)</option>
                            </select>
                        </div>
                        
                        <div class="col-span-1 md:col-span-2 space-y-2 mt-4">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tutor a Cargo (Vincular)</label>
                            <select name="padre_id" class="w-full bg-[#f2f3f7] px-4 py-3 rounded-lg text-[#191c1f] font-medium border border-transparent focus:border-[#0d631b] transition-colors">
                                <option value="">-- Dejar sin asignar temporalmente --</option>
                                @foreach($padres as $padre)
                                    <option value="{{ $padre->id }}" {{ old('padre_id', $estudiante->padre_id) == $padre->id ? 'selected' : '' }}>
                                        {{ $padre->nombre }} {{ $padre->apellido }} (CI: {{ $padre->ci ?? 'N/D' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>
                
                <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-br from-[#0d631b] to-[#006e1c] text-white font-bold text-lg shadow-lg hover:opacity-95 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">save</span>
                    Guardar Cambios del Estudiante
                </button>
            </div>

            <!-- Right Column: Inscription Documents & Observations -->
            <div class="space-y-8">
                @if($estudiante->inscripcionActual)
                    @php $ins = $estudiante->inscripcionActual; @endphp
                    
                    <section class="bg-orange-50 rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-orange-200 h-full flex flex-col relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 opacity-10">
                            <span class="material-symbols-outlined text-[100px] text-orange-900">warning</span>
                        </div>
                        
                        <h2 class="text-xl font-bold text-orange-800 flex items-center gap-2 mb-6 border-b border-orange-200 pb-4 relative z-10">
                            <span class="material-symbols-outlined">assignment_late</span>
                            Estado de la Inscripción
                        </h2>
                        
                        <div class="flex-1 flex flex-col gap-6 relative z-10">
                            
                            <!-- Documents Sub-panel -->
                            <div>
                                <label class="text-xs font-bold text-orange-700 uppercase tracking-wider mb-3 block">Documentos (Marcar al entregar)</label>
                                @php
                                    $allDocs = \App\Http\Controllers\InscripcionController::DOCUMENTOS;
                                    $docsActuales = $ins->documentos->where('presentado', 1)->pluck('tipo')->toArray();
                                @endphp
                                <div class="space-y-2 bg-white rounded-xl p-4 border border-orange-100">
                                    @foreach($allDocs as $doc)
                                    <label class="flex items-center p-2 rounded hover:bg-orange-50 cursor-pointer transition-colors group">
                                        <input type="checkbox" name="documentos[]" value="{{ $doc }}" 
                                            class="w-4 h-4 border-2 border-orange-300 rounded text-orange-600 focus:ring-orange-500 transition-all cursor-pointer"
                                            {{ in_array($doc, $docsActuales) ? 'checked' : '' }}>
                                        <span class="ml-3 font-medium text-sm text-gray-700 group-hover:text-orange-900">{{ $doc }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Observations Sub-panel -->
                            <div class="flex-1 flex flex-col">
                                <label class="text-xs font-bold text-orange-700 uppercase tracking-wider mb-3 block">Observaciones y Notas</label>
                                <textarea name="observaciones" class="w-full flex-1 min-h-[160px] p-4 bg-white rounded-xl border border-orange-100 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-[#191c1f] placeholder-orange-300 resize-none font-medium transition-all" 
                                    placeholder="Aquí puedes poner o borrar las observaciones...">{{ old('observaciones', $ins->observaciones) }}</textarea>
                                <p class="text-xs text-orange-600 mt-3 font-medium">Al presionar Guardar Cambios, estas observaciones se actualizarán y serán visibles en el Panel del Padre.</p>
                            </div>
                            
                        </div>
                    </section>
                @else
                    <section class="bg-[#f8f9fd] rounded-2xl p-8 border border-gray-200 border-dashed h-full flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-gray-300 text-[64px] mb-4">folder_off</span>
                        <h3 class="text-gray-900 font-bold text-lg mb-2">Sin inscripción en {{ date('Y') }}</h3>
                        <p class="text-gray-500 text-sm">Este estudiante no posee una inscripción activa en la gestión actual. Para añadir observaciones y verificar documentos, regístrelo primero.</p>
                        <a href="{{ route('inscripciones.create') }}?estudiante_id={{ $estudiante->id }}" class="mt-6 px-4 py-2 bg-white border border-gray-200 text-[#0d631b] font-bold rounded-lg hover:border-[#0d631b] transition-colors text-sm">
                            Realizar Inscripción
                        </a>
                    </section>
                @endif
            </div>

        </div>
    </form>
</div>
@endsection
