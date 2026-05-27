<?php

namespace App\Http\Controllers;

use App\Models\Garantia;
use App\Models\Notificacao;
use Illuminate\Http\Request;

class GarantiaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Garantia::with('ordemServico.cliente')->orderByDesc('data_fim');

        if ($user->isCliente()) {
            $query->whereHas('ordemServico.cliente', fn($q) => $q->where('user_id', $user->id));
        }

        if ($user->isMecanico()) {
            $query->whereHas('ordemServico', fn($q) => $q->where('mecanico_id', $user->mecanico?->id));
        }

        $garantias = $query->paginate(20);

        return view('garantias.index', compact('garantias'));
    }

    public function show(Garantia $garantia)
    {
        $garantia->load('ordemServico.cliente');
        $this->authorizeGarantia($garantia);

        return view('garantias.show', compact('garantia'));
    }

    public function edit(Garantia $garantia)
    {
        $this->authorizeGarantia($garantia, true);

        return view('garantias.edit', compact('garantia'));
    }

    public function update(Request $request, Garantia $garantia)
    {
        $this->authorizeGarantia($garantia, true);

        $data = $request->validate([
            'descricao' => 'required|string',
            'data_fim' => 'required|date',
            'observacao' => 'nullable|string',
        ]);

        $garantia->update($data);

        return redirect()->route('garantias.show', $garantia)->with('success', 'Garantia atualizada!');
    }

    public function acionar(Request $request, Garantia $garantia)
    {
        $this->authorizeGarantia($garantia);

        $request->validate(['observacao' => 'required|string|max:1000']);

        if ($garantia->status !== 'aceita') {
            return back()->with('error', 'Esta garantia ainda nao foi aceita pelo cliente.');
        }

        if ($garantia->expirada()) {
            return back()->with('error', 'Garantia expirada em ' . $garantia->data_fim->format('d/m/Y') . '.');
        }

        $garantia->update([
            'acionada' => true,
            'data_acionamento' => now(),
            'observacao' => $request->observacao,
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
            return back()->with('error', 'Esta oferta de garantia ja foi respondida.');
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

        if (!in_array($garantia->status, ['pendente', 'aguardando_pagamento'], true)) {
            return back()->with('error', 'Esta oferta de garantia ja foi respondida.');
        }

        $garantia->update([
            'status' => 'recusada',
            'observacao' => $garantia->status === 'aguardando_pagamento'
                ? 'Cliente desistiu do pagamento da garantia adicional de 60 dias.'
                : 'Cliente recusou a garantia adicional de 60 dias.',
        ]);

        Notificacao::where('user_id', auth()->id())
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
            return back()->with('error', 'Esta garantia nao esta aguardando pagamento.');
        }

        $data = $request->validate([
            'metodo_pagamento' => 'required|in:pix,cartao,dinheiro',
        ]);

        $metodo = match ($data['metodo_pagamento']) {
            'pix' => 'Pix',
            'cartao' => 'Cartao',
            default => 'Dinheiro/presencial',
        };

        $garantia->update([
            'status' => 'aceita',
            'data_inicio' => today(),
            'data_fim' => today()->addDays(60),
            'observacao' => 'Pagamento confirmado via ' . $metodo . '. Garantia adicional de 60 dias ativa.',
        ]);

        Notificacao::where('user_id', auth()->id())
            ->where('os_id', $garantia->os_id)
            ->where('status', 'pendente')
            ->update(['status' => 'aceita', 'lida' => true]);

        return back()->with('success', 'Pagamento confirmado. Garantia de 60 dias ativada.');
    }

    private function authorizeGarantia(Garantia $garantia, bool $gerencial = false): void
    {
        $user = auth()->user();
        $garantia->loadMissing('ordemServico.cliente');

        if ($gerencial) {
            abort_unless($user->isAtendente() || $user->isGerente(), 403);
            return;
        }

        if ($user->isCliente()) {
            abort_unless($garantia->ordemServico->cliente?->user_id === $user->id, 403);
            return;
        }

        if ($user->isMecanico()) {
            abort_unless($garantia->ordemServico->mecanico_id === $user->mecanico?->id, 403);
        }
    }
}
