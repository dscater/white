<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeguimientoController extends Controller
{
    public function index()
    {
        return Inertia::render("Seguimientos/Index");
    }

    public function update(Seguimiento $seguimiento, Request $request)
    {
        $col = $request->col;
        $value = $request->value;
        $seguimiento[$col] = $value;
        $seguimiento->fecha_registro = date("Y-m-d");
        $seguimiento->save();

        return response()->JSON([
            "sw" => true
        ]);
    }
}
