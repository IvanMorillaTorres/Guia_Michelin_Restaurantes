<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crear la tabla de restaurantes
    public function up(): void
    {
        Schema::create('restaurantes', function (Blueprint $table) {
            $table->id('id_restaurante');
            $table->string('nombre_restaurante');
            $table->string('slug')->unique();
            $table->string('telefono_restaurante', 20)->nullable();
            $table->decimal('precio_restaurante', 8, 2)->nullable();
            $table->text('descripcion_restaurante')->nullable();
            $table->decimal('valoracion_restaurante', 3, 1)->default(0);
            $table->string('web_real_restaurante')->nullable();
            $table->timestamp('fecha_creacion_restaurante')->useCurrent();
            $table->timestamp('fecha_edicion_restaurante')->nullable()->useCurrentOnUpdate();
            $table->unsignedBigInteger('id_ciudad');
            $table->foreign('id_ciudad')->references('id_ciudad')->on('ciudades')->onDelete('restrict');
        });
    }

    // Borrar la tabla
    public function down(): void
    {
        Schema::dropIfExists('restaurantes');
    }
};
