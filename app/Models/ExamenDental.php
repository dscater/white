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
        "imagen",
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
        return asset("imgs/diagnosticos/" . $this->imagen1) . '?p=' . random_int(199, 1000);
    }

    public function getUrlImagen2Attribute()
    {
        return asset("imgs/diagnosticos/" . $this->imagen2) . '?p=' . random_int(199, 1000);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
}
