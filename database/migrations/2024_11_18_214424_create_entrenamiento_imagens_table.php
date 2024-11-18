<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entrenamiento_imagens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("entrenamiento_id");
            $table->string("imagen");
            $table->timestamps();

            $table->foreign("entrenamiento_id")->on("entrenamientos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrenamiento_imagens');
    }
};
