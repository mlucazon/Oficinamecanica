<?php

namespace App\Http\Controllers;

use App\Models\ItemOs;
use App\Models\OrdemServico;
use App\Models\Peca;
use Illuminate\Http\Request;

class ItemOsController extends Controller
{
    public function store(Request $request, $id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        abort_unless(auth()->user()->isAtendente() || auth()->user()->isGerente(), 403);

        $data = $request->validate([
            'tipo'           => 'required|in:servico,peca',
            'servico_id'     => 'required_if:tipo,servico|nullable|exists:servicos,id',
            'peca_id'        => 'required_if:tipo,peca|nullable|exists:pecas,id',
            'descricao'      => 'nullable|string|max:255',
            'quantidade'     => 'required|numeric|min:0.001',
            'valor_unitario' => 'required|numeric|min:0',
            'diagnostico'    => 'nullable|string|max:5000',
            'observacoes'    => 'nullable|string|max:5000',
        ]);

        $ordemServico->update([
            'diagnostico' => $data['diagnostico'] ?? $ordemServico->diagnostico,
            'observacoes' => $data['observacoes'] ?? $ordemServico->observacoes,
        ]);

        unset($data['diagnostico'], $data['observacoes']);

        if ($data['tipo'] === 'servico' && isset($data['servico_id'])) {
            $servico = \App\Models\Servico::findOrFail($data['servico_id']);
            $data['descricao'] = $data['descricao'] ?: $servico->nome;
            $data['valor_unitario'] = $data['valor_unitario'] ?: $servico->valor_mao_obra;
            $data['peca_id'] = null;
        }

        if ($data['tipo'] === 'peca' && isset($data['peca_id'])) {
            $peca = Peca::findOrFail($data['peca_id']);
            if ($peca->estoque < $data['quantidade']) {
                return back()->withInput()->with('error', "Estoque insuficiente. Disponível: {$peca->estoque} {$peca->unidade}.");
            }
            $data['descricao'] = $data['descricao'] ?: $peca->nome;
            $data['valor_unitario'] = $data['valor_unitario'] ?: $peca->preco_venda;
            $data['servico_id'] = null;
            $peca->decrement('estoque', $data['quantidade']);
        }

        $ordemServico->itens()->create($data);
        return back()->with('success', 'Item adicionado!');
    }

    public function update(Request $request, $id, $itemId)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        abort_unless(auth()->user()->isAtendente() || auth()->user()->isGerente(), 403);

        $item = ItemOs::findOrFail($itemId);
        $data = $request->validate([
            'descricao'      => 'required|string|max:255',
            'quantidade'     => 'required|numeric|min:0.001',
            'valor_unitario' => 'required|numeric|min:0',
        ]);
        $item->update($data);
        return back()->with('success', 'Item atualizado!');
    }

    public function destroy($id, $itemId)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        abort_unless(auth()->user()->isAtendente() || auth()->user()->isGerente(), 403);

        $item = ItemOs::findOrFail($itemId);
        $item->delete();
        return back()->with('success', 'Item removido.');
    }
}
