<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Sistema Tiquipaya - @yield('titulo')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <!-- Google Fonts: Public Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "surface": "#f8f9fd", "primary": "#0d631b", "inverse-on-surface": "#eff1f5", "secondary-fixed": "#94f990",
              "on-error-container": "#93000a", "on-surface": "#191c1f", "inverse-surface": "#2e3134", "surface-container-highest": "#e1e2e6",
              "on-tertiary-container": "#ffedf0", "secondary-fixed-dim": "#78dc77", "error": "#ba1a1a", "surface-variant": "#e1e2e6",
              "secondary": "#006e1c", "on-tertiary": "#ffffff", "surface-container-lowest": "#ffffff", "surface-dim": "#d9dade",
              "on-tertiary-fixed-variant": "#7f2448", "primary-fixed-dim": "#88d982", "outline": "#707a6c", "on-primary-fixed-variant": "#005312",
              "tertiary": "#923357", "on-surface-variant": "#40493d", "on-background": "#191c1f", "primary-fixed": "#a3f69c",
              "on-secondary": "#ffffff", "on-tertiary-fixed": "#3f001c", "background": "#f8f9fd", "error-container": "#ffdad6",
              "on-primary-container": "#cbffc2", "surface-container-high": "#e7e8ec", "outline-variant": "#bfcaba", "primary-container": "#2e7d32",
              "surface-bright": "#f8f9fd", "tertiary-container": "#b14b6f", "on-primary-fixed": "#002204", "on-secondary-fixed-variant": "#005313",
              "tertiary-fixed-dim": "#ffb1c7", "surface-container-low": "#f2f3f7", "on-secondary-fixed": "#002204", "surface-tint": "#1b6d24",
              "tertiary-fixed": "#ffd9e2", "secondary-container": "#91f78e", "on-error": "#ffffff", "on-primary": "#ffffff",
              "surface-container": "#edeef2", "on-secondary-container": "#00731e", "inverse-primary": "#88d982"
            },
            fontFamily: { "headline": ["Public Sans"], "body": ["Public Sans"], "label": ["Public Sans"] },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Public Sans', sans-serif; background-color: #f8f9fd; min-height: max(884px, 100dvh); }
        .glass-panel { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        
        /* LEGACY STYLES SCOPED TO MAINTAIN UI COMPATIBILITY FOR NON-TAILWIND VIEWS */
        .legacy-view table { width: 100%; border-collapse: collapse; margin: 15px 0; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .legacy-view th, .legacy-view td { border-bottom: 1px solid #e2e8f0; padding: 12px 15px; text-align: left; }
        .legacy-view th { background-color: #1a5276; color: white; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; }
        .legacy-view tr:last-child td { border-bottom: none; }
        .legacy-view input[type="text"], .legacy-view input[type="number"], .legacy-view input[type="email"], .legacy-view input[type="password"], .legacy-view select, .legacy-view textarea { padding: 10px; margin: 5px 0; width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        .legacy-view .btn { padding: 8px 16px; background-color: #1a5276; color: white; border: none; cursor: pointer; margin: 5px; border-radius: 6px; font-weight: 600; transition: background 0.2s; text-decoration: none; display: inline-block; }
        .legacy-view .btn:hover { background-color: #0f3a5e; }
        .legacy-view h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: #1e293b; }
        .alert { padding: 12px 16px; margin-bottom: 20px; border-radius: 8px; font-weight: 500; }
        .alert-success { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body class="text-on-surface">

<!-- TopAppBar -->
<header class="fixed top-0 w-full flex justify-between items-center px-6 h-16 bg-[#2e7d32] dark:bg-green-900 text-white dark:text-emerald-50 z-50 no-border shadow-[0_24px_24px_rgba(13,99,27,0.06)]">
    <div class="flex items-center gap-4">
        <div class="md:hidden">
            <span class="material-symbols-outlined text-white">menu</span>
        </div>
        <h1 class="font-['Public_Sans'] font-bold text-lg tracking-tight">U.E. Modelo Tiquipaya</h1>
    </div>
    <div class="flex items-center gap-4">
        <!-- ESPACIO PARA ESCUDO DEL COLEGIO A LA DERECHA SUPERIOR -->
        <div class="hidden sm:flex items-center justify-center w-10 h-10 bg-white/20 rounded-full p-1 border border-white/40" title="Escudo Tiquipaya">
            <span class="material-symbols-outlined text-white text-2xl">shield</span>
        </div>
        
        <span class="hidden md:block text-sm font-medium opacity-90">Gestión Académica {{ date('Y') }}</span>
        
        <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
            @csrf
            <button type="submit" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-colors border-0 cursor-pointer text-white">
                Cerrar Sesión
            </button>
        </form>
    </div>
</header>

<!-- NavigationDrawer (Sidebar) -->
<aside class="hidden md:flex fixed left-0 top-16 h-[calc(100vh-64px)] w-64 bg-[#f2f3f7] dark:bg-slate-800 flex-col py-6 overflow-y-auto">
    <div class="px-6 mb-8 flex items-center gap-3">
        <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center overflow-hidden border-2 border-white text-white">
            <span class="material-symbols-outlined text-3xl">person</span>
        </div>
        <div>
            <p class="font-bold text-sm text-on-surface">{{ auth()->user()->name }}</p>
            <p class="text-xs text-on-surface-variant uppercase">{{ auth()->user()->role }}</p>
        </div>
    </div>
    <nav class="flex-1 px-4 space-y-1">
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'secretaria')
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-white dark:bg-slate-800 text-[#2e7d32] dark:text-emerald-400 font-bold rounded-l-full shadow-sm' : 'text-[#40493d] dark:text-slate-400 hover:bg-[#f2f3f7] dark:hover:bg-slate-800 hover:translate-x-1' }} transition-transform duration-200" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-['Public_Sans'] text-sm font-medium">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('estudiantes.*') ? 'bg-white dark:bg-slate-800 text-[#2e7d32] font-bold rounded-l-full shadow-sm' : 'text-[#40493d] hover:translate-x-1' }} transition-transform duration-200" href="{{ route('estudiantes.index') }}">
                <span class="material-symbols-outlined">school</span>
                <span class="font-['Public_Sans'] text-sm font-medium">Estudiantes</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('inscripciones.*') ? 'bg-white text-[#2e7d32] font-bold rounded-l-full shadow-sm' : 'text-[#40493d] hover:translate-x-1' }} transition-transform duration-200" href="{{ route('inscripciones.index') }}">
                <span class="material-symbols-outlined">app_registration</span>
                <span class="font-['Public_Sans'] text-sm font-medium">Inscripciones</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('reservas.*') ? 'bg-white text-[#2e7d32] font-bold rounded-l-full shadow-sm' : 'text-[#40493d] hover:translate-x-1' }} transition-transform duration-200" href="{{ route('reservas.index') }}">
                <span class="material-symbols-outlined">event_seat</span>
                <span class="font-['Public_Sans'] text-sm font-medium">Reservas de Cupo</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('padres.*') ? 'bg-white text-[#2e7d32] font-bold rounded-l-full shadow-sm' : 'text-[#40493d] hover:translate-x-1' }} transition-transform duration-200" href="{{ route('padres.index') }}">
                <span class="material-symbols-outlined">family_restroom</span>
                <span class="font-['Public_Sans'] text-sm font-medium">Padres/Tutores</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('notificaciones.*') ? 'bg-white text-[#2e7d32] font-bold rounded-l-full shadow-sm' : 'text-[#40493d] hover:translate-x-1' }} transition-transform duration-200" href="{{ route('notificaciones.crear') }}">
                <span class="material-symbols-outlined">notifications</span>
                <span class="font-['Public_Sans'] text-sm font-medium">Enviar Notificación</span>
            </a>
        @elseif(auth()->user()->role === 'padre')
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('padre.dashboard') ? 'bg-white text-[#2e7d32] font-bold rounded-l-full shadow-sm' : 'text-[#40493d] hover:translate-x-1' }} transition-transform duration-200" href="{{ route('padre.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-['Public_Sans'] text-sm font-medium">Mi Panel</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('notificaciones.index') ? 'bg-white text-[#2e7d32] font-bold rounded-l-full shadow-sm' : 'text-[#40493d] hover:translate-x-1' }} transition-transform duration-200" href="{{ route('notificaciones.index') }}">
                <span class="material-symbols-outlined">notifications</span>
                <span class="font-['Public_Sans'] text-sm font-medium">Mis Notificaciones</span>
            </a>
        @elseif(auth()->user()->role === 'docente')
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('docente.dashboard') ? 'bg-white text-[#2e7d32] font-bold rounded-l-full shadow-sm' : 'text-[#40493d] hover:translate-x-1' }} transition-transform duration-200" href="{{ route('docente.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-['Public_Sans'] text-sm font-medium">Dashboard Docente</span>
            </a>
        @endif
    </nav>
</aside>

<!-- Main Content Canvas -->
<main class="md:ml-64 pt-24 pb-20 px-4 md:px-8 min-h-screen flex flex-col transition-all duration-300">
    <div class="max-w-7xl mx-auto w-full legacy-view flex-[1]">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer Global -->
    <footer class="mt-8 pt-6 pb-2 text-center text-sm font-medium text-gray-500 border-t border-gray-200/50">
        &copy; 2002 - {{ date('Y') }} U.E. Modelo Tiquipaya. Todos los derechos reservados.
    </footer>
</main>

<!-- Bottom Navigation for Mobile -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-white dark:bg-slate-900 flex justify-around items-center z-50 shadow-[0_-4px_12px_rgba(0,0,0,0.05)] px-2">
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'secretaria')
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-[#2e7d32]' : 'text-on-surface-variant' }}" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-[10px] font-bold">Inicio</span>
        </a>
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('estudiantes.*') ? 'text-[#2e7d32]' : 'text-on-surface-variant' }}" href="{{ route('estudiantes.index') }}">
            <span class="material-symbols-outlined">school</span>
            <span class="text-[10px] font-medium">Alumnos</span>
        </a>
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('inscripciones.*') ? 'text-[#2e7d32]' : 'text-on-surface-variant' }}" href="{{ route('inscripciones.index') }}">
            <span class="material-symbols-outlined">app_registration</span>
            <span class="text-[10px] font-medium">Inscribir</span>
        </a>
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('padres.*') ? 'text-[#2e7d32]' : 'text-on-surface-variant' }}" href="{{ route('padres.index') }}">
            <span class="material-symbols-outlined">family_restroom</span>
            <span class="text-[10px] font-medium">Padres</span>
        </a>
    @endif
</nav>

</body>
</html>
