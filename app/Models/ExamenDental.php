<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenDental extends Model
{
    use HasFactory;

    protected $fillable = [
        "paciente_id",
        "dolencia_actual",
        "imagen1",
        "imagen2",
        "resultado",
        "fecha_registro",
    ];

    protected $appends = ["url_imagen1", "url_imagen2", "fecha_registro_t"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getUrlImagen1Attribute()
    {
        return asset("imgs/examen_dentals/" . $this->imagen1) . '?p=' . random_int(199, 1000);
    }

    public function getUrlImagen2Attribute()
    {
        return asset("imgs/examen_dentals/" . $this->imagen2) . '?p=' . random_int(199, 1000);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function examen_detalles()
    {
        return $this->hasMany(ExamenDetalle::class, 'examen_dental_id');
    }

    public function seguimientos()
    {
        return $this->hasOne(Seguimiento::class, 'examen_dental_id');
    }

    // FUNCIONES
    public static function getCodigoNuevo()
    {
        $ultimo = ExamenDental::get()->last();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro_cod + 1;
        }

        $codigo = "CE." . $nro;
        return [$codigo, $nro];
    }
}
