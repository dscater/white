<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntrenamientoImagenController extends Controller
{
    public static function getExtensionImagenDB($imagen)
    {
        $array = explode(".", $imagen);
        return $array[1];
    }
}
