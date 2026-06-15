<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    // Mostrar notificações para assistente/gerente
    public function index()
    {
        $this->sincronizarPendentesDoUsuario();

        $notificacoes_pendentes = Notificacao::where('user_id', auth()->id())
            ->where('status', 'pendente')
            ->with(['os.cliente', 'os.veiculo'])
            ->when(auth()->user()->isAtendente() || auth()->user()->isGerente(), function ($query) {
                $query->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('tipo', 'solicitacao_os')
                            ->whereHas('os', fn($os) => $os->where('status', 'aguardando_aceitacao'));
                    })->orWhereHas('os', fn($os) => $os->whereIn('status', [
                        'orcamento_enviado_atendente',
                        'aguardando_aprovacao',
                    ]));
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $notificacoes_respondidas = Notificacao::where('user_id', auth()->id())
            ->whereIn('status', ['aceita', 'recusada'])
            ->with(['os.cliente', 'os.veiculo'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $mecanicos = Mecanico::livres()->orderBy('nome')->get();
        $pecas_criticas = collect();

        return view('notificacoes.index', compact('notificacoes_pendentes', 'notificacoes_respondidas', 'mecanicos', 'pecas_criticas'));
    }

    private function sincronizarPendentesDoUsuario(): void
    {
        if (!auth()->user()->isAtendente() && !auth()->user()->isGerente()) {
            return;
        }

        Notificacao::where('user_id', auth()->id())
            ->where('status', 'pendente')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('tipo', 'solicitacao_os')
                        ->whereHas('os', fn($os) => $os->where('status', '!=', 'aguardando_aceitacao'));
                })->orWhere(function ($q) {
                    $q->where('tipo', 'atualizacao')
                        ->whereHas('os', fn($os) => $os->whereNotIn('status', [
                            'orcamento_enviado_atendente',
                            'aguardando_aprovacao',
                        ]));
                });
            })
            ->update([
                'status' => 'aceita',
                'lida' => true,
            ]);
    }

    // Aceitar OS
    public function aceitar(Notificacao $notificacao, Request $request)
    {
        if (!auth()->user()->isAtendente() && !auth()->user()->isGerente()) {
            abort(403);
        }

        if ($notificacao->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Sem permissão');
        }

        $data = $request->validate([
            'mecanico_id' => ['required', 'exists:mecanicos,id'],
        ], [
            'mecanico_id.required' => 'Selecione o mecânico responsável.',
            'mecanico_id.exists' => 'O mecânico selecionado não foi encontrado.',
        ]);

        $notificacao->update(['status' => 'aceita', 'lida' => true]);

        $mecanico = Mecanico::findOrFail($data['mecanico_id']);

        if (! Mecanico::livres()->whereKey($mecanico->id)->exists()) {
            return redirect()->back()->with('error', 'Este mecanico nao esta livre no momento. Escolha outro ou avise o cliente sobre a indisponibilidade.');
        }

        $notificacao->os->update([
            'status' => 'em_diagnostico',
            'mecanico_id' => $mecanico->id,
        ]);

        return redirect()->route('os.show', $notificacao->os)
            ->with('success', 'OS aceita! Mecanico responsavel: ' . $mecanico->nome . '.');
    }

    public function avisarSemMecanico(Notificacao $notificacao)
    {
        if (!auth()->user()->isAtendente() && !auth()->user()->isGerente()) {
            abort(403);
        }

        if ($notificacao->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Sem permissao');
        }

        $notificacao->loadMissing('os.cliente.user');

        if ($notificacao->os?->cliente?->user_id) {
            Notificacao::create([
                'user_id' => $notificacao->os->cliente->user_id,
                'os_id' => $notificacao->os->id,
                'tipo' => 'atualizacao',
                'status' => 'pendente',
                'mensagem' => 'No momento todos os mecanicos estao ocupados. Voce pode deixar o carro na oficina para ser avaliado assim que houver disponibilidade, ou deixar para trazer depois.',
            ]);
        }

        return redirect()->route('notificacoes.index')
            ->with('success', 'Cliente avisado sobre a indisponibilidade de mecanicos.');
    }

    // Recusar OS
    public function recusar(Notificacao $notificacao, Request $request)
    {
        if (!auth()->user()->isAtendente() && !auth()->user()->isGerente()) {
            abort(403);
        }

        if ($notificacao->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Sem permissão');
        }

        $request->validate(['motivo' => 'required|string|max:255']);

        $notificacao->update([
            'status' => 'recusada',
            'lida' => true,
            'mensagem' => $request->motivo
        ]);

        $notificacao->os->update(['status' => 'aberta', 'mecanico_id' => null]);

        return redirect()->route('notificacoes.index')
            ->with('success', 'OS recusada.');
    }

    // Marcar como lida (via AJAX)
    public function marcarLida(Notificacao $notificacao)
    {
        if ($notificacao->user_id !== auth()->id()) {
            return response()->json(['error' => 'Sem permissão'], 403);
        }

        $notificacao->update(['lida' => true]);

        return response()->json(['success' => true]);
    }

    public function confirmar(Notificacao $notificacao)
    {
        if ($notificacao->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Sem permissao.');
        }

        if (! auth()->user()->isCliente()) {
            abort(403);
        }

        $notificacao->update([
            'status' => 'aceita',
            'lida' => true,
        ]);

        return redirect()
            ->route('notificacoes.index')
            ->with('success', 'Notificacao confirmada.');
    }

    public function marcarTodasLidas()
    {
        Notificacao::where('user_id', auth()->id())
            ->where('lida', false)
            ->where('status', 'pendente')
            ->update(['lida' => true]);

        return response()->json(['success' => true]);
    }

    public function limpar()
    {
        Notificacao::where('user_id', auth()->id())
            ->whereIn('status', ['aceita', 'recusada'])
            ->delete();

        return redirect()->route('notificacoes.index')
            ->with('success', 'Histórico de notificações limpo.');
    }

    // Contar não lidas (para badge)
    public function contarNaoLidas()
    {
        $count = Notificacao::where('user_id', auth()->id())
            ->where('lida', false)
            ->where('status', 'pendente')
            ->count();

        return response()->json(['count' => $count]);
    }
}
