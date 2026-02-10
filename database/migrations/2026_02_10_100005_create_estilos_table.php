<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crear la tabla de estilos de cocina
    public function up(): void
    {
        Schema::create('estilos', function (Blueprint $table) {
            $table->id('id_estilo');
            $table->string('nombre_estilo');
            $table->text('descripcion_estilo')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_edicion')->nullable()->useCurrentOnUpdate();
        });
    }

    // Borrar la tabla
    public function down(): void
    {
        Schema::dropIfExists('estilos');
    }
};
