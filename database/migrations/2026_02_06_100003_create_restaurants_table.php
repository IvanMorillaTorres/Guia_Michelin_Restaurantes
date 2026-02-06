<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->foreignId('city_id')->constrained('cities');
            $table->string('direccion');
            $table->string('codigo_postal', 10);
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->decimal('precio_medio', 8, 2)->nullable()->comment('Precio medio por persona');
            $table->string('rango_precios')->nullable()->comment('€, €€, €€€, €€€€');
            $table->integer('estrellas_michelin')->default(0)->comment('0, 1, 2, 3 estrellas');
            $table->boolean('estrella_verde')->default(false)->comment('Estrella verde sostenibilidad');
            $table->boolean('bib_gourmand')->default(false);
            $table->text('horario')->nullable();
            $table->string('dia_cierre')->nullable();
            $table->integer('capacidad')->nullable();
            $table->boolean('terraza')->default(false);
            $table->boolean('parking')->default(false);
            $table->boolean('accesible')->default(false);
            $table->string('chef')->nullable();
            $table->decimal('valoracion', 3, 1)->default(0)->comment('Valoración de 0 a 5');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['city_id', 'activo']);
            $table->index(['estrellas_michelin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
