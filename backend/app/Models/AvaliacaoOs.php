<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoOs extends Model
{
    protected $table = 'avaliacoes_os';

    protected $fillable = [
        'os_id',
        'cliente_id',
        'nota',
        'comentario',
        'foto_antes_path',
        'foto_depois_path',
        'resposta',
        'respondido_por',
        'respondido_em',
    ];

    protected $casts = [
        'respondido_em' => 'datetime',
    ];

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'os_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function respondente()
    {
        return $this->belongsTo(User::class, 'respondido_por');
    }

    public function fotoAntesUrl(): ?string
    {
        return $this->foto_antes_path ? route('media.public', ['path' => $this->foto_antes_path]) : null;
    }

    public function fotoDepoisUrl(): ?string
    {
        return $this->foto_depois_path ? route('media.public', ['path' => $this->foto_depois_path]) : null;
    }
}
