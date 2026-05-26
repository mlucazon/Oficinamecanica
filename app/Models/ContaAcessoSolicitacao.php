<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContaAcessoSolicitacao extends Model
{
    protected $table = 'conta_acesso_solicitacoes';

    protected $fillable = [
        'solicitante_id',
        'gerente_id',
        'status',
        'respondido_em',
    ];

    protected $casts = [
        'respondido_em' => 'datetime',
    ];

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function gerente()
    {
        return $this->belongsTo(User::class, 'gerente_id');
    }
}
