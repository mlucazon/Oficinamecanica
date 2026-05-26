<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use App\Models\Notificacao;
use App\Models\OrdemServico;
use App\Models\Peca;
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

        $mecanicos = Mecanico::where('ativo', true)->orderBy('nome')->get();
        $pecas_criticas = auth()->user()->isMecanico()
            ? Peca::whereColumn('estoque', '<=', 'estoque_minimo')->orderBy('estoque')->get()
            : collect();

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

        if (!$mecanico) {
            $defaultEmail = env('OS_DEFAULT_MECANICO_EMAIL', 'jose@autotech.com');
            $mecanico = Mecanico::whereHas('user', fn($q) => $q->where('email', $defaultEmail))->first();

            if (!$mecanico) {
                return redirect()->back()->with('error', 'Nenhum mecânico disponível para receber esta OS.');
            }
        }

        $notificacao->os->update([
            'status' => 'em_diagnostico',
            'mecanico_id' => $mecanico->id,
        ]);

        if ($mecanico->user_id) {
            Notificacao::create([
                'user_id' => $mecanico->user_id,
                'os_id' => $notificacao->os->id,
                'tipo' => 'atualizacao',
                'status' => 'pendente',
                'mensagem' => 'O atendente encaminhou para voce a OS ' . $notificacao->os->numero . ' do cliente ' . $notificacao->os->cliente->nome . '. Faca o diagnostico e monte o orcamento.',
            ]);
        }

        return redirect()->route('os.show', $notificacao->os)
            ->with('success', 'OS aceita! Encaminhada para o mecânico ' . $mecanico->nome . '.');
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
