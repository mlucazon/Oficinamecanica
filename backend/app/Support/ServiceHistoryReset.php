<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Storage;

class ServiceHistoryReset
{
    public static function runOnce(string $key): void
    {
        self::ensureFlagsTable();

        if (DB::table('app_flags')->where('key', $key)->exists()) {
            return;
        }

        self::clear();

        DB::table('app_flags')->insert([
            'key' => $key,
            'value' => now()->toDateTimeString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function clear(): void
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

    private static function ensureFlagsTable(): void
    {
        if (Schema::hasTable('app_flags')) {
            return;
        }

        Schema::create('app_flags', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }
}
