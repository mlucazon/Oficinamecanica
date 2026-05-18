<?php

namespace App\Http\Controllers;

use App\Models\Garantia;
use Illuminate\Http\Request;

class GarantiaController extends Controller
{
    public function index()
    {
        $garantias = Garantia::with('ordemServico.cliente')->orderByDesc('data_fim')->paginate(20);
        return view('garantias.index', compact('garantias'));
    }

    public function show(Garantia $garantia)
    {
        $garantia->load('ordemServico.cliente');
        return view('garantias.show', compact('garantia'));
    }

    public function edit(Garantia $garantia)
    {
        return view('garantias.edit', compact('garantia'));
    }

    public function update(Request $request, Garantia $garantia)
    {
        $data = $request->validate([
            'descricao'  => 'required|string',
            'data_fim'   => 'required|date',
            'observacao' => 'nullable|string',
        ]);
        $garantia->update($data);
        return redirect()->route('garantias.show', $garantia)->with('success', 'Garantia atualizada!');
    }

    public function acionar(Request $request, Garantia $garantia)
    {
        $request->validate(['observacao' => 'required|string|max:1000']);
        if ($garantia->status !== 'aceita') {
            return back()->with('error', 'Esta garantia ainda não foi aceita pelo cliente.');
        }
        if ($garantia->expirada()) {
            return back()->with('error', 'Garantia expirada em ' . $garantia->data_fim->format('d/m/Y') . '.');
        }
        $garantia->update([
            'acionada'         => true,
            'data_acionamento' => now(),
            'observacao'       => $request->observacao,
        ]);
        return back()->with('success', 'Garantia acionada!');
    }

    public function aceitarOferta(Garantia $garantia)
    {
        $garantia->load('ordemServico.cliente');

        if (!auth()->user()->isCliente() || $garantia->ordemServico->cliente?->user_id !== auth()->id()) {
            abort(403);
        }

        if ($garantia->status !== 'pendente') {
            return back()->with('error', 'Esta oferta de garantia já foi respondida.');
        }

        $garantia->update([
            'status' => 'aguardando_pagamento',
            'observacao' => 'Cliente aceitou a garantia adicional de 60 dias. Aguardando pagamento.',
        ]);

        return back()->with('success', 'Garantia escolhida. Agora confirme o pagamento para ativar.');
    }

    public function recusarOferta(Garantia $garantia)
    {
        $garantia->load('ordemServico.cliente');

        if (!auth()->user()->isCliente() || $garantia->ordemServico->cliente?->user_id !== auth()->id()) {
            abort(403);
        }

        if ($garantia->status !== 'pendente') {
            return back()->with('error', 'Esta oferta de garantia já foi respondida.');
        }

        $garantia->update([
            'status' => 'recusada',
            'observacao' => 'Cliente recusou a garantia adicional de 60 dias.',
        ]);

        \App\Models\Notificacao::where('user_id', auth()->id())
            ->where('os_id', $garantia->os_id)
            ->where('status', 'pendente')
            ->update(['status' => 'recusada', 'lida' => true]);

        return back()->with('success', 'Garantia recusada. Nenhum valor adicional foi aplicado.');
    }

    public function pagarOferta(Request $request, Garantia $garantia)
    {
        $garantia->load('ordemServico.cliente');

        if (!auth()->user()->isCliente() || $garantia->ordemServico->cliente?->user_id !== auth()->id()) {
            abort(403);
        }

        if ($garantia->status !== 'aguardando_pagamento') {
            return back()->with('error', 'Esta garantia não está aguardando pagamento.');
        }

        $data = $request->validate([
            'metodo_pagamento' => 'required|in:pix,cartao,dinheiro',
        ]);

        $metodo = match ($data['metodo_pagamento']) {
            'pix' => 'Pix',
            'cartao' => 'Cartão',
            default => 'Dinheiro/presencial',
        };

        $garantia->update([
            'status' => 'aceita',
            'data_inicio' => today(),
            'data_fim' => today()->addDays(60),
            'observacao' => 'Pagamento confirmado via ' . $metodo . '. Garantia adicional de 60 dias ativa.',
        ]);

        \App\Models\Notificacao::where('user_id', auth()->id())
            ->where('os_id', $garantia->os_id)
            ->where('status', 'pendente')
            ->update(['status' => 'aceita', 'lida' => true]);

        return back()->with('success', 'Pagamento confirmado. Garantia de 60 dias ativada.');
    }
}
