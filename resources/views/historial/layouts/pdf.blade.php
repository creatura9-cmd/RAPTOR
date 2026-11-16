
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <title>@yield('titulo', 'Informe RAPTOR')</title>

    <style>
        @page {
            margin: 35px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #263746;
            line-height: 1.5;
        }

        .encabezado {
            width: 100%;
            border-bottom: 3px solid #315A72;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .encabezado td {
            vertical-align: middle;
        }

        .logo {
            width: 125px;
            height: auto;
        }

        .titulo {
            text-align: right;
        }

        .titulo h1 {
            color: #315A72;
            font-size: 20px;
            margin: 0;
        }

        .titulo p {
            margin: 3px 0;
            color: #647785;
            font-size: 9px;
        }

        .folio {
            background: #315A72;
            color: white;
            padding: 8px 12px;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .seccion {
            margin-bottom: 16px;
        }

        .seccion-titulo {
            background: #eaf0f3;
            color: #315A72;
            font-size: 11px;
            font-weight: bold;
            padding: 7px 9px;
            border-left: 4px solid #315A72;
            margin-bottom: 8px;
        }

        .datos {
            width: 100%;
            border-collapse: collapse;
        }

        .datos td {
            padding: 5px 7px;
            border-bottom: 1px solid #e2e8ec;
            vertical-align: top;
        }

        .etiqueta {
            width: 22%;
            color: #647785;
            font-weight: bold;
        }

        .valor {
            width: 28%;
        }

        .tabla {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .tabla th {
            background: #315A72;
            color: white;
            padding: 7px 6px;
            text-align: left;
            font-size: 9px;
        }

        .tabla td {
            padding: 7px 6px;
            border-bottom: 1px solid #e2e8ec;
            font-size: 9px;
        }

        .derecha {
            text-align: right;
        }

        .centro {
            text-align: center;
        }

        .total {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #315A72;
            padding: 10px 5px;
        }

        .observaciones {
            padding: 8px;
            background: #f5f8fa;
            border: 1px solid #e2e8ec;
        }

        .vacio {
            color: #8796a0;
            font-style: italic;
            padding: 8px;
        }

        .pie {
            border-top: 1px solid #dce8ed;
            margin-top: 25px;
            padding-top: 9px;
            text-align: center;
            color: #8796a0;
            font-size: 8px;
        }
    </style>
</head>

<body>

    {{-- Encabezado compartido --}}
    <table class="encabezado">
        <tr>
            <td width="40%">
                @if(file_exists(public_path('images/logoRAPTOR.png')))
                    <img
                        src="{{ public_path('images/logoRAPTOR.png') }}"
                        class="logo"
                    >
                @else
                    <h1 style="color:#315A72;">RAPTOR</h1>
                @endif
            </td>

            <td width="60%" class="titulo">
                <h1>@yield('titulo', 'INFORME')</h1>
                <p>Gestión inteligente para talleres de motocicletas</p>
                <p>Registro y seguimiento de servicios técnicos</p>
            </td>
        </tr>
    </table>

    {{-- Contenido de cada informe --}}
    @yield('contenido')

    {{-- Pie compartido --}}
    <div class="pie">
        RAPTOR - Gestión inteligente para talleres de motocicletas<br>
        Informe generado por el sistema de gestión RAPTOR.
    </div>

</body>
</html>
