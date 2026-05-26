<?php

namespace App\Http\Controllers;

use App\Models\ContaAcessoSolicitacao;
use App\Models\User;
use Illuminate\Http\Request;

class RoleAccountController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $solicitacoesPendentes = ContaAcessoSolicitacao::with('solicitante')
            ->where('status', 'pendente')
            ->latest()
            ->get();

        if ($user->isGerente()) {
            $accessGranted = $request->session()->get('conta_usuarios_acesso', false);

            if (! $accessGranted) {
                return view('conta.usuarios', [
                    'gerente' => true,
                    'accessGranted' => false,
                    'accessError' => session('access_error'),
                    'solicitacoesPendentes' => $solicitacoesPendentes,
                ]);
            }

            return view('conta.usuarios', [
                'gerente' => true,
                'accessGranted' => true,
                'users' => $this->buscarUsuarios($request),
                'filterRole' => $request->role,
                'filterSearch' => $request->search,
                'accessError' => session('access_error'),
                'solicitacoesPendentes' => $solicitacoesPendentes,
            ]);
        }

        $solicitacao = ContaAcessoSolicitacao::where('solicitante_id', $user->id)
            ->latest()
            ->first();

        if ($solicitacao?->status === 'aprovada') {
            return view('conta.usuarios', [
                'gerente' => false,
                'accessGranted' => true,
                'users' => $this->buscarUsuarios($request),
                'filterRole' => $request->role,
                'filterSearch' => $request->search,
                'solicitacao' => $solicitacao,
            ]);
        }

        return view('conta.usuarios', [
            'gerente' => false,
            'accessGranted' => false,
            'solicitado' => $solicitacao?->status === 'pendente',
            'solicitacao' => $solicitacao,
        ]);
    }

    public function solicitar()
    {
        if (! auth()->user()->isAtendente()) {
            abort(403);
        }

        $solicitacaoAberta = ContaAcessoSolicitacao::where('solicitante_id', auth()->id())
            ->whereIn('status', ['pendente', 'aprovada'])
            ->latest()
            ->first();

        if ($solicitacaoAberta?->status === 'pendente') {
            return redirect()->route('conta.usuarios')
                ->with('error', 'A autorizacao ja foi solicitada. Aguarde o gerente liberar o acesso.');
        }

        if ($solicitacaoAberta?->status === 'aprovada') {
            return redirect()->route('conta.usuarios')
                ->with('success', 'Seu acesso ja foi autorizado pelo gerente.');
        }

        ContaAcessoSolicitacao::create([
            'solicitante_id' => auth()->id(),
            'status' => 'pendente',
        ]);

        return redirect()->route('conta.usuarios')
            ->with('success', 'Solicitacao de autorizacao enviada ao gerente.');
    }

    public function aprovarSolicitacao(ContaAcessoSolicitacao $solicitacao)
    {
        if (! auth()->user()->isGerente()) {
            abort(403);
        }

        $solicitacao->update([
            'status' => 'aprovada',
            'gerente_id' => auth()->id(),
            'respondido_em' => now(),
        ]);

        return redirect()->route('conta.usuarios')
            ->with('success', 'Acesso autorizado para ' . $solicitacao->solicitante->name . '.');
    }

    public function recusarSolicitacao(ContaAcessoSolicitacao $solicitacao)
    {
        if (! auth()->user()->isGerente()) {
            abort(403);
        }

        $solicitacao->update([
            'status' => 'recusada',
            'gerente_id' => auth()->id(),
            'respondido_em' => now(),
        ]);

        return redirect()->route('conta.usuarios')
            ->with('success', 'Solicitacao recusada.');
    }

    public function solicitarTrocaSenha(Request $request)
    {
        $user = auth()->user();

        if (! $user->isCliente()) {
            abort(403);
        }

        if ($user->password_change_requested_at) {
            return back()->with('error', 'Voce ja solicitou a troca de senha. Aguarde o gerente alterar.');
        }

        $user->update([
            'password_change_requested_at' => now(),
        ]);

        return back()->with('success', 'Solicitacao de troca de senha enviada ao gerente.');
    }

    public function cancelarTrocaSenha(Request $request)
    {
        $user = auth()->user();

        if (! $user->isCliente()) {
            abort(403);
        }

        if (! $user->password_change_requested_at) {
            return back()->with('error', 'Nao existe solicitacao de troca de senha para cancelar.');
        }

        $user->update([
            'password_change_requested_at' => null,
        ]);

        return back()->with('success', 'Solicitacao de troca de senha cancelada.');
    }

    public function autorizar(Request $request)
    {
        if (! auth()->user()->isGerente()) {
            abort(403);
        }

        $validated = $request->validate([
            'senha' => 'required|string',
        ]);

        if ($validated['senha'] !== '12345678') {
            return redirect()->route('conta.usuarios')
                ->with('access_error', 'Senha incorreta. Tente novamente.');
        }

        $request->session()->put('conta_usuarios_acesso', true);

        return redirect()->route('conta.usuarios');
    }

    public function fechar(Request $request)
    {
        if (! auth()->user()->isGerente()) {
            abort(403);
        }

        $request->session()->forget('conta_usuarios_acesso');

        return redirect()->route('conta.usuarios')
            ->with('success', 'Acesso encerrado. Informe a senha novamente para reabrir.');
    }

    public function detalhes(Request $request, User $user)
    {
        if (! $this->podeAcessarContas($request)) {
            abort(403);
        }

        $user->load([
            'cliente.veiculos.ordens.mecanico',
            'cliente.ordens.veiculo',
            'cliente.ordens.itens',
            'cliente.ordens.garantias',
            'mecanico.ordens.cliente',
            'mecanico.ordens.veiculo',
            'mecanico.ordens.itens',
        ]);

        return view('conta.usuario-detalhes', compact('user'));
    }

    public function atualizarSenha(Request $request, User $user)
    {
        if (! auth()->user()->isGerente() || ! $request->session()->get('conta_usuarios_acesso', false)) {
            abort(403);
        }

        if ($user->isCliente() && ! $user->password_change_requested_at) {
            return redirect()
                ->route('conta.usuarios.detalhes', $user)
                ->with('error', 'Este cliente ainda nao solicitou troca de senha.');
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'A confirmacao da senha nao confere.',
            'password.min' => 'A senha precisa ter pelo menos 8 caracteres.',
        ]);

        $user->update([
            'password' => $validated['password'],
            'password_change_requested_at' => null,
        ]);

        return redirect()
            ->route('conta.usuarios.detalhes', $user)
            ->with('success', 'Senha atualizada com sucesso.');
    }

    private function buscarUsuarios(Request $request)
    {
        $query = User::with(['cliente', 'mecanico'])
            ->whereIn('role', ['gerente', 'atendente', 'mecanico', 'cliente']);

        if ($request->filled('role') && in_array($request->role, ['gerente', 'atendente', 'mecanico', 'cliente'])) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query
            ->orderByRaw("FIELD(role, 'gerente', 'atendente', 'mecanico', 'cliente')")
            ->orderBy('name')
            ->get();
    }

    private function podeAcessarContas(Request $request): bool
    {
        $user = auth()->user();

        if ($user->isGerente()) {
            return $request->session()->get('conta_usuarios_acesso', false);
        }

        if ($user->isAtendente()) {
            return ContaAcessoSolicitacao::where('solicitante_id', $user->id)
                ->where('status', 'aprovada')
                ->exists();
        }

        return false;
    }
}
