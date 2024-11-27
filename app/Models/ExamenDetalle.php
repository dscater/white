<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "examen_dental_id",
        "pieza",
        "diagnostico",
        "observaciones",
    ];

    public function examen_dental()
    {
        return $this->belongsTo(ExamenDental::class, 'examen_dental_id');
    }

    public function seguimiento()
    {
        return $this->hasOne(Seguimiento::class, 'examen_detalle_id');
    }
}
