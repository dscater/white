<?php

namespace App\Models;

use App\Http\Controllers\EntrenamientoImagenController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrenamientoImagen extends Model
{
    use HasFactory;

    protected $fillable = [
        "entrenamiento_id",
        "imagen",
    ];

    protected $appends = ["url_imagen", "url_archivo", "url_file", "name", "ext"];


    public function getExtAttribute()
    {
        return EntrenamientoImagenController::getExtensionImagenDB($this->imagen);
    }

    public function getNameAttribute()
    {
        return $this->imagen;
    }

    public function getUrlArchivoAttribute()
    {
        return asset("imgs/entrenamientos/" . $this->imagen);
    }

    public function getUrlFileAttribute()
    {
        $array_imgs = ["jpg", "jpeg", "png", "webp", "gif"];
        $ext = EntrenamientoImagenController::getExtensionImagenDB($this->imagen);
        if (in_array($ext, $array_imgs)) {
            return asset("/imgs/entrenamientos/" . $this->imagen);
        }
        return asset("/imgs/attach.png");
    }

    public function getUrlImagenAttribute()
    {
        return asset("imgs/entrenamientos/" . $this->imagen);
    }

    public function entrenamiento()
    {
        return $this->belongsTo(Entrenamiento::class, 'entrenamiento_id');
    }
}
