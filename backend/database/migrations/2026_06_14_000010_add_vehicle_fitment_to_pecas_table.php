<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pecas', function (Blueprint $table) {
            $table->foreignId('marca_veiculo_id')->nullable()->after('fabricante')->constrained('marcas_veiculos')->nullOnDelete();
            $table->foreignId('modelo_veiculo_id')->nullable()->after('marca_veiculo_id')->constrained('modelos_veiculos')->nullOnDelete();
            $table->index(['marca_veiculo_id', 'modelo_veiculo_id']);
        });
    }

    public function down(): void
    {
        Schema::table('pecas', function (Blueprint $table) {
            $table->dropForeign(['marca_veiculo_id']);
            $table->dropForeign(['modelo_veiculo_id']);
            $table->dropIndex(['marca_veiculo_id', 'modelo_veiculo_id']);
            $table->dropColumn(['marca_veiculo_id', 'modelo_veiculo_id']);
        });
    }
};
