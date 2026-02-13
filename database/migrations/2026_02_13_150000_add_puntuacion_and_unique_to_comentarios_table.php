<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            // default 0 para no romper si ya hay comentarios existentes
            $table->unsignedTinyInteger('puntuacion')->default(0)->after('id_users');
            $table->unique(['id_restaurante', 'id_users']);
        });
    }

    public function down(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            $table->dropUnique(['id_restaurante', 'id_users']);
            $table->dropColumn('puntuacion');
        });
    }
};
