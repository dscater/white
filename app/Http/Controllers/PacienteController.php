<?php

namespace App\Http\Controllers;

use App\Models\ExamenDental;
use App\Models\HistorialAccion;
use App\Models\Paciente;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PacienteController extends Controller
{
    public $validacion = [
        "nombre" => "required",
        "paterno" => "required",
        "sexo" => "required",
        "estado_civil" => "required",
        "nacionalidad" => "required",
        "dir" => "required",
        "fono" => "required",
        "correo" => "required|email",
        "nom_familiar" => "required",
        "fono_familiar" => "required",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "paterno.required" => "Este campo es obligatorio",
        "sexo.required" => "Este campo es obligatorio",
        "estado_civil.required" => "Este campo es obligatorio",
        "nacionalidad.required" => "Este campo es obligatorio",
        "dir.required" => "Este campo es obligatorio",
        "fono.required" => "Este campo es obligatorio",
        "correo.required" => "Este campo es obligatorio",
        "correo.email" => "Debes ingresar un correo valido",
        "nom_familiar.required" => "Este campo es obligatorio",
        "fono_familiar.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Pacientes/Index");
    }

    public function listado()
    {
        $pacientes = Paciente::select("pacientes.*")->get();
        return response()->JSON([
            "pacientes" => $pacientes
        ]);
    }

    public function api(Request $request)
    {
        $pacientes = Paciente::select("pacientes.*");
        $pacientes = $pacientes->get();
        return response()->JSON(["data" => $pacientes]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $pacientes = Paciente::select("pacientes.*");

        if (trim($search) != "") {
            $pacientes->where("nombre", "LIKE", "%$search%");
            $pacientes->orWhere("paterno", "LIKE", "%$search%");
            $pacientes->orWhere("materno", "LIKE", "%$search%");
            $pacientes->orWhere("ci", "LIKE", "%$search%");
        }

        $pacientes = $pacientes->paginate($request->itemsPerPage);
        return response()->JSON([
            "pacientes" => $pacientes
        ]);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:4096';
        }
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            $request['fecha_registro'] = date('Y-m-d');
            // crear el Paciente
            $nuevo_paciente = Paciente::create(array_map('mb_strtoupper', $request->except('foto')));
            if ($request->hasFile('foto')) {
                $file = $request->foto;
                $nom_foto = time() . '_' . $nuevo_paciente->id . '.' . $file->getClientOriginalExtension();
                $nuevo_paciente->foto = $nom_foto;
                $file->move(public_path() . '/imgs/pacientes/', $nom_foto);
            }
            $nuevo_paciente->save();

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_paciente, "pacientes");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN PACIENTE',
                'datos_original' => $datos_original,
                'modulo' => 'PACIENTES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("pacientes.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Paciente $paciente)
    {
        return response()->JSON($paciente);
    }

    public function update(Paciente $paciente, Request $request)
    {
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:4096';
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($paciente, "pacientes");
            $paciente->update(array_map('mb_strtoupper', $request->except('foto')));
            if ($request->hasFile('foto')) {
                $antiguo = $paciente->foto;
                if ($antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/pacientes/' . $antiguo);
                }
                $file = $request->foto;
                $nom_foto = time() . '_' . $paciente->id . '.' . $file->getClientOriginalExtension();
                $paciente->foto = $nom_foto;
                $file->move(public_path() . '/imgs/pacientes/', $nom_foto);
            }
            $paciente->save();

            $datos_nuevo = HistorialAccion::getDetalleRegistro($paciente, "pacientes");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN PACIENTE',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'PACIENTES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("pacientes.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Paciente $paciente)
    {
        DB::beginTransaction();
        try {
            $usos = ExamenDental::where("paciente_id", $paciente->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $usos = Seguimiento::where("paciente_id", $paciente->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $antiguo = $paciente->foto;
            if ($antiguo != 'default.png') {
                \File::delete(public_path() . '/imgs/pacientes/' . $antiguo);
            }
            $datos_original = HistorialAccion::getDetalleRegistro($paciente, "pacientes");
            $paciente->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN PACIENTE',
                'datos_original' => $datos_original,
                'modulo' => 'PACIENTES',
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
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
