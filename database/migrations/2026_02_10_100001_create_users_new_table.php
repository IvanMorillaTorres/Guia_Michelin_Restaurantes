<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crear la tabla de usuarios
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_users');
            $table->string('nombre');
            $table->string('apellido1');
            $table->string('apellido2')->nullable();
            $table->string('nombre_del_atributo')->nullable();
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->string('telefono', 20)->nullable();
            $table->date('nacimiento')->nullable();
            $table->string('estado')->default('activo');
            $table->unsignedBigInteger('id_rol');
            $table->foreign('id_rol')->references('id_rol')->on('roles')->onDelete('restrict');
            $table->timestamps();
        });
    }

    // Borrar la tabla
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
