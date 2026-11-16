<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">

<head>
    @include('components.favicon')

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Dashboard') - {{ config('app.name', 'Admin') }}
    </title>

    {{-- Tailwind CSS --}}
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

                    },

                    fontFamily: {

                        sans: [
                            'Inter',
                            'ui-sans-serif',
                            'system-ui',
                            'sans-serif'
                        ]

                    }

                }

            }

        }

    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Chart.js --}}
    <script
        src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"
        defer
    ></script>

    <style>

        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

    </style>

    @stack('styles')

</head>

<body class="h-full text-slate-800 antialiased">

    <div
        x-data="{ sidebarOpen: false }"
        class="min-h-screen flex"
    >

        {{-- Overlay móvil --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
            aria-hidden="true"
        ></div>

        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

            @include('layouts.partials.navbar')

            <main class="flex-1 p-4 sm:p-6 lg:p-8">

                {{-- Mensajes manejados por el layout --}}
                <div id="layout-alerts" class="hidden">

                    {{-- Crear --}}
                    @if (session('success'))

                        <x-alert
                            type="success"
                            class="mb-6"
                        >
                            {{ session('success') }}
                        </x-alert>

                    @endif

                    {{-- Editar --}}
                    @if (session('update'))

                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                            {{ session('update') }}
                        </div>

                    @endif

                    {{-- Eliminar --}}
                    @if (session('destroy'))

                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            {{ session('destroy') }}
                        </div>

                    @endif

                    {{-- Error --}}
                    @if (session('error'))

                        <x-alert
                            type="error"
                            class="mb-6"
                        >
                            {{ session('error') }}
                        </x-alert>

                    @endif

                </div>

                @yield('content')

            </main>

            @include('layouts.partials.footer')

        </div>

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"
        defer
    ></script>

    {{-- Mover los mensajes del layout al lugar indicado por cada vista --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const layoutAlerts = document.getElementById('layout-alerts');

            const alertsContainer = document.getElementById('alerts-container');

            if (layoutAlerts) {

                if (alertsContainer) {

                    while (layoutAlerts.firstElementChild) {

                        alertsContainer.appendChild(
                            layoutAlerts.firstElementChild
                        );

                    }

                } else {

                    layoutAlerts.classList.remove('hidden');

                }

            }

        });

    </script>

    @stack('scripts')

</body>

</html>