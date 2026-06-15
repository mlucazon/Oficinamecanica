<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marcas_veiculos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->timestamps();
        });

        Schema::create('modelos_veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_id')->constrained('marcas_veiculos')->cascadeOnDelete();
            $table->string('nome');
            $table->timestamps();

            $table->unique(['marca_id', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modelos_veiculos');
        Schema::dropIfExists('marcas_veiculos');
    }
};
