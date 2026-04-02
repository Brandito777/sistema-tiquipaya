@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">person_add</span>
                Nuevo Padre / Tutor
            </h1>
            <p class="text-sm text-gray-500 mt-1">Registra un nuevo tutor legal y genera sus credenciales de acceso al sistema.</p>
        </div>
        <a href="{{ route('padres.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 font-medium text-sm transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            Volver al Directorio
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 text-red-800 p-4 rounded-xl border border-red-200">
            <div class="flex items-center gap-2 mb-2 font-semibold">
                <span class="material-symbols-outlined text-red-600">error</span>
                Error en la validación
            </div>
            <ul class="list-disc pl-8 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('padres.store') }}" class="space-y-6">
        @csrf

        <!-- Bloque 1: Información Personal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                <span class="material-symbols-outlined text-gray-500 text-[20px]">badge</span>
                <h3 class="text-sm font-semibold text-gray-700">Información Personal</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre(s) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">person</span>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Juan Carlos"
                               class="!m-0 w-full !pl-12 pr-4 py-2 bg-gray-50 border @error('nombre') border-red-300 ring-red-200 @else border-gray-200 @enderror rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Apellido -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Apellido(s) <span class="text-red-500">*</span></label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}" required placeholder="Ej: Perez Mamani"
                           class="!m-0 w-full px-4 py-2 bg-gray-50 border @error('apellido') border-red-300 ring-red-200 @else border-gray-200 @enderror rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('apellido') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- CI -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Carnet de Identidad (CI) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">id_card</span>
                        <input type="text" name="ci" value="{{ old('ci') }}" required placeholder="Ej: 1234567 CBBA"
                               class="!m-0 w-full !pl-12 pr-4 py-2 bg-gray-50 border @error('ci') border-red-300 ring-red-200 @else border-gray-200 @enderror rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    @error('ci') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono / Celular <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">call</span>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" required placeholder="Ej: 71234567"
                               class="!m-0 w-full !pl-12 pr-4 py-2 bg-gray-50 border @error('telefono') border-red-300 ring-red-200 @else border-gray-200 @enderror rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    @error('telefono') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección de Domicilio <span class="text-gray-400 text-xs font-normal">(Opcional)</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">home_pin</span>
                        <input type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Ej: Av. Simón López esq. Washington Nro 123"
                               class="!m-0 w-full !pl-12 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bloque 2: Credenciales de Acceso Visual -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-blue-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-500 text-[20px]">vpn_key</span>
                <h3 class="text-sm font-semibold text-blue-900">Credenciales de Acceso Web</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/30">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">mail</span>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="padre@ejemplo.com" autocomplete="off"
                               class="!m-0 w-full !pl-12 pr-4 py-2 bg-white border @error('email') border-red-300 ring-red-200 @else border-gray-200 @enderror rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña Inicial <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">lock</span>
                        <input type="password" name="password" required placeholder="Ingresar contraseña..." autocomplete="new-password"
                               class="!m-0 w-full !pl-12 pr-10 py-2 bg-white border @error('password') border-red-300 ring-red-200 @else border-gray-200 @enderror rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="pwd_input">
                        
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors flex items-center justify-center p-1" onclick="togglePassword()">
                            <span class="material-symbols-outlined text-[18px]" id="pwd_icon">visibility</span>
                        </button>
                    </div>
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    <p class="text-[11px] text-gray-500 mt-1">Mínimo 6 caracteres. El padre podrá cambiarla después desde su panel.</p>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="pt-2 flex items-center justify-end gap-3 pb-8">
            <a href="{{ route('padres.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 shadow-sm transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">person_add</span>
                Registrar Tutor Legal
            </button>
        </div>
    </form>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('pwd_input');
        const icon = document.getElementById('pwd_icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endsection
