<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->string('ruta');
            $table->string('alt_text')->nullable();
            $table->boolean('es_principal')->default(false);
            $table->integer('orden')->default(0);
            $table->timestamps();
            
            $table->index(['restaurant_id', 'es_principal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_images');
    }
};
