<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('provincia');
<<<<<<< HEAD
            $table->string('comunidad_autonoma');
            $table->string('pais')->default('España');
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
=======
            $table->string('comunidad_autonoma')->default('Catalunya');
            $table->string('pais')->default('España');
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
>>>>>>> 5ba3659da2c0677e98dc44bed1a8c996838cde51
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
