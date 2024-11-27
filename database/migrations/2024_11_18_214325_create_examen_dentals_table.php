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
        Schema::create('examen_dentals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("paciente_id");
            $table->string("dolencia_actual", 300);
            $table->string("imagen1")->nullable();
            $table->string("imagen2")->nullable();
            $table->string("resultado");
            $table->date("fecha_registro");
            $table->timestamps();
            $table->foreign("paciente_id")->on("pacientes")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_dentals');
    }
};
