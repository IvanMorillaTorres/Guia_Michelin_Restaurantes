<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crear la tabla de ciudades
    public function up(): void
    {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id('id_ciudad');
            $table->string('nombre_ciudad');
            $table->string('codigo_postal_ciudad', 10)->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_edicion')->nullable()->useCurrentOnUpdate();
            $table->unsignedBigInteger('id_comunidad');
            $table->foreign('id_comunidad')->references('id_comunidad')->on('comunidades');
        });
    }

    // Borrar la tabla
    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};
