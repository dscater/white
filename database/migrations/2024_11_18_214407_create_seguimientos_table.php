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
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("paciente_id");
            $table->unsignedBigInteger("examen_dental_id");
            $table->string("pieza");
            $table->string("estado");
            $table->string("observacion", 400)->nullable();
            $table->date("fecha_registro");
            $table->timestamps();

            $table->foreign("paciente_id")->on("pacientes")->references("id");
            $table->foreign("examen_dental_id")->on("examen_dentals")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
