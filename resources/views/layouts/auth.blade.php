<!DOCTYPE html>
<html lang="es" class="h-full bg-primary-50">

<head>
    @include('components.favicon')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | RAPTOR</title>
    
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f5f7',
                            100: '#dce8ed',
                            200: '#bfd2dc',
                            300: '#9bb8c6',
                            400: '#6f96aa',
                            500: '#315A72',
                            600: '#294c61',
                            700: '#223f50',
                            800: '#1b323f',
                            900: '#14252f',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js: permite el funcionamiento de los modales y las interacciones --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="flex min-h-full items-center justify-center p-4 text-slate-800 antialiased">

    <main class="w-full max-w-md">
        {{-- Mensaje de operación exitosa --}}
        @if (session('success'))
            <div
                role="status"
                class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800"
            >
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensaje de error --}}
        @if (session('error'))
            <div
                role="alert"
                class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"
            >
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>

</html>