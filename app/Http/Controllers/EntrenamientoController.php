<?php

namespace App\Http\Controllers;

use App\Models\Entrenamiento;
use App\Models\EntrenamientoImagen;
use App\Models\HistorialAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class EntrenamientoController extends Controller
{
    public $validacion = [
        "tipo" => "required",
        "entrenamiento_imagens" => "required|array|min:1",

    ];

    public $mensajes = [
        "tipo.required" => "Este campo es obligatorio",
        "entrenamiento_imagens.required" => "Debes cargar imagenes",
        "entrenamiento_imagens.min" => "Debes cargar por lo menos :min imágen",
    ];

    public function index()
    {
        return Inertia::render("Entrenamientos/Index");
    }

    public function listado()
    {
        $entrenamientos = Entrenamiento::select("entrenamientos.*")->get();
        return response()->JSON([
            "entrenamientos" => $entrenamientos
        ]);
    }

    public function api(Request $request)
    {
        $entrenamientos = Entrenamiento::with(["entrenamiento_imagens"])->select("entrenamientos.*");
        $entrenamientos = $entrenamientos->get();
        return response()->JSON(["data" => $entrenamientos]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $entrenamientos = Entrenamiento::with(["entrenamiento_imagens"])->select("entrenamientos.*");

        if (trim($search) != "") {
            $entrenamientos->where("tipo", "LIKE", "%$search%");
        }

        $entrenamientos = $entrenamientos->paginate($request->itemsPerPage);
        return response()->JSON([
            "entrenamientos" => $entrenamientos
        ]);
    }

    public function getTiposDiagnosticos(Request $request)
    {

        $tipos = ["CARIES", "SIN CARIES"];

        $array_tipos = [];
        foreach ($tipos as $value) {
            $existe = Entrenamiento::where("tipo", $value)->get()->first();
            if (!$existe) {
                $array_tipos[] = $value;
            }
        }

        if (isset($request->tipo)) {
            $tipo = $request->tipo;
            $array_tipos[] = $tipo;
        }
        return response()->JSON($array_tipos);
    }

    public function create()
    {
        return Inertia::render("Entrenamientos/Create");
    }

    public function store(Request $request)
    {
        set_time_limit(0);
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');

        DB::beginTransaction();
        try {
            // crear el Entrenamiento
            $nuevo_entrenamiento = Entrenamiento::create(array_map('mb_strtoupper', $request->except('eliminados', 'entrenamiento_imagens')));

            if ($request->file("entrenamiento_imagens")) {
                $entrenamiento_imagens = $request->file('entrenamiento_imagens');
                foreach ($entrenamiento_imagens as $key => $file) {
                    $extension = "." . $file['file']->getClientOriginalExtension();
                    $nom_archivo = "imagen" . $nuevo_entrenamiento->id . time() . "_" . $key . $extension;
                    $nuevo_entrenamiento->entrenamiento_imagens()->create([
                        "imagen" => $nom_archivo,
                    ]);
                    $file['file']->move(public_path() . '/imgs/entrenamientos/', $nom_archivo);
                    sleep(0.2);
                }
            }

            $texto = "";
            $archivo = fopen(public_path("files/modelo_imagenes.mdl"), "w");
            fwrite($archivo, "");
            for ($i = 1; $i <= count($nuevo_entrenamiento->entrenamiento_imagens) + 100000; $i++) {
                $archivo = fopen(public_path("files/modelo_imagenes.mdl"), "a");
                if ($i % 2 == 0) {
                    fwrite($archivo, "1");
                    $archivo .= "1";
                } else {
                    fwrite($archivo, "0");
                    $texto .= "0";
                }
            }

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_entrenamiento, "entrenamientos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN ENTRENAMIENTO DE IMAGEN',
                'datos_original' => $datos_original,
                'modulo' => 'ENTRENAMIENTO DE IMAGENES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("entrenamientos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Entrenamiento $entrenamiento) {}

    public function edit(Entrenamiento $entrenamiento)
    {
        $entrenamiento = $entrenamiento->load("entrenamiento_imagens");
        return Inertia::render("Entrenamientos/Edit", compact("entrenamiento"));
    }

    public function update(Entrenamiento $entrenamiento, Request $request)
    {
        set_time_limit(0);
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($entrenamiento, "entrenamientos");
            $entrenamiento->update(array_map('mb_strtoupper', $request->except('eliminados', 'entrenamiento_imagens')));

            if (isset($request->eliminados)) {
                foreach ($request->eliminados as $e) {
                    $entrenamiento_imagen = EntrenamientoImagen::find($e);
                    if (file_exists(public_path("imgs/entrenamientos/" . $entrenamiento_imagen->imagen))) {
                        \File::delete(public_path("imgs/entrenamientos/" . $entrenamiento_imagen->imagen));
                    }
                    $entrenamiento_imagen->delete();
                }
            }

            $entrenamiento_imagens = [];
            if ($request->entrenamiento_imagens) {
                $entrenamiento_imagens = $request->entrenamiento_imagens;
                foreach ($entrenamiento_imagens as $key => $file) {
                    if (isset($file["file"]) && !is_string($file["file"])) {
                        $extension = "." . $file['file']->getClientOriginalExtension();

                        $nom_archivo = "imagen" . $entrenamiento->id . time() . "_" . $key . $extension;
                        $entrenamiento->entrenamiento_imagens()->create([
                            "imagen" => $nom_archivo,
                        ]);
                        $file['file']->move(public_path() . '/imgs/entrenamientos/', $nom_archivo);
                        sleep(0.2);
                    }
                }
            }

            $texto = "";
            $archivo = fopen(public_path("files/modelo_imagenes.mdl"), "w");
            fwrite($archivo, "");
            for ($i = 1; $i <= count($entrenamiento->entrenamiento_imagens) + 100000; $i++) {
                $archivo = fopen(public_path("files/modelo_imagenes.mdl"), "a");
                if ($i % 2 == 0) {
                    fwrite($archivo, "1");
                    $archivo .= "1";
                } else {
                    fwrite($archivo, "0");
                    $texto .= "0";
                }
            }

            $datos_nuevo = HistorialAccion::getDetalleRegistro($entrenamiento, "entrenamientos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN ENTRENAMIENTO DE IMAGEN',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'ENTRENAMIENTO DE IMAGENES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("entrenamientos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Entrenamiento $entrenamiento)
    {
        DB::beginTransaction();
        try {
            foreach ($entrenamiento->entrenamiento_imagens as $item) {
                if (file_exists(public_path("imgs/entrenamientos/" . $item->imagen))) {
                    \File::delete(public_path("imgs/entrenamientos/" . $item->imagen));
                }
                $item->delete();
            }

            $datos_original = HistorialAccion::getDetalleRegistro($entrenamiento, "entrenamientos");
            $entrenamiento->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN ENTRENAMIENTO DE IMAGEN',
                'datos_original' => $datos_original,
                'modulo' => 'ENTRENAMIENTO DE IMAGENES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
