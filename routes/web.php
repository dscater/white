<?php

use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\EntrenamientoController;
use App\Http\Controllers\EntrenamientoImagenController;
use App\Http\Controllers\ExamenDentalController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
})->name("porta.index");

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
})->name("login");

Route::get("configuracions/getConfiguracion", [ConfiguracionController::class, 'getConfiguracion'])->name("configuracions.getConfiguracion");

Route::middleware('auth')->prefix("admin")->group(function () {
    // INICIO
    Route::get('/inicio', [InicioController::class, 'inicio'])->name('inicio');
    Route::get("/inicio/getMaximoImagenes", [InicioController::class, 'getMaximoImagenes'])->name("entrenamientos.getMaximoImagenes");

    // CONFIGURACION
    Route::resource("configuracions", ConfiguracionController::class)->only(
        ["index", "show", "update"]
    );

    // USUARIO
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("permisos", [UserController::class, 'permisos']);

    // USUARIOS
    Route::put("usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("usuarios/api", [UsuarioController::class, 'api'])->name("usuarios.api");
    Route::get("usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["index", "store"]
    );

    // PACIENTES
    Route::get("pacientes/api", [PacienteController::class, 'api'])->name("pacientes.api");
    Route::get("pacientes/paginado", [PacienteController::class, 'paginado'])->name("pacientes.paginado");
    Route::get("pacientes/listado", [PacienteController::class, 'listado'])->name("pacientes.listado");
    Route::resource("pacientes", PacienteController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // EXAMEN DENTAL
    Route::get("examen_dentals/api", [ExamenDentalController::class, 'api'])->name("examen_dentals.api");
    Route::get("/examen_dentals/paginado", [ExamenDentalController::class, 'paginado'])->name("examen_dentals.paginado");
    Route::get("/examen_dentals/listado", [ExamenDentalController::class, 'listado'])->name("examen_dentals.listado");
    Route::post("/examen_dentals/procesarImagen", [ExamenDentalController::class, 'procesarImagen'])->name("examen_dentals.procesarImagen");
    Route::resource("examen_dentals", ExamenDentalController::class)->only(
        ["index", "create", "store", "edit", "update", "show", "destroy"]
    );

    // ENTRENAMIENTO IMAGENES
    Route::delete("/entrenamiento_imagens/{entrenamiento_imagen}", [EntrenamientoImagenController::class, 'destroy'])->name("entrenamiento_imagens.destroy");

    // ENTRENAMIENTOS
    Route::get("entrenamientos/api", [EntrenamientoController::class, 'api'])->name("entrenamientos.api");
    Route::get("/entrenamientos/getTiposDiagnosticos", [EntrenamientoController::class, 'getTiposDiagnosticos'])->name("entrenamientos.getTiposDiagnosticos");
    Route::get("/entrenamientos/paginado", [EntrenamientoController::class, 'paginado'])->name("entrenamientos.paginado");
    Route::get("/entrenamientos/listado", [EntrenamientoController::class, 'listado'])->name("entrenamientos.listado");
    Route::post("/entrenamientos/procesarImagen", [EntrenamientoController::class, 'procesarImagen'])->name("entrenamientos.procesarImagen");
    Route::resource("entrenamientos", EntrenamientoController::class)->only(
        ["index", "create", "store", "edit", "update", "show", "destroy"]
    );

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");
});
require __DIR__ . '/auth.php';
