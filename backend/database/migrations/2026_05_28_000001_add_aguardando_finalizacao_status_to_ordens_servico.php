<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE ordens_servico MODIFY status ENUM('aguardando_aceitacao','solicitacao_aceita','solicitacao_recusada','em_diagnostico','orcamento_enviado_atendente','aguardando_aprovacao','aprovada','em_execucao','aguardando_finalizacao','aguardando_pecas','finalizada','cancelada','aberta') NOT NULL DEFAULT 'aguardando_aceitacao'");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE ordens_servico MODIFY status ENUM('aguardando_aceitacao','solicitacao_aceita','solicitacao_recusada','em_diagnostico','orcamento_enviado_atendente','aguardando_aprovacao','aprovada','em_execucao','aguardando_pecas','finalizada','cancelada','aberta') NOT NULL DEFAULT 'aguardando_aceitacao'");
    }
};
