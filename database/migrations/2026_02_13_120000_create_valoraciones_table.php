<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('valoraciones', function (Blueprint $table) {
            $table->id('id_valoracion');
            $table->unsignedBigInteger('id_restaurante');
            $table->unsignedBigInteger('id_users');
            $table->unsignedTinyInteger('puntuacion');
            $table->timestamps();

            $table->foreign('id_restaurante')
                ->references('id_restaurante')
                ->on('restaurantes')
                ->onDelete('cascade');

            $table->foreign('id_users')
                ->references('id_users')
                ->on('users')
                ->onDelete('cascade');

            $table->unique(['id_restaurante', 'id_users']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valoraciones');
    }
};
