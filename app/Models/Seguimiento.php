<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        "paciente_id",
        "examen_dental_id",
        "examen_detalle_id",
        "pieza",
        "estado",
        "observacion",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function examen_dental()
    {
        return $this->belongsTo(ExamenDental::class, 'examen_dental_id');
    }

    public function examen_detalle()
    {
        return $this->belongsTo(ExamenDetalle::class, 'examen_detalle_id');
    }
}
