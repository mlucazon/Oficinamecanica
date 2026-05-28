<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cartoes_cliente')) {
            return;
        }

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
    }

    public function down(): void
    {
        Schema::dropIfExists('cartoes_cliente');
    }
};
