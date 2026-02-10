<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crear la tabla de paises
    public function up(): void
    {
        Schema::create('paises', function (Blueprint $table) {
            $table->id('id_pais');
            $table->string('nombre');
            $table->string('abreviatura', 10)->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_edit')->nullable()->useCurrentOnUpdate();
        });
    }

    // Borrar la tabla
    public function down(): void
    {
        Schema::dropIfExists('paises');
    }
};
