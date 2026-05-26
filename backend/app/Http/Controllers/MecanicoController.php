<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use Illuminate\Http\Request;

class MecanicoController extends Controller
{
    public function index(Request $request)
    {
        $this->bloquearCliente();

        $mecanicos = Mecanico::with('user')
            ->when($request->busca, fn($q, $b) =>
                $q->where('nome', 'like', "%{$b}%")
            )
            ->orderBy('nome')
            ->paginate(20)
            ->withQueryString();

        return view('mecanicos.index', compact('mecanicos'));
    }

    public function create()
    {
        $this->bloquearCliente();

        return view('mecanicos.create');
    }

    public function store(Request $request)
    {
        $this->bloquearCliente();

        $data = $request->validate([
            'nome'          => 'required|string|max:150',
            'cpf'           => 'nullable|string|max:14|unique:mecanicos,cpf',
            'telefone'      => 'nullable|string|max:20',
            'especialidade' => 'nullable|string|max:100',
        ]);

        Mecanico::create($data);
        return redirect()->route('mecanicos.index')->with('success', 'Mecânico cadastrado!');
    }

    public function show(Mecanico $mecanico)
    {
        $this->bloquearCliente();

        $mecanico->load(['ordens' => fn($q) => $q->latest()->limit(10)]);
        return view('mecanicos.show', compact('mecanico'));
    }

    public function edit(Mecanico $mecanico)
    {
        $this->bloquearCliente();

        return view('mecanicos.edit', compact('mecanico'));
    }

    public function update(Request $request, Mecanico $mecanico)
    {
        $this->bloquearCliente();

        $data = $request->validate([
            'nome'          => 'required|string|max:150',
            'cpf'           => 'nullable|string|max:14|unique:mecanicos,cpf,' . $mecanico->id,
            'telefone'      => 'nullable|string|max:20',
            'especialidade' => 'nullable|string|max:100',
        ]);

        $mecanico->update($data);
        return redirect()->route('mecanicos.index')->with('success', 'Mecânico atualizado!');
    }

    public function destroy(Mecanico $mecanico)
    {
        $this->bloquearCliente();

        $mecanico->delete();
        return redirect()->route('mecanicos.index')->with('success', 'Mecânico removido.');
    }

    public function toggle(Mecanico $mecanico)
    {
        $this->bloquearCliente();

        $mecanico->update(['ativo' => !$mecanico->ativo]);
        $msg = $mecanico->ativo ? 'Mecânico ativado.' : 'Mecânico desativado.';
        return back()->with('success', $msg);
    }

    private function bloquearCliente(): void
    {
        abort_if(auth()->user()->isCliente(), 403);
    }
}
