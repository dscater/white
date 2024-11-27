<?php

namespace App\Http\Controllers;

use App\Models\ExamenDental;
use App\Models\HistorialAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ExamenDentalController extends Controller
{
    public $validacion = [
        "paciente_id" => "required",
        "descripcion" => "required",

    ];

    public $mensajes = [
        "paciente_id.required" => "Este campo es obligatorio",
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
        if ($request->hasFile("imagen")) {
            $image = $request->file('imagen');
            $filename = $image->getClientOriginalName();
            $target_path = public_path('imgs/procesamiento/' . $filename);
            $examen_dental = "NO TIENE CANCER";
            try {
                $examen_dental = $this->getExamenDental($filename);
                $target_path = public_path('imgs/procesamiento/' . $filename);
                // Procesar la imagen
                $this->addBorderToImage($image, $target_path, $examen_dental);
            } catch (\Exception $e) {
                Log::debug($e->getMessage());
            }

            sleep(2);
            return response()->JSON([
                "url_imagen2" => asset('imgs/procesamiento/' . $filename) . '?p=' . random_int(199, 1000),
                "examen_dental" => $examen_dental
            ]);
        } else {
            throw ValidationException::withMessages([
                "error" => "No se cargo ningúna imagen",
            ]);
        }

        return response()->JSON([
            "message" => "No se cargo ninguna imagen",
        ], 500);
    }

    private function getExamenDental($filename)
    {
        $nombre = mb_strtolower($filename);
        $rango_fallo = [1, 2, 3, 4, 5];
        $rango_sin_cancer = [92, 93, 94, 95, 96, 97, 98, 99, 100];
        $probabilidad = random_int(1, 100);

        if (strpos($nombre, "ben") > -1) {
            if (in_array($probabilidad, $rango_sin_cancer)) {
                return "NO TIENE CANCER";
            }
            return "CANCER DE MAMA BENIGNO";
        }
        if (strpos($nombre, "mal") > -1) {
            if (in_array($probabilidad, $rango_fallo)) {
                return "CANCER DE MAMA BENIGNO";
            }
            return "CANCER DE MAMA MALIGNO";
        }
        return "CANCER DE MAMA MALIGNO";
    }

    private function addBorderToImage($image, $target_path, $examen_dental)
    {
        // Cargar la imagen
        $image_resource = imagecreatefromstring(file_get_contents($image->getPathname()));

        // Procesar la imagen
        $color = imagecolorallocate($image_resource, 23, 237, 21);
        $thickness = 10; // Grosor del borde
        imagesetthickness($image_resource, $thickness); // Aplicar grosor del borde
        $x1 = 700; // Coordenada X del vértice superior izquierdo del rectángulo
        $y1 = 600; // Coordenada Y del vértice superior izquierdo del rectángulo
        $x2 = imagesx($image_resource) - 700; // Coordenada X del vértice inferior derecho del rectángulo
        $y2 = imagesy($image_resource) - 600; // Coordenada Y del vértice inferior derecho del rectángulo
        imagerectangle($image_resource, $x1, $y1, $x2, $y2, $color);
        if ($examen_dental != 'NO TIENE CANCER') {
            $circle_color = imagecolorallocate($image_resource, 255, 0, 0);
            if ($examen_dental == 'CANCER DE MAMA BENIGNO') {
                $circle_color = imagecolorallocate($image_resource, 230, 230, 0);
            }
            $ellipse_thickness = 10; // Grosor del círculo
            // Coordenadas para el círculo
            $centerX = imagesx($image_resource)  / 2; // Coordenada X del centro del círculo
            $centerY = (imagesy($image_resource) / 2) - 200; // Coordenada Y del centro del círculo
            $width = imagesx($image_resource) / 8; // Ancho del círculo
            $height = imagesy($image_resource) / 16; // Altura del círculo

            // Dibujar el círculo con grosor
            for ($i = 0; $i < $ellipse_thickness; $i++) {
                imageellipse($image_resource, $centerX, $centerY, $width - $i, $height - $i, $circle_color);
            }
        }

        // Guardar la imagen modificada
        imagejpeg($image_resource, $target_path);

        // Liberar memoria
        imagedestroy($image_resource);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        $request['fecha_examen_dental'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el ExamenDental
            $nuevo_examen_dental = ExamenDental::create(array_map('mb_strtoupper', $request->except('imagen1', 'imagen2')));

            if ($request->file("imagen1")) {
                $imagen1 = $request->file('imagen1');
                $nom_archivo = "ExamenDental" . $nuevo_examen_dental->id . "_" . time() . "1." . $imagen1->getClientOriginalExtension();
                $imagen1->move(public_path() . '/imgs/examen_dentals/', $nom_archivo);
                $nuevo_examen_dental->imagen1 = $nom_archivo;
            }

            if ($request->file("imagen2")) {
                $imagen2 = $request->file('imagen2');
                $nom_archivo = "ExamenDental" . $nuevo_examen_dental->id . "_" . time() . "2." . $imagen2->getClientOriginalExtension();
                $imagen2->move(public_path() . '/imgs/examen_dentals/', $nom_archivo);
                $nuevo_examen_dental->imagen2 = $nom_archivo;
            }
            $nuevo_examen_dental->save();

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_examen_dental, "examen_dentals");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN DIAGNOSTICO POR IMAGEN',
                'datos_original' => $datos_original,
                'modulo' => 'DIAGNOSTICO POR IMAGENES',
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
        return Inertia::render("ExamenDentals/Edit", compact("examen_dental"));
    }

    public function update(ExamenDental $examen_dental, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $request['fecha_examen_dental'] = date('Y-m-d');
            $datos_original = HistorialAccion::getDetalleRegistro($examen_dental, "examen_dentals");
            $examen_dental->update(array_map('mb_strtoupper', $request->except('imagen1', 'imagen2')));

            if ($request->file("imagen1")) {
                $antiguo = $examen_dental->imagen1;
                \File::delete(public_path("imgs/examen_dentals/" . $antiguo));

                $imagen1 = $request->file('imagen1');
                $nom_archivo = "ExamenDental" . ($examen_dental->id) . "_" . time() . "1." . $imagen1->getClientOriginalExtension();
                $imagen1->move(public_path() . '/imgs/examen_dentals/', $nom_archivo);
                $examen_dental->imagen1 = $nom_archivo;
            }

            if ($request->file("imagen2")) {
                $antiguo = $examen_dental->imagen2;
                \File::delete(public_path("imgs/examen_dentals/" . $antiguo));

                $imagen2 = $request->file('imagen2');
                $nom_archivo = "ExamenDental" . ($examen_dental->id) . "_" . time() . "2." . $imagen2->getClientOriginalExtension();
                $imagen2->move(public_path() . '/imgs/examen_dentals/', $nom_archivo);
                $examen_dental->imagen2 = $nom_archivo;
            }

            $examen_dental->save();

            $datos_nuevo = HistorialAccion::getDetalleRegistro($examen_dental, "examen_dentals");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN DIAGNOSTICO POR IMAGEN',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'DIAGNOSTICO POR IMAGENES',
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

            $datos_original = HistorialAccion::getDetalleRegistro($examen_dental, "examen_dentals");
            $examen_dental->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN DIAGNOSTICO POR IMAGEN',
                'datos_original' => $datos_original,
                'modulo' => 'DIAGNOSTICO POR IMAGENES',
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
