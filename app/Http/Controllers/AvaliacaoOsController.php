<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoOs;
use App\Models\OrdemServico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoOsController extends Controller
{
    public function index()
    {
        $avaliacoes = AvaliacaoOs::with(['cliente.user', 'ordemServico.veiculo', 'respondente'])
            ->latest()
            ->paginate(10);

        $ordensPendentes = collect();
        if (Auth::user()->isCliente() && Auth::user()->cliente) {
            $ordensPendentes = Auth::user()->cliente->ordens()
                ->with(['veiculo', 'avaliacao'])
                ->where('status', 'finalizada')
                ->whereDoesntHave('avaliacao')
                ->latest('data_conclusao')
                ->get();
        }

        return view('avaliacoes.index', compact('avaliacoes', 'ordensPendentes'));
    }

    public function create(OrdemServico $ordemServico)
    {
        $this->authorizeClienteDaOs($ordemServico);

        if ($ordemServico->status !== 'finalizada') {
            return redirect()->route('avaliacoes.index')
                ->with('error', 'Apenas OS finalizadas podem ser avaliadas.');
        }

        if ($ordemServico->avaliacao) {
            return redirect()->route('avaliacoes.index')
                ->with('error', 'Esta OS ja possui uma avaliacao.');
        }

        $ordemServico->load(['veiculo', 'cliente']);

        return view('avaliacoes.create', compact('ordemServico'));
    }

    public function store(Request $request, OrdemServico $ordemServico)
    {
        $this->authorizeClienteDaOs($ordemServico);

        if ($ordemServico->status !== 'finalizada') {
            return back()->with('error', 'Apenas OS finalizadas podem ser avaliadas.');
        }

        if ($ordemServico->avaliacao) {
            return redirect()->route('avaliacoes.index')
                ->with('error', 'Esta OS ja possui uma avaliacao.');
        }

        $data = $request->validate([
            'nota' => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['required', 'string', 'max:3000'],
            'foto_antes' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'foto_depois' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ]);

        $avaliacaoData = [
            'os_id' => $ordemServico->id,
            'cliente_id' => Auth::user()->cliente->id,
            'nota' => $data['nota'],
            'comentario' => $data['comentario'],
        ];

        if ($request->hasFile('foto_antes')) {
            $avaliacaoData['foto_antes_path'] = $request->file('foto_antes')
                ->store("avaliacoes/{$ordemServico->id}", 'public');
        }

        if ($request->hasFile('foto_depois')) {
            $avaliacaoData['foto_depois_path'] = $request->file('foto_depois')
                ->store("avaliacoes/{$ordemServico->id}", 'public');
        }

        AvaliacaoOs::create($avaliacaoData);

        return redirect()->route('avaliacoes.index')
            ->with('success', 'Avaliacao enviada. Obrigado pelo feedback!');
    }

    public function responder(Request $request, AvaliacaoOs $avaliacao)
    {
        if (!Auth::user()->isAtendente() && !Auth::user()->isGerente()) {
            abort(403);
        }

        $data = $request->validate([
            'resposta' => ['required', 'string', 'max:3000'],
        ]);

        $avaliacao->update([
            'resposta' => $data['resposta'],
            'respondido_por' => Auth::id(),
            'respondido_em' => now(),
        ]);

        return back()->with('success', 'Resposta publicada na avaliacao.');
    }

    private function authorizeClienteDaOs(OrdemServico $ordemServico): void
    {
        if (!Auth::user()->isCliente() || $ordemServico->cliente?->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
