<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peca extends Model
{
    protected $fillable = ['nome','codigo','fabricante','marca_veiculo_id','modelo_veiculo_id','preco_custo','preco_venda','estoque','estoque_minimo','unidade','ativo'];
    protected $casts    = ['ativo' => 'boolean'];

    public function itens() { return $this->hasMany(ItemOs::class, 'peca_id'); }
    public function marcaVeiculo() { return $this->belongsTo(MarcasVeiculos::class, 'marca_veiculo_id'); }
    public function modeloVeiculo() { return $this->belongsTo(ModelosVeiculos::class, 'modelo_veiculo_id'); }

    public function estoqueAbaixoMinimo(): bool
    {
        return $this->estoque <= $this->estoque_minimo;
    }
}
