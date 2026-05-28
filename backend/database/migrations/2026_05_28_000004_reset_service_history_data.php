<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'avaliacoes_os',
            'notificacoes',
            'garantias',
            'fotos_os',
            'itens_os',
            'ordens_servico',
        ];

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        Storage::disk('public')->deleteDirectory('os');
        Storage::disk('public')->deleteDirectory('avaliacoes');
    }

    public function down(): void
    {
        // This migration intentionally only clears generated service history.
    }
};
