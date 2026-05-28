<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Throwable;

class CartoesClienteSchema
{
    public static function ensure(): void
    {
        if (Schema::hasTable('cartoes_cliente')) {
            return;
        }

        try {
            Schema::create('cartoes_cliente', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('tipo', ['debito', 'credito']);
                $table->string('bandeira', 30)->nullable();
                $table->string('titular', 120);
                $table->string('final', 4);
                $table->string('validade', 5);
                $table->timestamps();
            });
        } catch (Throwable $exception) {
            if (! Schema::hasTable('cartoes_cliente')) {
                throw $exception;
            }
        }
    }
}
