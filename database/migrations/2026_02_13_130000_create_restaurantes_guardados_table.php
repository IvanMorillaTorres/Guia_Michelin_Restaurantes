<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurantes_guardados', function (Blueprint $table) {
            $table->id('id_guardado');
            $table->unsignedBigInteger('id_users');
            $table->unsignedBigInteger('id_restaurante');
            $table->timestamps();

            $table->foreign('id_users')
                ->references('id_users')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_restaurante')
                ->references('id_restaurante')
                ->on('restaurantes')
                ->onDelete('cascade');

            $table->unique(['id_users', 'id_restaurante']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurantes_guardados');
    }
};
