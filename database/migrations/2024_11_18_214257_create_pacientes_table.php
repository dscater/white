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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("paterno");
            $table->string("materno")->nullable();
            $table->string("ci")->nullable();
            $table->string("ci_exp")->nullable();
            $table->date("fecha_nac")->nullable();
            $table->string("sexo");
            $table->string("estado_civil");
            $table->string("nacionalidad");
            $table->string("lugar_nac", 400)->nullable();
            $table->string("ocupacion")->nullable();
            $table->string("dir");
            $table->string("fono");
            $table->string("correo");
            $table->string("nom_familiar", 400);
            $table->string("fono_familiar");
            $table->string("foto")->nullable();
            $table->date("fecha_registro")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
