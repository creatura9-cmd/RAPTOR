@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('content')

<style>
    :root {
        --raptor-blue: #315A72;
        --raptor-dark: #193447;
        --raptor-yellow: #facc15;
    }

    /* CONFIGURACIÓN GENERAL */
    body:has(#login-raptor) {
        display: block;
        padding: 0;
        margin: 0;
        background: #050b11;
    }

    body:has(#login-raptor) main:has(#login-raptor) {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: none;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    /* PANTALLA PRINCIPAL */
    #login-raptor {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 100vh;
        min-height: 100dvh;
        padding: 40px 30px;
        overflow: hidden;
        font-family: Arial, Helvetica, sans-serif;
        background: #050b11;
    }

    /* FONDO */
    #login-raptor .background {
        position: absolute;
        inset: 0;
        background: url('{{ asset('images/fondo-taller.jpg') }}')
            center / cover no-repeat;
        transform: scale(1.02);
    }

    /* OSCURECIMIENTO */
    #login-raptor .overlay {
        position: absolute;
        inset: 0;
        background: rgba(3, 9, 15, .68);
    }

    /* CONTENEDOR PRINCIPAL */
    #login-raptor .main-content {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 420px;
        align-items: center;
        gap: 55px;
        width: 100%;
        max-width: 900px;
        margin: auto;
    }

    /* =====================================
       LADO IZQUIERDO: BIENVENIDA
    ===================================== */

    #login-raptor .welcome-section {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        color: white;
        animation: aparecer .8s ease-out both;
    }

    #login-raptor .welcome-label {
        margin-bottom: 18px;
        color: var(--raptor-yellow);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 4px;
        text-transform: uppercase;
    }

    #login-raptor .welcome-title {
        margin: 0;
        color: white;
        font-size: clamp(42px, 5vw, 68px);
        font-weight: 900;
        line-height: 1.15;
        letter-spacing: 1.5px;
        word-spacing: 5px;
        text-shadow: 0 4px 20px rgba(0,0,0,.4);
    }

    #login-raptor .welcome-title span {
        display: block;
        color: #a9c9dc;
        letter-spacing: 2.5px;
    }

    #login-raptor .welcome-divider {
        width: 85px;
        height: 4px;
        margin: 27px 0;
        border-radius: 5px;
        background: var(--raptor-yellow);
    }

    #login-raptor .welcome-description {
        max-width: 420px;
        margin: 0;
        color: rgba(255,255,255,.88);
        font-size: 15px;
        line-height: 1.9;
    }

    /* =====================================
       LADO DERECHO: FORMULARIO
    ===================================== */

    #login-raptor .login-section {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        transform: translateX(5px);
        animation: aparecer .9s ease-out both;
    }

    /* TARJETA BLANCA */
    #login-raptor .login-card {
        width: 100%;
        padding: 42px 38px;
        border: 1px solid rgba(255,255,255,.8);
        border-radius: 15px;
        background: #ffffff;
        color: #193447;
        box-shadow: 0 20px 60px rgba(0,0,0,.35);
    }

    /* LOGO */
    #login-raptor .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 22px;
    }

    #login-raptor .logo {
        display: block;
        width: 175px;
        max-width: 80%;
        height: auto;
        max-height: 140px;
        object-fit: contain;
    }

    /* TÍTULO */
    #login-raptor .login-title {
        margin: 0 0 10px;
        color: var(--raptor-dark);
        font-size: 29px;
        font-weight: 800;
        text-align: center;
    }

    #login-raptor .login-subtitle {
        margin-bottom: 32px;
        color: #64748b;
        font-size: 13px;
        text-align: center;
    }

    /* ERRORES */
    #login-raptor .error-container {
        margin-bottom: 20px;
        padding: 12px;
        border: 1px solid #fecaca;
        border-radius: 7px;
        background: #fef2f2;
        color: #b91c1c;
        font-size: 12px;
    }

    /* CAMPOS */
    #login-raptor .form-group {
        margin-bottom: 22px;
    }

    #login-raptor .form-group label {
        display: block;
        margin-bottom: 9px;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
    }

    #login-raptor .form-input {
        display: block;
        width: 100%;
        height: 46px;
        padding: 0 13px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        outline: none;
        background: #ffffff;
        color: #193447;
        font-size: 14px;
        transition: .25s;
    }

    #login-raptor .form-input:focus {
        border-color: var(--raptor-blue);
        box-shadow: 0 0 0 3px rgba(49,90,114,.15);
    }

    #login-raptor .form-input::placeholder {
        color: #94a3b8;
    }

    /* RECORDARME */
    #login-raptor .remember-container {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 3px 0 25px;
        color: #64748b;
        font-size: 12px;
        cursor: pointer;
    }

    #login-raptor .remember-container input {
        width: 15px;
        height: 15px;
        accent-color: var(--raptor-blue);
        cursor: pointer;
    }

    /* BOTÓN */
    #login-raptor .login-button {
        display: block;
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 6px;
        background: linear-gradient(135deg, #315A72, #1c4057);
        color: white;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .5px;
        cursor: pointer;
        transition: .25s;
    }

    #login-raptor .login-button:hover {
        background: linear-gradient(135deg, #416f8a, #315A72);
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(49,90,114,.25);
    }

    #login-raptor .login-button:focus-visible {
        outline: 2px solid var(--raptor-yellow);
        outline-offset: 4px;
    }

    /* RECUPERAR CONTRASEÑA */
    #login-raptor .forgot-password {
        display: block;
        margin-top: 24px;
        color: var(--raptor-blue);
        font-size: 12px;
        text-align: center;
        text-decoration: none;
        transition: .2s;
    }

    #login-raptor .forgot-password:hover {
        color: var(--raptor-dark);
        text-decoration: underline;
    }

    /* =====================================
       ANIMACIÓN
    ===================================== */

    @keyframes aparecer {
        from {
            opacity: 0;
            transform: translateY(18px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =====================================
       TABLETAS
    ===================================== */

    @media (max-width: 950px) {
        #login-raptor .main-content {
            grid-template-columns: minmax(0, 1fr) 370px;
            gap: 45px;
        }

        #login-raptor .login-section {
            transform: none;
        }

        #login-raptor .login-card {
            padding: 35px 28px;
        }

        #login-raptor .welcome-title {
            font-size: 43px;
        }

        #login-raptor .welcome-description {
            font-size: 13px;
        }
    }

    /* =====================================
       CELULARES
    ===================================== */

    @media (max-width: 650px) {
        #login-raptor {
            padding: 35px 18px;
            overflow-y: auto;
        }

        #login-raptor .main-content {
            display: flex;
            flex-direction: column;
            gap: 35px;
            max-width: 420px;
        }

        #login-raptor .welcome-section {
            order: 1;
            align-items: center;
            text-align: center;
        }

        #login-raptor .welcome-label {
            margin-bottom: 10px;
            font-size: 10px;
        }

        #login-raptor .welcome-title {
            font-size: 36px;
        }

        #login-raptor .welcome-divider {
            margin: 18px 0;
        }

        #login-raptor .welcome-description {
            font-size: 12px;
        }

        #login-raptor .login-section {
            order: 2;
            transform: none;
        }

        #login-raptor .login-card {
            padding: 32px 23px;
        }

        #login-raptor .logo {
            width: 145px;
            max-height: 115px;
        }

        #login-raptor .login-title {
            font-size: 27px;
        }
    }

    /* PANTALLAS BAJAS */
    @media (max-height: 650px) and (min-width: 651px) {
        #login-raptor {
            padding-top: 25px;
            padding-bottom: 25px;
        }

        #login-raptor .login-card {
            padding-top: 22px;
            padding-bottom: 22px;
        }

        #login-raptor .logo {
            width: 115px;
            max-height: 85px;
        }

        #login-raptor .logo-container {
            margin-bottom: 10px;
        }

        #login-raptor .login-subtitle {
            margin-bottom: 18px;
        }

        #login-raptor .welcome-title {
            font-size: 42px;
        }
    }

    /* ACCESIBILIDAD */
    @media (prefers-reduced-motion: reduce) {
        #login-raptor *,
        #login-raptor *::before,
        #login-raptor *::after {
            animation: none !important;
            transition: none !important;
        }
    }
</style>


<div id="login-raptor">

    {{-- FONDO --}}
    <div class="background"></div>
    <div class="overlay"></div>


    <div class="main-content">

        {{-- =================================
             LADO IZQUIERDO: BIENVENIDA
        ================================== --}}

        <section class="welcome-section">

            <span class="welcome-label">
                Servicio técnico para motos
            </span>

            <h1 class="welcome-title">
                Bienvenido a
                <span>RAPTOR</span>
            </h1>

            <div class="welcome-divider"></div>

            

        </section>


        {{-- =================================
             LADO DERECHO: FORMULARIO
        ================================== --}}

        <section class="login-section">

            <div class="login-card">

                {{-- LOGO OFICIAL --}}
                <div class="logo-container">
                    <img
                        src="{{ asset('images/logoRAPTOR.png') }}"
                        alt="Logo oficial de RAPTOR"
                        class="logo"
                    >
                </div>

                {{-- TÍTULO --}}
                <h2 class="login-title">
                    Iniciar sesión
                </h2>

                <p class="login-subtitle">
                    Ingresa tus credenciales para continuar.
                </p>


                {{-- ERRORES --}}
                @if ($errors->any())
                    <div class="error-container">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif


                {{-- FORMULARIO --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- CORREO --}}
                    <div class="form-group">

                        <label for="email">
                            Correo electrónico
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Ingresa tu correo electrónico"
                            class="form-input"
                        >

                    </div>


                    {{-- CONTRASEÑA --}}
                    <div class="form-group">

                        <label for="password">
                            Contraseña
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="Ingresa tu contraseña"
                            class="form-input"
                        >

                    </div>


                    {{-- RECORDARME --}}
                    <label class="remember-container">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                        >

                        Recordarme

                    </label>


                    {{-- BOTÓN --}}
                    <button
                        type="submit"
                        class="login-button"
                    >
                        Iniciar sesión
                    </button>

                </form>


                {{-- RECUPERACIÓN DE CONTRASEÑA --}}
                <a
                    href="{{ route('password.request') }}"
                    class="forgot-password"
                >
                    ¿Olvidaste tu contraseña?
                </a>

            </div>

        </section>

    </div>

</div>

@endsection
