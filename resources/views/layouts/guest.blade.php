<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('titulo', 'U.E. Modelo Tiquipaya')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- MDI & Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-dim": "#d9dade", "surface-tint": "#1b6d24", "on-primary-fixed": "#002204", "on-secondary-fixed": "#002204",
                        "on-error": "#ffffff", "surface": "#f8f9fd", "on-secondary-container": "#00731e", "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed-variant": "#7f2448", "error-container": "#ffdad6", "surface-container-low": "#f2f3f7",
                        "secondary-container": "#91f78e", "primary": "#0d631b", "secondary-fixed-dim": "#78dc77", "on-surface-variant": "#40493d",
                        "secondary": "#006e1c", "on-primary": "#ffffff", "surface-container": "#edeef2", "outline": "#707a6c",
                        "secondary-fixed": "#94f990", "inverse-primary": "#88d982", "surface-variant": "#e1e2e6", "on-secondary": "#ffffff",
                        "on-tertiary-container": "#ffedf0", "on-surface": "#191c1f", "error": "#ba1a1a", "tertiary": "#923357",
                        "surface-container-highest": "#e1e2e6", "primary-fixed": "#a3f69c", "on-tertiary-fixed": "#3f001c",
                        "tertiary-fixed-dim": "#ffb1c7", "primary-container": "#2e7d32", "on-error-container": "#93000a",
                        "inverse-surface": "#2e3134", "outline-variant": "#bfcaba", "on-background": "#191c1f", "tertiary-container": "#b14b6f",
                        "on-secondary-fixed-variant": "#005313", "surface-bright": "#f8f9fd", "inverse-on-surface": "#eff1f5",
                        "tertiary-fixed": "#ffd9e2", "primary-fixed-dim": "#88d982", "surface-container-high": "#e7e8ec",
                        "on-primary-container": "#cbffc2", "background": "#f8f9fd", "on-tertiary": "#ffffff", "on-primary-fixed-variant": "#005312"
                    },
                    fontFamily: {
                        "headline": ["Public Sans"],
                        "body": ["Public Sans"],
                        "label": ["Public Sans"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "1.5rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .bg-pattern {
            background-color: #f8f9fd;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230d631b' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="font-body bg-pattern text-on-background min-h-screen flex flex-col">

    <!-- Top Navigation Suppression: Not rendered for transactional login screen as per Shell Visibility rules -->
    <main class="flex-grow flex items-center justify-center p-4 sm:p-12">
        @yield('content')
    </main>

    <!-- Shared Component: Footer -->
    <footer class="bg-slate-50 dark:bg-slate-900 w-full py-8 tonal-shift bg-surface-container-low mt-auto border-t border-outline-variant/20">
        <div class="flex flex-col md:flex-row justify-between items-center px-8 max-w-7xl mx-auto w-full">
            <div class="mb-4 md:mb-0 text-center md:text-left">
                <p class="font-public-sans text-sm text-slate-500 font-medium">© {{ date('Y') }} U.E. Modelo Tiquipaya. Todos los derechos reservados.</p>
            </div>
            <div class="flex space-x-6">
                <a class="font-public-sans text-sm font-semibold text-primary hover:text-primary-container transition-colors duration-200" href="#">Portal Web</a>
                <a class="font-public-sans text-sm font-semibold text-primary hover:text-primary-container transition-colors duration-200" href="#">Soporte</a>
                @if(request()->routeIs('login'))
                    <a class="font-public-sans text-sm font-semibold text-secondary hover:text-secondary-container transition-colors duration-200" href="{{ route('reservas.create') }}">Pre-Inscripción</a>
                @else
                    <a class="font-public-sans text-sm font-semibold text-secondary hover:text-secondary-container transition-colors duration-200" href="{{ route('login') }}">Ingresar al Sistema</a>
                @endif
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
