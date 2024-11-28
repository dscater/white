<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ExamenDental;
use App\Models\Lote;
use App\Models\Paciente;
use App\Models\Seguimiento;
use App\Models\Urbanizacion;
use App\Models\User;
use App\Models\VentaLote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PDF;

class ReporteController extends Controller
{
    public function usuarios()
    {
        return Inertia::render("Reportes/Usuarios");
    }

    public function r_usuarios(Request $request)
    {
        $usuarios = User::where('id', '!=', 1)->orderBy("paterno", "ASC")->get();
        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('usuarios.pdf');
    }

    public function pacientes()
    {
        return Inertia::render("Reportes/Pacientes");
    }

    public function r_pacientes(Request $request)
    {
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $pacientes = Paciente::select("pacientes.*");

        if ($fecha_ini && $fecha_fin) {
            $pacientes->whereBetween("fecha_registro", [$fecha_ini, $fecha_fin]);
        }

        $pacientes = $pacientes->get();
        $pdf = PDF::loadView('reportes.pacientes', compact('pacientes'))->setPaper('letter', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('pacientes.pdf');
    }

    public function examen_dentals()
    {
        return Inertia::render("Reportes/ExamenDentals");
    }

    public function r_examen_dentals(Request $request)
    {
        $examen_dental_id = $request->examen_dental_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $examen_dentals = ExamenDental::select("examen_dentals.*");

        if ($examen_dental_id != 'todos') {
            $examen_dentals->where("id", $examen_dental_id);
        }

        if ($fecha_ini && $fecha_fin) {
            $examen_dentals->whereBetween("fecha_registro", [$fecha_ini, $fecha_fin]);
        }
        $examen_dentals = $examen_dentals->get();

        $pdf = PDF::loadView('reportes.examen_dentals', compact('examen_dentals'))->setPaper('letter', 'portrait');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('examen_dentals.pdf');
    }

    public function seguimientos()
    {
        return Inertia::render("Reportes/Seguimientos");
    }

    public function r_seguimientos(Request $request)
    {
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;
        $data = [];
        $estados = ["PENDIENTE", "EN PROCESO", "FINALIZADO"];

        foreach ($estados as $value) {
            $seguimientos = Seguimiento::where("estado", $value);
            if ($fecha_ini && $fecha_fin) {
                $seguimientos->whereBetween("fecha_registro", [$fecha_ini, $fecha_fin]);
            }
            $seguimientos = $seguimientos->count();
            $data[] = [$value, (int)$seguimientos];
        }

        return response()->JSON([
            "data" => $data
        ]);
    }
}
