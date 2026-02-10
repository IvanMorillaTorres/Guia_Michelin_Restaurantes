<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crear la tabla pivote entre restaurantes y estilos
    public function up(): void
    {
        Schema::create('rest_estilos', function (Blueprint $table) {
            $table->id('id_rest_estilos');
            $table->unsignedBigInteger('id_estilo');
            $table->unsignedBigInteger('id_restaurante');
            $table->foreign('id_estilo')->references('id_estilo')->on('estilos')->onDelete('cascade');
            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurantes')->onDelete('cascade');
            $table->unique(['id_estilo', 'id_restaurante']);
        });
    }

    // Borrar la tabla
    public function down(): void
    {
        Schema::dropIfExists('rest_estilos');
    }
};
