@extends('layouts.guest')
@section('titulo', 'Iniciar Sesión')

@section('content')
<div class="w-full max-w-md bg-surface-container-lowest rounded-xl shadow-xl shadow-primary/5 border border-outline-variant/10 overflow-hidden transition-all duration-300">
    <!-- Branding Header -->
    <div class="px-8 pt-10 pb-6 text-center">
        <div class="flex flex-col items-center gap-4">
            <!-- School Crest Placeholder -->
            <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center shadow-md">
                <span class="material-symbols-outlined text-on-primary-container" style="font-variation-settings: 'FILL' 1;">school</span>
            </div>
            <span class="text-xl font-bold text-primary tracking-tight font-headline">U.E. Modelo Tiquipaya</span>
        </div>
    </div>
    
    <!-- Login Content -->
    <div class="px-8 pb-10">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-extrabold text-on-surface mb-2 font-headline tracking-tight">Iniciar Sesión</h1>
            <p class="text-on-surface-variant text-md">Bienvenido de nuevo. Por favor, ingresa tus credenciales.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-error-container text-on-error-container border border-error/20 flex flex-col gap-1 shadow-sm">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-error">error</span>
                        <span class="text-sm font-semibold">{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif
        
        <form class="space-y-6" method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface-variant ml-1" for="email">Correo Electrónico Administrador / Padre</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-outline">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                    </div>
                    <input class="block w-full pl-11 pr-4 py-4 bg-surface-container-high border-none rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest text-on-surface placeholder:text-outline/60 transition-all duration-200" id="email" name="email" placeholder="ejemplo@modelo.edu.bo" type="email" required autofocus/>
                </div>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between items-end ml-1">
                    <label class="block text-sm font-semibold text-on-surface-variant" for="password">Contraseña</label>
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-outline">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                    </div>
                    <input class="block w-full pl-11 pr-4 py-4 bg-surface-container-high border-none rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest text-on-surface placeholder:text-outline/60 transition-all duration-200" id="password" name="password" placeholder="••••••••" type="password" required/>
                </div>
            </div>
            
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center">
                    <input class="h-5 w-5 text-primary focus:ring-primary/30 border-outline-variant rounded-md transition-colors cursor-pointer" id="remember-me" name="remember-me" type="checkbox"/>
                    <label class="ml-3 block text-sm text-on-surface-variant cursor-pointer font-medium" for="remember-me">Recordar cuenta</label>
                </div>
                <div class="text-sm">
                    <a class="font-semibold text-primary hover:text-primary-container transition-colors duration-200" href="#">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
            
            <div class="pt-4">
                <button class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg shadow-primary/20 text-lg font-bold text-on-primary bg-primary hover:bg-primary-container active:scale-[0.98] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" type="submit">
                    Ingresar
                    <span class="material-symbols-outlined ml-2 text-[20px]">login</span>
                </button>
            </div>
            
            <div class="mt-4 bg-surface-container-low p-4 rounded-xl border border-dashed border-outline-variant/30 text-center">
                <p class="text-xs text-on-surface-variant font-medium mb-1">Cuentas de prueba (dev):</p>
                <div class="flex justify-center gap-4 text-xs font-bold text-primary">
                    <span>admin@test.com</span>
                    <span>padre@test.com</span>
                </div>
            </div>
        </form>
        
        <div class="mt-8 pt-8 border-t border-outline-variant/10 text-center">
            <p class="text-xs text-outline/70 font-medium uppercase tracking-widest">Excelencia Académica y Formación Humana</p>
        </div>
    </div>
</div>
@endsection
