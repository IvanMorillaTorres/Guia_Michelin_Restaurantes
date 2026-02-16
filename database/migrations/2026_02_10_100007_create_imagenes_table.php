<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crear la tabla de imagenes de los restaurantes
    public function up(): void
    {
        Schema::create('imagenes', function (Blueprint $table) {
            $table->id('id_imagenes');
            $table->string('imagen');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->unsignedBigInteger('id_restaurante');
            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurantes');
        });
    }

    // Borrar la tabla
    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
