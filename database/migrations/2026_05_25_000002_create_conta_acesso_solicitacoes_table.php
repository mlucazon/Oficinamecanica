<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conta_acesso_solicitacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('gerente_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pendente', 'aprovada', 'recusada'])->default('pendente');
            $table->timestamp('respondido_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conta_acesso_solicitacoes');
    }
};
