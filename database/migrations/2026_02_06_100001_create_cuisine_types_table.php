<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuisine_types', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
<<<<<<< HEAD
            $table->string('slug')->unique();
=======
>>>>>>> 5ba3659da2c0677e98dc44bed1a8c996838cde51
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuisine_types');
    }
};
