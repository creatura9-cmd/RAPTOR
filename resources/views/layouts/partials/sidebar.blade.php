{{-- Sidebar principal de RAPTOR (mismo estilo que el portal de clientes) --}}

@php
    // Foto de fondo: se prefiere public/images/portal-hero.jpg; si no existe, la misma imagen externa del portal.
    $heroFoto = file_exists(public_path('images/portal-hero.jpg'))
        ? asset('images/portal-hero.jpg')
        : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?q=80&w=2070&auto=format&fit=crop';

    // Módulos del menú (mismas rutas e iconos de siempre)
    $grupos = [
        [
            'key' => 'recepcion', 'label' => 'Recepción', 'icon' => 'clipboard',
            'patrones' => ['cliente.*', 'moto.*', 'inventario.*'],
            'items' => [
                ['Clientes',      'cliente.index',    'user',       'cliente.*'],
                ['Motocicletas',  'moto.index',       'motorcycle', 'moto.*'],
                ['Inventarios',   'inventario.index', 'list-check', 'inventario.*'],
            ],
        ],
        [
            'key' => 'taller', 'label' => 'Taller', 'icon' => 'cog',
            'patrones' => ['diagnostico.*', 'preorden.*', 'orden_trabajo.*'],
            'items' => [
                ['Diagnósticos',       'diagnostico.index',   'square-mouse-pointer', 'diagnostico.*'],
                ['Preórdenes',         'preorden.index',      'alarm-clock',          'preorden.*'],
                ['Órdenes de trabajo', 'orden_trabajo.index', 'file-cog',             'orden_trabajo.*'],
            ],
        ],
        [
            'key' => 'seguimiento', 'label' => 'Seguimiento', 'icon' => 'grid-2x2-check',
            'patrones' => ['pago.*', 'entrega.*', 'historial.*'],
            'items' => [
                ['Pagos',     'pago.index',    'hand-coins',           'pago.*'],
                ['Entregas',  'entrega.index', 'square-arrow-up-right', 'entrega.*'],
                ['Historial', 'historial.index', 'calendar-fold', 'historial.*'],
            ],
        ],
        [
            'key' => 'administracion', 'label' => 'Administración', 'icon' => 'shield-user',
            'patrones' => ['mecanico.*', 'repuesto.*', 'marca_moto.*'],
            'items' => [
                ['Mecánicos',      'mecanico.index',   'user-cog',  'mecanico.*'],
                ['Repuestos',      'repuesto.index',   'tool-case', 'repuesto.*'],
                ['Marcas de moto', 'marca_moto.index', 'shield',    'marca_moto.*'],
            ],
        ],
    ];

    $inicioActivo = request()->routeIs('dashboard.*') && ! request()->filled('seccion');
@endphp

<style>
    .raptor-aside {
        --r-yellow: #f5b01b;
        overflow: hidden;
        isolation: isolate;
        background: #081f2f;
        border-right: 4px solid var(--r-yellow);
    }
    /* Foto de la moto como fondo del menú */
    .raptor-aside .aside-foto {
        position: absolute; z-index: -3; inset: 0; width: 100%; height: 100%;
        object-fit: cover; object-position: 62% center; opacity: .5;
    }
    /* Velo oscuro: el menú se lee bien y la moto se asoma hacia abajo */
    .raptor-aside .aside-velo {
        position: absolute; z-index: -2; inset: 0;
        background: linear-gradient(180deg, rgb(8 31 47 / 96%) 0%, rgb(8 31 47 / 82%) 45%, rgb(8 31 47 / 55%) 100%);
    }

    .raptor-aside .nav-link {
        display: flex; align-items: center; gap: .85rem; width: 100%;
        padding: .65rem 1rem; border-radius: .6rem; text-align: left;
        font-size: .875rem; font-weight: 500; color: #d3e0ea;
        border-left: 4px solid transparent;
        transition: background-color .15s ease, color .15s ease;
    }
    .raptor-aside .nav-link:hover { background: rgb(255 255 255 / 8%); color: #fff; }
    .raptor-aside .nav-link.activo {
        background: rgb(255 255 255 / 10%); border-left-color: var(--r-yellow);
        color: var(--r-yellow); font-weight: 700;
    }
    .raptor-aside .nav-sub { padding: .5rem 1rem; font-size: .8125rem; }
    .raptor-aside .nav-link.inactivo { opacity: .45; cursor: not-allowed; }
    .raptor-aside .nav-link.inactivo:hover { background: transparent; color: #d3e0ea; }

    .raptor-aside .nav-toggle {
        padding: .5rem; border-radius: .5rem; color: #d3e0ea;
    }
    .raptor-aside .nav-toggle:hover { background: rgb(255 255 255 / 8%); color: #fff; }

    .raptor-aside a:focus-visible, .raptor-aside button:focus-visible {
        outline: 3px solid rgb(245 176 27 / 75%); outline-offset: 2px;
    }
    @media (prefers-reduced-motion: reduce) {
        .raptor-aside, .raptor-aside * { transition-duration: .01ms !important; }
    }
</style>

<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="raptor-aside fixed inset-y-0 left-0 z-40 w-64 transform transition-transform duration-200 ease-in-out lg:translate-x-0"
    aria-label="Menú principal"
>
    <img src="{{ $heroFoto }}" alt="" class="aside-foto" aria-hidden="true">
    <div class="aside-velo" aria-hidden="true"></div>

    <div class="relative flex h-full flex-col px-3 py-6">

        {{-- Logo --}}
        <div class="px-2 pb-5 text-center">
            <img src="{{ asset('images/logoRAPTOR.png') }}" alt="Logo de RAPTOR" class="mx-auto h-24 w-auto object-contain">
            <p class="mt-2 text-2xl font-black italic tracking-wide text-white">RAPTOR</p>
            <p class="mt-1 text-[11px] italic tracking-[0.08em] text-slate-300/90">Tu moto al día</p>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 space-y-1 overflow-y-auto" aria-label="Navegación lateral">

            {{-- INICIO --}}
            <a href="{{ route('dashboard.index') }}"
               @if ($inicioActivo) aria-current="page" @endif
               class="nav-link {{ $inicioActivo ? 'activo' : '' }}">
                <span class="h-5 w-5 flex-shrink-0" aria-hidden="true">
                    @include('layouts.partials.icons', ['icon' => 'home'])
                </span>
                <span>Inicio</span>
            </a>

            {{-- MÓDULOS --}}
            @foreach ($grupos as $g)
                @php
                    $hijoActivo    = collect($g['patrones'])->contains(fn ($p) => request()->routeIs($p));
                    $resumenActivo = request()->routeIs('dashboard.*') && request('seccion') === $g['key'];
                    $grupoActivo   = $hijoActivo || $resumenActivo;
                @endphp

                <div x-data="{ open: {{ $grupoActivo ? 'true' : 'false' }} }" class="pt-1">

                    <div class="flex items-center gap-1">
                        {{-- Abrir el resumen del módulo --}}
                        <a href="{{ route('dashboard.index', ['seccion' => $g['key']]) }}"
                           @if ($resumenActivo) aria-current="page" @endif
                           class="nav-link flex-1 {{ $grupoActivo ? 'activo' : '' }}">
                            <span class="h-5 w-5 flex-shrink-0" aria-hidden="true">
                                @include('layouts.partials.icons', ['icon' => $g['icon']])
                            </span>
                            <span>{{ $g['label'] }}</span>
                        </a>

                        {{-- Desplegar o contraer submódulos --}}
                        <button type="button"
                                @click="open = !open"
                                :aria-expanded="open.toString()"
                                aria-label="Desplegar menú de {{ $g['label'] }}"
                                class="nav-toggle">
                            <svg class="h-4 w-4 transition-transform duration-200"
                                 :class="{ 'rotate-180': open }"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open" x-transition class="ml-3 mt-1 space-y-1">
                        @foreach ($g['items'] as [$etiqueta, $ruta, $icono, $patron])
                            @if ($ruta)
                                @php $activo = request()->routeIs($patron); @endphp
                                <a href="{{ route($ruta) }}"
                                   @if ($activo) aria-current="page" @endif
                                   class="nav-link nav-sub {{ $activo ? 'activo' : '' }}">
                                    <span class="h-5 w-5 flex-shrink-0" aria-hidden="true">
                                        @include('layouts.partials.icons', ['icon' => $icono])
                                    </span>
                                    <span>{{ $etiqueta }}</span>
                                </a>
                            @else
                                <span class="nav-link nav-sub inactivo" aria-disabled="true">
                                    <span class="h-5 w-5 flex-shrink-0" aria-hidden="true">
                                        @include('layouts.partials.icons', ['icon' => $icono])
                                    </span>
                                    <span>{{ $etiqueta }}</span>
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        {{-- Cerrar sesión --}}
        <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}" class="mt-4">
            @csrf
            <button type="submit" class="nav-link" aria-label="Cerrar sesión">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9V5.5A2.5 2.5 0 0 0 12.5 3h-5A2.5 2.5 0 0 0 5 5.5v13A2.5 2.5 0 0 0 7.5 21h5a2.5 2.5 0 0 0 2.5-2.5V15m3-6 3 3m0 0-3 3m3-3H10"/>
                </svg>
                Cerrar sesión
            </button>
        </form>

    </div>
</aside>