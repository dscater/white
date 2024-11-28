<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>ExamenDental</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
            margin-right: 1cm;
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

        .textinfo {
            font-weight: bold;
            border-top: dashed 1px black;
        }

        .img_celda img {
            width: 45px;
        }

        .txttitle {
            width: 100%;
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }

        .tableinfo {
            border-collapse: separate;
            border-spacing: 40px 0px;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @php
        $contador = 0;
    @endphp
    @foreach ($examen_dentals as $examen_dental)
        @php
            $contador++;
        @endphp
        <div class="encabezado">
            <div class="logo">
                <img src="{{ $configuracion->first()->logo_b64 }}">
            </div>
            <h2 class="titulo">
                {{ $configuracion->first()->razon_social }}
            </h2>
            <h4 class="texto">EXÁMENES DENTALES</h4>
            <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
        </div>

        <table class="tableinfo">
            <tbody>
                <tr>
                    <td class="centreado">{{ $examen_dental->codigo }}</td>
                    <td>{{ $examen_dental->paciente->full_name }}</td>
                    <td class="centreado">{{ $examen_dental->fecha_registro_t }}</td>
                </tr>
                <tr>
                    <td class="textinfo centreado">CÓD. EXÁMEN</td>
                    <td class="textinfo centreado">PACIENTE</td>
                    <td class="textinfo centreado">FECHA DE REGISTRO</td>
                </tr>
                <tr>
                    <td>{{ $examen_dental->dolencia_actual }}</td>
                    <td colspan="2">{{ $examen_dental->resultado }}</td>
                </tr>
                <tr>
                    <td class="textinfo centreado">DOLENCIA</td>
                    <td colspan="2" class="textinfo centreado">RESULTADO</td>
                </tr>
            </tbody>
        </table>
        <h4 class="txttitle">SEGUIMIENTO EXÁMEN</h4>
        <table border="1">
            <thead>
                <tr>
                    <th width="10%">PIEZA</th>
                    <th>DIAGNOSTICO</th>
                    <th>OBSERVACIONES</th>
                    <th>ESTADO</th>
                    <th>OBSERVACIÓN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($examen_dental->examen_detalles as $ed)
                    <tr>
                        <td class="centreado">{{ $ed->pieza }}</td>
                        <td>{{ $ed->diagnostico }}</td>
                        <td>{{ $ed->observaciones }}</td>
                        <td>{{ $ed->seguimiento->estado }}</td>
                        <td>{{ $ed->seguimiento->observacion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($contador < count($examen_dentals))
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
