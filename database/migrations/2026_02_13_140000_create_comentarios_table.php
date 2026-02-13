<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id('id_comentario');
            $table->unsignedBigInteger('id_restaurante');
            $table->unsignedBigInteger('id_users');
            $table->text('texto');
            $table->timestamps();

            $table->foreign('id_restaurante')
                ->references('id_restaurante')
                ->on('restaurantes')
                ->onDelete('cascade');

            $table->foreign('id_users')
                ->references('id_users')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
