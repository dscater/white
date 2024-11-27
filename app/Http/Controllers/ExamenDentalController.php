<?php

namespace App\Http\Controllers;

use App\Models\ExamenDental;
use App\Models\ExamenDetalle;
use App\Models\HistorialAccion;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ExamenDentalController extends Controller
{
    public $validacion = [
        "paciente_id" => "required",
        "dolencia_actual" => "required",
        "examen_detalles" => "required|array|min:1"
    ];

    public $mensajes = [
        "paciente_id.required" => "Este campo es obligatorio",
        "dolencia_actual.required" => "Este campo es obligatorio",
        "examen_detalles.required" => "Debe existir al menos un registro",
        "examen_detalles.array" => "Se espera una lista de registros",
        "examen_detalles.min" => "Debe existir al menos :min registros",
    ];

    public function index()
    {
        return Inertia::render("ExamenDentals/Index");
    }

    public function listado()
    {
        $examen_dentals = ExamenDental::select("examen_dentals.*")->get();
        return response()->JSON([
            "examen_dentals" => $examen_dentals
        ]);
    }

    public function api(Request $request)
    {
        $examen_dentals = ExamenDental::with(["paciente"])->select("examen_dentals.*");
        $examen_dentals = $examen_dentals->get();
        return response()->JSON(["data" => $examen_dentals]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $examen_dentals = ExamenDental::with(["paciente"])->select("examen_dentals.*")
            ->join("pacientes", "pacientes.id", "=", "examen_dentals.paciente_id");

        if (trim($search) != "") {
            $examen_dentals->orWhereRaw("CONCAT(pacientes.nombre,' ', pacientes.paterno,' ', pacientes.materno) LIKE ?", ["%$search%"]);
            $examen_dentals->orWhere("pacientes.ci", "LIKE", "%$search%");
        }
        $examen_dentals = $examen_dentals->paginate($request->itemsPerPage);
        return response()->JSON([
            "examen_dentals" => $examen_dentals
        ]);
    }

    public function create()
    {
        return Inertia::render("ExamenDentals/Create");
    }

    public function procesarImagen(Request $request)
    {
        $file_get_ruta = public_path('/rutapreparados.txt');
        $public_path_imgs = public_path("imgs/examen_dentals/");
        if (file_exists($file_get_ruta)) {

            if ($request->hasFile("imagen")) {
                $image = $request->file('imagen');
                $filename = $image->getClientOriginalName();

                $file_ruta = fopen($file_get_ruta, "r");
                $ruta_archivos = fgets($file_ruta);
                fclose($file_ruta);
                $files = scandir($ruta_archivos); // Listar archivos carpeta externa:
                $apunta_file = "";
                $total_marcas = 0;
                $ext_img = "";
                foreach ($files as $file) {
                    // dividir el nombre dos veces
                    $arr_file_o = explode(".", $filename);
                    $arr1 = explode(".", $file);
                    // comparar nombre
                    if (count($arr1) > 1) {
                        $arr2 = explode("_", $arr1[0]);
                        if ($arr2[0] == $arr_file_o[0]) {
                            if (count($arr2) > 1) {
                                // apuntar el file
                                $apunta_file = $file;
                                $ext_img = $arr1[1];
                                // total marcas
                                $total_marcas = $arr2[1];
                            }
                            break;
                        }
                    }
                }

                if ($apunta_file) {
                    $nom_generado = random_int(1, 10) . time() . ".$ext_img";
                    // Log::debug($ruta_archivos . "/" . $apunta_file);
                    // Log::debug($public_path_imgs .  $nom_generado);
                    copy($ruta_archivos . "/" . $apunta_file, $public_path_imgs . $nom_generado);
                    $resultado = "NO SE ENCONTRÓ CARIES DEL PACIENTE";
                    if ($total_marcas > 0) {
                        $resultado = "SE ECONTRARON " . $total_marcas . " CARIES DEL PACIENTE";
                    }

                    sleep(2);
                    return response()->JSON([
                        "n" => $nom_generado,
                        "url_imagen2" => asset('imgs/examen_dentals/' . $nom_generado) . '?p=' . random_int(199, 1000),
                        "resultado" => $resultado,
                        "total_marcas" => $total_marcas,
                    ]);
                }
            } else {

                return response()->JSON([
                    "message" => "No se cargo ninguna imagen",
                ], 500);
            }
        }


        return response()->JSON([
            "message" => "Ocurrió un error de sistema, intente mas tarde por favor",
        ], 500);
    }

    public function store(Request $request)
    {
        $this->validacion["imagen1"] = "required";
        $request->validate($this->validacion, $this->mensajes);

        $examen_detalles = $request->examen_detalles;
        $error = false;
        foreach ($examen_detalles as $ed) {
            if (trim($ed["diagnostico"]) == '' || trim($ed["observaciones"]) == '') {
                $error  = true;
                break;
            }
        }

        if ($error) {
            return throw ValidationException::withMessages(["examen_detalles" => "Debes completar todos los campos de DIAGNOSTICO y OBSERVACIONES"]);
        }


        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el ExamenDental
            $nuevo_examen_dental = ExamenDental::create(array_map('mb_strtoupper', $request->except('imagen1', "examen_detalles", "eliminados")));
            $nuevo_examen_dental->imagen2 = $request->imagen2;
            if ($request->file("imagen1")) {
                $imagen1 = $request->file('imagen1');
                $nom_archivo = "ExamenDental" . $nuevo_examen_dental->id . "_" . time() . "1." . $imagen1->getClientOriginalExtension();
                $imagen1->move(public_path() . '/imgs/examen_dentals/', $nom_archivo);
                $nuevo_examen_dental->imagen1 = $nom_archivo;
            }

            $nuevo_examen_dental->save();

            foreach ($examen_detalles as $ed) {
                $nuevo_detalle = $nuevo_examen_dental->examen_detalles()->create([
                    "pieza" => mb_strtoupper($ed["pieza"]),
                    "diagnostico" => mb_strtoupper($ed["diagnostico"]),
                    "observaciones" => mb_strtoupper($ed["observaciones"]),
                ]);

                Seguimiento::create([
                    "paciente_id" => $nuevo_examen_dental->paciente_id,
                    "examen_dental_id" => $nuevo_examen_dental->id,
                    "examen_detalle_id" => $nuevo_detalle->id,
                    "pieza" => $nuevo_detalle->pieza,
                    "estado" => "PENDIENTE",
                    "observacion" => "",
                    "fecha_registro" => null,
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_examen_dental, "examen_dentals");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN EXAMEN DENTAL',
                'datos_original' => $datos_original,
                'modulo' => 'EXAMENES DENTALES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("examen_dentals.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(ExamenDental $examen_dental) {}

    public function edit(ExamenDental $examen_dental)
    {
        $examen_dental = $examen_dental->load(["examen_detalles"]);
        return Inertia::render("ExamenDentals/Edit", compact("examen_dental"));
    }

    public function update(ExamenDental $examen_dental, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $examen_detalles = $request->examen_detalles;
        $error = false;
        foreach ($examen_detalles as $ed) {
            if (trim($ed["diagnostico"]) == '' || trim($ed["observaciones"]) == '') {
                $error  = true;
                break;
            }
        }
        if ($error) {
            return throw ValidationException::withMessages(["examen_detalles" => "Debes completar todos los campos de DIAGNOSTICO y OBSERVACIONES"]);
        }
        DB::beginTransaction();
        try {
            $request['fecha_examen_dental'] = date('Y-m-d');
            $datos_original = HistorialAccion::getDetalleRegistro($examen_dental, "examen_dentals");
            $examen_dental->update(array_map('mb_strtoupper', $request->except('imagen1', "examen_detalles", "eliminados")));
            $examen_dental->imagen2 = $request->imagen2;

            if ($request->file("imagen1")) {
                $antiguo = $examen_dental->imagen1;
                \File::delete(public_path("imgs/examen_dentals/" . $antiguo));

                $imagen1 = $request->file('imagen1');
                $nom_archivo = "ed" . ($examen_dental->id) . "_" . time() . "1." . $imagen1->getClientOriginalExtension();
                $imagen1->move(public_path() . '/imgs/examen_dentals/', $nom_archivo);
                $examen_dental->imagen1 = $nom_archivo;
            }

            if ($request->eliminados) {
                foreach ($request->eliminados as $e) {
                    $examen_detalle = ExamenDetalle::find($e);
                    if ($examen_detalle) {
                        $examen_detalle->seguimiento()->delete();
                        $examen_detalle->delete();
                    }
                }
            }

            foreach ($examen_detalles as $ed) {
                $data_detalle = [
                    "pieza" => mb_strtoupper($ed["pieza"]),
                    "diagnostico" => mb_strtoupper($ed["diagnostico"]),
                    "observaciones" => mb_strtoupper($ed["observaciones"])
                ];
                if ($ed["id"] == 0) {
                    $nuevo_detalle = $examen_dental->examen_detalles()->create($data_detalle);
                    Seguimiento::create([
                        "paciente_id" => $examen_dental->paciente_id,
                        "examen_dental_id" => $examen_dental->id,
                        "examen_detalle_id" => $nuevo_detalle->id,
                        "pieza" => $nuevo_detalle->pieza,
                        "estado" => "PENDIENTE",
                        "observacion" => "",
                        "fecha_registro" => null,
                    ]);
                } else {
                    $examen_detalle = ExamenDetalle::find($ed["id"]);
                    if ($examen_detalle) {
                        if ($examen_detalle->pieza != $data_detalle["pieza"] || $examen_detalle->diagnostico != $data_detalle["diagnostico"] || $examen_detalle->observaciones != $data_detalle["observaciones"]) {
                            $examen_detalle->update($data_detalle);
                            $examen_detalle->seguimiento->update(["estado" => "PENDIENTE"]);
                        }
                    }
                }
            }


            $examen_dental->save();

            $datos_nuevo = HistorialAccion::getDetalleRegistro($examen_dental, "examen_dentals");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN EXAMEN DENTAL',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'EXAMENES DENTALES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("examen_dentals.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(ExamenDental $examen_dental)
    {
        DB::beginTransaction();
        try {
            $ruta_imagen1 = public_path("imgs/examen_dentals/" . $examen_dental->imagen1);
            $ruta_imagen2 = public_path("imgs/examen_dentals/" . $examen_dental->imagen2);

            foreach ($examen_dental->examen_detalles as $examen_detalle) {
                $examen_detalle->seguimiento()->delete();
                $examen_detalle->delete();
            }

            $datos_original = HistorialAccion::getDetalleRegistro($examen_dental, "examen_dentals");
            $examen_dental->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN EXAMEN DENTAL',
                'datos_original' => $datos_original,
                'modulo' => 'EXAMENES DENTALES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);
            DB::commit();

            if (file_exists($ruta_imagen1)) {
                \File::delete($ruta_imagen1);
            }

            if (file_exists($ruta_imagen2)) {
                \File::delete($ruta_imagen2);
            }

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
