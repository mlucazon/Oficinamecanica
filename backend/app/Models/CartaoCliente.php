<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartaoCliente extends Model
{
    protected $table = 'cartoes_cliente';

    protected $fillable = [
        'user_id',
        'tipo',
        'bandeira',
        'titular',
        'final',
        'validade',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
