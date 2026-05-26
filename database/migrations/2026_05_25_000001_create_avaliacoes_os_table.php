<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes_os', function (Blueprint $table) {
            $table->id();
            $table->foreignId('os_id')->unique()->constrained('ordens_servico')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->unsignedTinyInteger('nota');
            $table->text('comentario');
            $table->string('foto_antes_path')->nullable();
            $table->string('foto_depois_path')->nullable();
            $table->text('resposta')->nullable();
            $table->foreignId('respondido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('respondido_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes_os');
    }
};
