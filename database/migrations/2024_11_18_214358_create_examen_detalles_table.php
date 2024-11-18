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
        Schema::create('examen_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("examen_dental_id");
            $table->string("pieza");
            $table->string("diagnostico", 200);
            $table->string("observaciones", 200);
            $table->timestamps();

            $table->foreign("examen_dental_id")->on("examen_dentals")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_detalles');
    }
};
