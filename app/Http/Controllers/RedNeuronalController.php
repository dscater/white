<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phpml\Classification\KNearestNeighbors;
use Phpml\ModelManager;

class RedNeuronalController extends Controller
{
    public function entrenamiento($files)
    {
        foreach ($files as $image) {
            $tempFilePath = $image->getPath();
            // Leer la imagen
            $image = imagecreatefromjpeg($tempFilePath);

            // Obtener las dimensiones de la imagen
            $width = imagesx($image);
            $height = imagesy($image);

            // Convertir la imagen en una matriz de píxeles
            $pixelMatrix = [];
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    // Obtener el color del píxel en coordenadas (x, y)
                    $rgb = imagecolorat($image, $x, $y);

                    // Extraer los componentes de color RGB
                    $red = ($rgb >> 16) & 0xFF;
                    $green = ($rgb >> 8) & 0xFF;
                    $blue = $rgb & 0xFF;

                    // Almacenar los componentes de color en la matriz de píxeles
                    $pixelMatrix[$x][$y] = [$red, $green, $blue];
                }
            }

            // Iterar sobre la imagen y marcar los píxeles negros como 1 y los demás como 0
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    // Obtener el color del píxel en coordenadas (x, y)
                    $rgb = imagecolorat($image, $x, $y);

                    // Extraer los componentes de color RGB
                    $red = ($rgb >> 16) & 0xFF;
                    $green = ($rgb >> 8) & 0xFF;
                    $blue = $rgb & 0xFF;

                    // Determinar si el píxel es negro
                    if ($red === 0 && $green === 0 && $blue === 0) {
                        $pixelArray[$x][$y] = 1; // Marcar como negro (1)
                    } else {
                        $pixelArray[$x][$y] = 0; // Marcar como no negro (0)
                    }
                }
            }

            // obtener las caracteristicas de la imagen en pixeles
            $informacion_imagen = [];
            foreach ($pixelMatrix as $row) {
                foreach ($row as $pixel) {
                    $informacion_imagen = array_merge($informacion_imagen, $pixelArray);
                }
            }
        }

        $classifier = new KNearestNeighbors();
        $classifier->train($informacion_imagen, $pixelMatrix);

        // Guarda el modelo entrenado
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, public_path('files/modelo_imagenes.mdl'));

        return true;
    }

    public static function reconocimiento($imagen)
    {
        $modelManager = new ModelManager();
        $modelo = $modelManager->restoreFromFile(public_path('files/modelo_imagenes.mdl'));

        $tempFilePath = $imagen->getPath();
        // Leer la imagen
        $image = imagecreatefromjpeg($tempFilePath);

        // Obtener las dimensiones de la imagen
        $width = imagesx($image);
        $height = imagesy($image);

        // Convertir la imagen en una matriz de píxeles
        $pixelMatrix = [];
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                // Obtener el color del píxel en coordenadas (x, y)
                $rgb = imagecolorat($image, $x, $y);

                // Extraer los componentes de color RGB
                $red = ($rgb >> 16) & 0xFF;
                $green = ($rgb >> 8) & 0xFF;
                $blue = $rgb & 0xFF;

                // Almacenar los componentes de color en la matriz de píxeles
                $pixelMatrix[$x][$y] = [$red, $green, $blue];
            }
        }

        // obtener las caracteristicas de la imagen en pixeles
        $informacion_imagen = [];
        foreach ($pixelMatrix as $row) {
            foreach ($row as $pixel) {
                $informacion_imagen = array_merge($informacion_imagen, $pixel);
            }
        }

        $prediccion = $modelo->predict($informacion_imagen);

        return $prediccion;
    }
}
