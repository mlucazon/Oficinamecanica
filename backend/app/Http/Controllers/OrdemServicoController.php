<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\Mecanico;
use App\Models\User;
use App\Models\Notificacao;
use App\Models\Garantia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrdemServicoController extends Controller
{
    // UC007 — Listar com filtros
    public function index(Request $request)
    {
        $query = OrdemServico::with(['cliente','veiculo','mecanico.user'])->latest();

        if ($request->filled('status'))     $query->where('status', $request->status);
        if ($request->filled('mecanico_id'))$query->where('mecanico_id', $request->mecanico_id);
        if ($request->filled('data_inicio'))$query->whereDate('created_at', '>=', $request->data_inicio);
        if ($request->filled('data_fim'))   $query->whereDate('created_at', '<=', $request->data_fim);

        if ($request->filled('busca')) {
            $b = $request->busca;
            $query->where(fn($q) =>
                $q->where('numero', 'like', "%{$b}%")
                  ->orWhereHas('cliente', fn($c) => $c->where('nome', 'like', "%{$b}%"))
                  ->orWhereHas('veiculo', fn($v) => $v->where('placa', 'like', "%{$b}%"))
            );
        }

        // Clientes só veem as próprias OS
        if (Auth::user()->isCliente()) {
            $query->whereHas('cliente', fn($q) => $q->where('user_id', Auth::id()));
        }

        if (Auth::user()->isMecanico()) {
            $query->where('mecanico_id', Auth::user()->mecanico?->id);
        }

        $ordens    = $query->paginate(20)->withQueryString();
        $mecanicos = Mecanico::where('ativo', true)->orderBy('nome')->get();

        return view('ordens-servico.index', compact('ordens', 'mecanicos'));
    }

    // UC003 — Formulário de abertura
    public function create()
    {
        abort_unless(Auth::user()->isCliente(), 403);

        $cliente = Auth::user()->cliente;
        abort_unless($cliente, 403);

        if (!$cliente->veiculos()->exists()) {
            return redirect()->route('conta.veiculos')
                ->with('error', 'Para abrir uma OS, primeiro cadastre um veiculo.');
        }

        $clientes  = collect([$cliente]);
        $mecanicos = Mecanico::where('ativo', true)->orderBy('nome')->get();
        return view('ordens-servico.create', compact('clientes', 'mecanicos'));
    }

    // UC003 — Salvar nova OS
    public function store(Request $request)
    {
        abort_unless(Auth::user()->isCliente(), 403);

        $cliente = Auth::user()->cliente;
        abort_unless($cliente, 403);

        if (!$cliente->veiculos()->exists()) {
            return redirect()->route('conta.veiculos')
                ->with('error', 'Para abrir uma OS, primeiro cadastre um veiculo.');
        }

        $data = $request->validate([
            'veiculo_id'  => 'required|exists:veiculos,id',
            'mecanico_id' => 'nullable|exists:mecanicos,id',
            'sintomas'    => 'required|string|max:2000',
            'km_entrada'  => 'nullable|integer|min:0',
            // Mídia enviada na abertura da OS (UC003)
            'foto_defeito' => 'required|image|mimes:jpeg,png,webp|max:5120',
            'video_defeito' => 'nullable|file|max:102400',
        ], [
            'video_defeito.file' => 'Envie um arquivo de video valido.',
            'video_defeito.max' => 'O video deve ter no maximo 100 MB.',
        ]);

        if ($request->hasFile('video_defeito')) {
            $video = $request->file('video_defeito');
            $extensao = strtolower($video->getClientOriginalExtension());
            $extensoesPermitidas = ['mp4', 'mov', 'm4v', 'webm', 'ogg', 'avi', '3gp', '3gpp'];

            if (!in_array($extensao, $extensoesPermitidas, true)) {
                return back()
                    ->withInput()
                    ->withErrors(['video_defeito' => 'Envie um video em MP4, MOV, M4V, WEBM, OGG, AVI ou 3GP.']);
            }
        }

        abort_unless($cliente->veiculos()->whereKey($data['veiculo_id'])->exists(), 403);

        $data['cliente_id'] = $cliente->id;
        $data['numero'] = OrdemServico::gerarNumero();
        $data['status'] = 'aguardando_aceitacao';

        $os = OrdemServico::create($data);

        // Persistir mídias enviadas na abertura da OS
        // - RN004: foto
        if ($request->hasFile('foto_defeito')) {
            $path = $request->file('foto_defeito')->store("os/{$os->id}", 'public');
            $os->fotos()->create([
                'path' => $path,
                'tipo' => 'entrada',
                'lado' => 'outro',
            ]);
        }

        // - RN004: vídeo (salvo também em fotos_os; no show o preview/stream precisa tratar tipo de arquivo)
        if ($request->hasFile('video_defeito')) {
            $path = $request->file('video_defeito')->store("os/{$os->id}", 'public');
            $os->fotos()->create([
                'path' => $path,
                'tipo' => 'entrada',
                'lado' => 'outro',
            ]);
        }

        // Criar notificações para todos os atendentes/gerentes
        $garantiaUsada = $this->acionarGarantiaAtivaParaNovaOs($os);

        $atendentes = User::whereIn('role', ['atendente', 'gerente'])
            ->where('id', '!=', Auth::id())
            ->get();

        foreach ($atendentes as $atendente) {
            Notificacao::create([
                'user_id' => $atendente->id,
                'os_id'   => $os->id,
                'tipo'    => 'solicitacao_os',
                'status'  => 'pendente',
                'mensagem' => $garantiaUsada
                    ? "Nova OS #{$os->numero} de {$os->cliente->nome} - garantia acionada automaticamente"
                    : "Nova OS #{$os->numero} de {$os->cliente->nome}",
            ]);
        }

        if ($garantiaUsada) {
            return redirect()->route('os.show', $os)
                ->with('success', "OS {$os->numero} aberta com sucesso usando a garantia ativa do veiculo.");
        }

        return redirect()->route('os.show', $os)
               ->with('success', "OS {$os->numero} aberta com sucesso! Aguardando aceitação do assistente.");
    }

    private function acionarGarantiaAtivaParaNovaOs(OrdemServico $os): ?Garantia
    {
        $garantia = Garantia::where('status', 'aceita')
            ->where('acionada', false)
            ->whereDate('data_fim', '>=', today())
            ->whereHas('ordemServico', function ($query) use ($os) {
                $query->where('cliente_id', $os->cliente_id)
                    ->where('veiculo_id', $os->veiculo_id)
                    ->where('id', '!=', $os->id);
            })
            ->orderByDesc('data_fim')
            ->first();

        if (!$garantia) {
            return null;
        }

        $observacaoGarantia = trim((string) $garantia->observacao);
        $observacaoOs = trim((string) $os->observacoes);
        $mensagem = "Garantia acionada automaticamente na OS {$os->numero} em " . now()->format('d/m/Y H:i') . ".";

        $garantia->update([
            'acionada' => true,
            'data_acionamento' => now(),
            'observacao' => $observacaoGarantia
                ? $observacaoGarantia . "\n" . $mensagem
                : $mensagem,
        ]);

        $os->update([
            'observacoes' => $observacaoOs
                ? $observacaoOs . "\n" . 'Esta OS foi aberta usando uma garantia ativa do veiculo.'
                : 'Esta OS foi aberta usando uma garantia ativa do veiculo.',
        ]);

        return $garantia;
    }

    // Ver OS completa
    public function show($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        if (Auth::user()->isMecanico() && $ordemServico->mecanico_id !== Auth::user()->mecanico?->id) {
            abort(403);
        }

        if (Auth::user()->isCliente() && $ordemServico->cliente?->user_id !== Auth::id()) {
            abort(403);
        }

        $ordemServico->load([
            'cliente','veiculo','mecanico.user',
            'itens.servico','itens.peca',
            'fotos','garantias','avaliacao',
        ]);

        $servicos  = \App\Models\Servico::where('ativo', true)->orderBy('nome')->get();
        $pecas     = \App\Models\Peca::where('ativo', true)->orderBy('nome')->get();

        return view('ordens-servico.show', compact('ordemServico','servicos','pecas'));
    }

    public function edit($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $mecanicos = Mecanico::where('ativo', true)->orderBy('nome')->get();
        return view('ordens-servico.edit', compact('ordemServico','mecanicos'));
    }

    // UC004/UC005 — Atualizar diagnóstico
    public function update(Request $request, $id)
    {
        $ordemServico = OrdemServico::findOrFail($id);

        if (Auth::user()->isCliente()) {
            if ($ordemServico->cliente?->user_id !== Auth::id()) {
                abort(403);
            }

            $data = $request->validate([
                'sintomas' => 'required|string|max:2000',
            ]);

            $ordemServico->update($data);

            return redirect()->route('os.show', $ordemServico->id)
                   ->with('success', 'Sintomas atualizados!');
        }

        if ($request->has('sintomas')) {
            abort(403);
        }

        if (Auth::user()->isMecanico() && $ordemServico->mecanico_id !== Auth::user()->mecanico?->id) {
            abort(403);
        }

        $data = $request->validate([
            'mecanico_id'   => 'nullable|exists:mecanicos,id',
            'diagnostico'   => 'nullable|string|max:5000',
            'observacoes'   => 'nullable|string|max:2000',
            'data_previsao' => 'nullable|date',
            'valor_desconto'=> 'nullable|numeric|min:0',
        ]);

        $ordemServico->update($data);
        $ordemServico->recalcularTotais();

        return redirect()->route('os.show', $ordemServico->id)
               ->with('success', 'OS atualizada!');
    }

    public function enviarOrcamentoAtendente($id)
    {
        $ordemServico = OrdemServico::with(['cliente', 'mecanico.user'])->findOrFail($id);

        if (!Auth::user()->isMecanico() || $ordemServico->mecanico_id !== Auth::user()->mecanico?->id) {
            abort(403);
        }

        if (!$ordemServico->diagnostico || $ordemServico->itens()->count() === 0) {
            return back()->with('error', 'Informe o diagnostico e adicione pelo menos um item ao orcamento.');
        }

        $ordemServico->update(['status' => 'orcamento_enviado_atendente']);

        Notificacao::where('user_id', Auth::id())
            ->where('os_id', $ordemServico->id)
            ->where('status', 'pendente')
            ->update([
                'status' => 'aceita',
                'lida' => true,
            ]);

        User::whereIn('role', ['atendente', 'gerente'])->get()->each(function (User $user) use ($ordemServico) {
            Notificacao::create([
                'user_id' => $user->id,
                'os_id' => $ordemServico->id,
                'tipo' => 'atualizacao',
                'status' => 'pendente',
                'mensagem' => 'O mecanico ' . $ordemServico->mecanico->nome . ' enviou o orcamento da OS ' . $ordemServico->numero . ' para o cliente ' . $ordemServico->cliente->nome . '.',
            ]);
        });

        return back()->with('success', 'Orcamento enviado para o atendente.');
    }

    public function enviarOrcamentoCliente($id)
    {
        $ordemServico = OrdemServico::with(['cliente.user'])->findOrFail($id);

        if (!Auth::user()->isAtendente() && !Auth::user()->isGerente()) {
            abort(403);
        }

        $ordemServico->update(['status' => 'aguardando_aprovacao']);

        if ($ordemServico->cliente?->user_id) {
            Notificacao::create([
                'user_id' => $ordemServico->cliente->user_id,
                'os_id' => $ordemServico->id,
                'tipo' => 'atualizacao',
                'status' => 'pendente',
                'mensagem' => 'Seu orcamento da OS ' . $ordemServico->numero . ' esta pronto. Aprove para prosseguir ou recuse o servico.',
            ]);
        }

        return back()->with('success', 'Orcamento enviado para o cliente decidir.');
    }

    public function clienteAprovar($id)
    {
        $ordemServico = OrdemServico::with(['cliente', 'mecanico.user'])->findOrFail($id);

        if (!Auth::user()->isCliente() || $ordemServico->cliente?->user_id !== Auth::id()) {
            abort(403);
        }

        $ordemServico->update([
            'aprovado_cliente' => true,
            'data_aprovacao' => now(),
            'status' => 'em_execucao',
        ]);

        Notificacao::where('user_id', Auth::id())
            ->where('os_id', $ordemServico->id)
            ->where('status', 'pendente')
            ->update([
                'status' => 'aceita',
                'lida' => true,
            ]);

        User::whereIn('role', ['atendente', 'gerente'])->get()->each(function (User $user) use ($ordemServico) {
            Notificacao::create([
                'user_id' => $user->id,
                'os_id' => $ordemServico->id,
                'tipo' => 'atualizacao',
                'status' => 'pendente',
                'mensagem' => 'O cliente ' . $ordemServico->cliente->nome . ' aprovou prosseguir com a OS ' . $ordemServico->numero . '.',
            ]);
        });

        if ($ordemServico->mecanico?->user_id) {
            Notificacao::create([
                'user_id' => $ordemServico->mecanico->user_id,
                'os_id' => $ordemServico->id,
                'tipo' => 'atualizacao',
                'status' => 'pendente',
                'mensagem' => 'O cliente aprovou a OS ' . $ordemServico->numero . '. Voce pode prosseguir com o servico.',
            ]);
        }

        return back()->with('success', 'Orcamento aprovado. O mecanico solicitou sua presenca com o veiculo na oficina. Veja a localizacao para combinar a entrega.');
    }

    public function clienteRecusar(Request $request, $id)
    {
        $ordemServico = OrdemServico::with(['cliente', 'mecanico.user'])->findOrFail($id);

        if (!Auth::user()->isCliente() || $ordemServico->cliente?->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'motivo_recusa' => ['nullable', 'string', 'max:120'],
            'detalhes_recusa' => ['nullable', 'string', 'max:2000'],
        ]);

        $ordemServico->update([
            'status' => 'cancelada',
            'motivo_recusa' => $data['motivo_recusa'] ?? 'Cliente recusou o orcamento',
            'detalhes_recusa' => $data['detalhes_recusa'] ?? null,
        ]);

        Notificacao::where('user_id', Auth::id())
            ->where('os_id', $ordemServico->id)
            ->where('status', 'pendente')
            ->update([
                'status' => 'recusada',
                'lida' => true,
            ]);

        User::whereIn('role', ['atendente', 'gerente'])->get()->each(function (User $user) use ($ordemServico) {
            Notificacao::create([
                'user_id' => $user->id,
                'os_id' => $ordemServico->id,
                'tipo' => 'atualizacao',
                'status' => 'pendente',
                'mensagem' => 'O cliente recusou prosseguir com a OS ' . $ordemServico->numero . '.',
            ]);
        });

        if ($ordemServico->mecanico?->user_id) {
            Notificacao::create([
                'user_id' => $ordemServico->mecanico->user_id,
                'os_id' => $ordemServico->id,
                'tipo' => 'atualizacao',
                'status' => 'pendente',
                'mensagem' => 'O cliente recusou a OS ' . $ordemServico->numero . '.',
            ]);
        }

        return back()->with('success', 'Orcamento recusado. A oficina foi avisada.');
    }

    public function destroy($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);

        if (Auth::user()->isCliente()) {
            if ($ordemServico->cliente?->user_id !== Auth::id()) {
                abort(403);
            }

            if ($ordemServico->status !== 'finalizada') {
                return back()->with('error', 'Você só pode apagar OS finalizadas.');
            }

            $ordemServico->delete();
            return redirect()->route('conta.os')->with('success', 'OS finalizada apagada do seu histórico.');
        }

        if ($ordemServico->aprovado_cliente) {
            return back()->with('error', 'Não é possível excluir uma OS já aprovada.');
        }
        $ordemServico->delete();
        return redirect()->route('os.index')->with('success', 'OS removida.');
    }

    // UC007 — Mudar status
    public function atualizarStatus(Request $request, $id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $request->validate([
            'status' => 'required|in:aberta,em_diagnostico,aguardando_aprovacao,aprovada,em_execucao,aguardando_pecas,finalizada,cancelada',
        ]);

        if ($request->status === 'finalizada' && ! $ordemServico->aprovado_cliente) {
            return back()->with('error', 'A OS só pode ser finalizada depois que o cliente aceitar fazer o serviço.');
        }

        $ordemServico->update(['status' => $request->status]);

        // RN001 — ao finalizar, cria garantia de 90 dias automaticamente
        if ($request->status === 'finalizada') {
            $ordemServico->update(['data_conclusao' => now()]);
            if ($ordemServico->mecanico?->user_id) {
                Notificacao::where('user_id', $ordemServico->mecanico->user_id)
                    ->where('os_id', $ordemServico->id)
                    ->where('status', 'pendente')
                    ->update([
                        'status' => 'aceita',
                        'lida' => true,
                    ]);
            }
            $this->criarOfertaGarantia($ordemServico);
        }

        return back()->with('success', 'Status atualizado para: ' . $ordemServico->statusLabel());
    }

    // UC004 — Gerente aprova orçamento
    public function aprovar($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $ordemServico->update([
            'aprovado_cliente' => true,
            'data_aprovacao'   => now(),
            'status'           => 'aprovada',
        ]);

        return back()->with('success', 'Orçamento aprovado! OS liberada para execução.');
    }

    // UC009 — Fechar OS
    public function fechar($id)
    {
        abort_if(auth()->user()->isCliente(), 403);

        $ordemServico = OrdemServico::findOrFail($id);

        if (! $ordemServico->aprovado_cliente) {
            return back()->with('error', 'A OS só pode ser finalizada depois que o cliente aceitar fazer o serviço.');
        }

        $ordemServico->update([
            'status'         => 'finalizada',
            'data_conclusao' => now(),
        ]);

        if ($ordemServico->mecanico?->user_id) {
            Notificacao::where('user_id', $ordemServico->mecanico->user_id)
                ->where('os_id', $ordemServico->id)
                ->where('status', 'pendente')
                ->update([
                    'status' => 'aceita',
                    'lida' => true,
                ]);
        }

        $this->criarOfertaGarantia($ordemServico);

        return back()->with('success', 'Ordem de serviço finalizada com sucesso!');
    }

    private function criarOfertaGarantia(OrdemServico $ordemServico): void
    {
        $ordemServico->loadMissing(['cliente.user', 'itens', 'garantias']);

        if ($ordemServico->garantias()
            ->whereIn('status', ['pendente', 'aceita'])
            ->exists()) {
            return;
        }

        $base = (float) $ordemServico->valor_total;
        if ($base <= 0) {
            $base = (float) $ordemServico->itens->sum('valor_total');
        }

        $valorGarantia = round(min(450, max(60, $base * 0.12)), 2);

        $garantia = $ordemServico->garantias()->create([
            'descricao' => 'Oferta de garantia adicional de 60 dias para serviços e peças desta OS.',
            'valor' => $valorGarantia,
            'status' => 'pendente',
            'data_inicio' => today(),
            'data_fim' => today()->addDays(60),
            'observacao' => 'Aguardando decisão do cliente.',
        ]);

        if ($ordemServico->cliente?->user_id) {
            Notificacao::create([
                'user_id' => $ordemServico->cliente->user_id,
                'os_id' => $ordemServico->id,
                'tipo' => 'atualizacao',
                'status' => 'pendente',
                'mensagem' => 'Sua OS ' . $ordemServico->numero . ' foi finalizada. Voce pode adicionar 60 dias de garantia por R$ ' . number_format($garantia->valor, 2, ',', '.') . ' ou recusar sem custo.',
            ]);
        }
    }

    // Autorização do cliente via link/token (RF004)
    public function showAutorizacao(string $token)
    {
        $os = OrdemServico::where('numero', $token)
            ->with(['cliente','veiculo','itens.servico','itens.peca'])
            ->firstOrFail();
        return view('ordens-servico.autorizar', compact('os'));
    }

    public function autorizar(Request $request, string $token)
    {
        $os = OrdemServico::where('numero', $token)->firstOrFail();
        $os->update([
            'aprovado_cliente' => true,
            'data_aprovacao'   => now(),
            'status'           => 'aprovada',
        ]);
        return view('ordens-servico.autorizado', compact('os'));
    }

    // RN004 — Upload de fotos
    public function uploadFotos(Request $request, $id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $request->validate([
            'fotos'   => 'required|array|max:10',
            'fotos.*' => 'image|mimes:jpeg,png,webp|max:5120',
            'tipo'    => 'required|in:entrada,saida,processo',
            'lado'    => 'nullable|in:frontal,traseira,lateral_dir,lateral_esq,interior,outro',
        ]);

        foreach ($request->file('fotos') as $foto) {
            $path = $foto->store("os/{$ordemServico->id}", 'public');
            $ordemServico->fotos()->create([
                'path' => $path,
                'tipo' => $request->tipo,
                'lado' => $request->lado,
            ]);
        }

        return back()->with('success', count($request->file('fotos')) . ' foto(s) salva(s).');
    }

    public function deletarFoto($id, int $foto)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $fotoModel = $ordemServico->fotos()->findOrFail($foto);
        Storage::disk('public')->delete($fotoModel->path);
        $fotoModel->delete();
        return back()->with('success', 'Foto removida.');
    }

    // Imprimir OS (para PDF)
    public function imprimir($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $ordemServico->load(['cliente','veiculo','mecanico','itens.servico','itens.peca','garantias']);
        return view('ordens-servico.print', compact('ordemServico'));
    }
}
