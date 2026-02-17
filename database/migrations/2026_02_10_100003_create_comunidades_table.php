<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crear la tabla de comunidades autonomas
    public function up(): void
    {
        Schema::create('comunidades', function (Blueprint $table) {
            $table->id('id_comunidad');
            $table->string('nombre_comunidad');
            $table->string('abreviatura_comunidad', 10)->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_edicion')->nullable()->useCurrentOnUpdate();
            $table->unsignedBigInteger('id_pais');
            $table->foreign('id_pais')->references('id_pais')->on('paises');
        });
    }

    // Borrar la tabla
    public function down(): void
    {
        Schema::dropIfExists('comunidades');
    }
};
