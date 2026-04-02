@extends('layouts.guest')
@section('titulo', 'Reserva Exitosa')

@section('content')
<div class="w-full max-w-lg bg-white rounded-[2rem] shadow-2xl border border-gray-100 overflow-hidden text-center py-16 px-8 sm:px-12 relative transition-all duration-300">
    
    <!-- Background glowing accent directly from requested gradient style -->
    <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-green-700/10 to-transparent pointer-events-none"></div>

    <div class="relative z-10 w-28 h-28 bg-white border-[6px] border-green-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-xl shadow-green-900/10 transform hover:scale-105 transition-transform duration-300">
        <span class="material-symbols-outlined text-6xl text-green-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
    </div>
    
    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-5 font-['Public_Sans'] tracking-tight">¡Solicitud Exitosa!</h1>
    
    <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100/50 mb-10 shadow-inner">
        <p class="text-blue-900/80 font-bold text-[15px] leading-relaxed">
            La pre-inscripción académica de sus estudiantes ha sido registrada de forma segura en nuestro engranaje central. 
            El departamento de secretaría de la Institución validará su solicitud y se pondrá en contacto pronto.
        </p>
    </div>
    
    <div class="space-y-4 relative z-10 flex flex-col pt-2 border-t border-gray-100">
        <a href="{{ route('login') }}" class="w-full flex justify-center items-center py-5 px-6 rounded-xl shadow-lg shadow-green-900/20 text-lg font-bold text-white hover:scale-[1.02] active:scale-95 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-green-600/30" style="background: linear-gradient(135deg, #022c22 0%, #065f46 50%, #047857 100%);">
            Volver al Inicio
        </a>
        
        <a href="{{ route('reservas.create') }}" class="w-full flex justify-center items-center py-4 px-6 rounded-xl border-2 border-gray-200 text-gray-600 hover:text-green-700 hover:border-green-600/50 hover:bg-green-50 font-black tracking-wide uppercase text-sm transition-all duration-200 group">
            Realizar Otra Reserva
            <span class="material-symbols-outlined ml-2 text-xl text-gray-400 group-hover:text-green-600 transition-colors">add_circle</span>
        </a>
    </div>
</div>
@endsection
