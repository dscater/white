<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pacientes</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1.5cm;
            margin-bottom: 0.3cm;
            margin-left: 0.3cm;
            margin-right: 0.3cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
            page-break-before: avoid;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
        }

        table thead tr th {
            font-size: 7pt;
        }

        table tbody tr td {
            font-size: 6pt;
        }


        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            height: 100px;
            top: -20px;
            left: 0px;
        }

        h2.titulo {
            width: 450px;
            margin: auto;
            margin-top: 0PX;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .fecha {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: normal;
            font-size: 0.85em;
        }

        .total {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        tr {
            page-break-inside: avoid !important;
        }

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .datos {
            margin-left: 15px;
            border-top: solid 1px;
            border-collapse: collapse;
            width: 250px;
        }

        .txt {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
        }

        .txt_center {
            font-weight: bold;
            text-align: center;
        }

        .cumplimiento {
            position: absolute;
            width: 150px;
            right: 0px;
            top: 86px;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .bg-principal {
            background: #1867C0;
            color: white;
        }

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    <div class="encabezado">
        <div class="logo">
            <img src="{{ $configuracion->first()->logo_b64 }}">
        </div>
        <h2 class="titulo">
            {{ $configuracion->first()->razon_social }}
        </h2>
        <h4 class="texto">LISTA DE PACIENTES</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
    </div>
    <table border="1">
        <thead class="bg-principal">
            <tr>
                <th width="3%">N°</th>
                <th>PATERNO</th>
                <th>MATERNO</th>
                <th>NOMBRE(S)</th>
                <th>C.I.</th>
                <th>FECHA NACIMIENTO</th>
                <th>SEXO</th>
                <th>ESTADO CIVIL</th>
                <th>NACIONALIDAD</th>
                <th>LUGAR DE NACIMIENTO</th>
                <th>OCUPACIÓN</th>
                <th>DIRECCIÓN</th>
                <th>TELÉFONO/CELULAR</th>
                <th>CORREO</th>
                <th>NOMBRE FAMILIAR</th>
                <th>CEL. FAMILIAR</th>
                <th width="9%">FECHA DE REGISTRO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @foreach ($pacientes as $paciente)
                <tr>
                    <td class="centreado">{{ $cont++ }}</td>
                    <td class="">{{ $paciente->paterno }}</td>
                    <td class="">{{ $paciente->materno }}</td>
                    <td class="">{{ $paciente->nombre }}</td>
                    <td class="">{{ $paciente->full_ci }}</td>
                    <td class="">{{ $paciente->fecha_nac }}</td>
                    <td class="">{{ $paciente->sexo }}</td>
                    <td class="">{{ $paciente->estado_civil }}</td>
                    <td class="">{{ $paciente->nacionalidad }}</td>
                    <td class="">{{ $paciente->ocupacion }}</td>
                    <td class="">{{ $paciente->dir }}</td>
                    <td class="">{{ $paciente->fono }}</td>
                    <td class="">{{ $paciente->fono }}</td>
                    <td class="">{{ $paciente->correo }}</td>
                    <td class="">{{ $paciente->nom_familiar }}</td>
                    <td class="">{{ $paciente->fono_familiar }}</td>
                    <td class="centreado">{{ $paciente->fecha_registro_t }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
