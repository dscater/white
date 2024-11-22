<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "paterno",
        "materno",
        "ci",
        "ci_exp",
        "fecha_nac",
        "sexo",
        "estado_civil",
        "nacionalidad",
        "lugar_nac",
        "ocupacion",
        "dir",
        "fono",
        "correo",
        "nom_familiar",
        "fono_familiar",
        "foto",
        "fecha_registro",
    ];

    protected $appends = ["url_foto", "foto_b64", "full_name", "full_ci", "fecha_registro_t"];
    
    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getFullCiAttribute()
    {
        return $this->ci . ' ' . $this->ci_exp;
    }

    public function getFullNameAttribute()
    {
        return $this->nombre . ' ' . $this->paterno . ($this->materno != NULL && $this->materno != '' ? ' ' . $this->materno : '');
    }

    public function getUrlFotoAttribute()
    {
        if ($this->foto) {
            return asset("imgs/pacientes/" . $this->foto);
        }
        return asset("imgs/pacientes/default.png");
    }

    public function getFotoB64Attribute()
    {
        $path = public_path("imgs/pacientes/" . $this->foto);
        if (!$this->foto || !file_exists($path)) {
            $path = public_path("imgs/pacientes/default.png");
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
