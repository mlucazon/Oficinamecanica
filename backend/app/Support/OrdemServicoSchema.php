<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrdemServicoSchema
{
    public static function ensureStatusEnum(): void
    {
        if (! Schema::hasTable('ordens_servico')) {
            return;
        }

        DB::statement("ALTER TABLE ordens_servico MODIFY status ENUM('aguardando_aceitacao','solicitacao_aceita','solicitacao_recusada','em_diagnostico','orcamento_enviado_atendente','aguardando_aprovacao','aprovada','em_execucao','aguardando_finalizacao','aguardando_pecas','finalizada','cancelada','aberta') NOT NULL DEFAULT 'aguardando_aceitacao'");
    }
}
