<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\MarcasVeiculos;
use App\Models\ModelosVeiculos;
use App\Models\Veiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    public function index(Request $request)
    {
        // Cliente so enxerga seus proprios veiculos.
        // Gerentes/atendentes podem enxergar tudo.
        $user = auth()->user();
        $isClient = $user && $user->isCliente();

        $query = Veiculo::query()->with(['cliente', 'ordens' => fn($q) => $q->latest()]);

        if ($isClient) {
            $cliente = auth()->user()->cliente;
            $query->where('cliente_id', $cliente?->id);
        }

        $query->when($request->busca, fn($q, $b) =>
            $q->where(function ($inner) use ($b) {
                $inner->where('placa', 'like', "%{$b}%")
                    ->orWhere('modelo', 'like', "%{$b}%")
                    ->orWhereHas('cliente', fn($c) => $c->where('nome', 'like', "%{$b}%"));
            })
        );

        $veiculos = $query->latest()->paginate(20)->withQueryString();

        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        $marcas = MarcasVeiculos::orderBy('nome')->get(['id', 'nome']);
        return view('veiculos.create', compact('marcas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'placa' => 'required|string|max:10|unique:veiculos,placa',
            'marca' => 'required|string|max:80',
            'modelo' => 'required|string|max:80',
            'ano' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cor' => 'nullable|string|max:50',
            'km_atual' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $cliente = auth()->user()->cliente;

        if (!$cliente) {
            return back()->withErrors(['cliente' => 'Usuario nao tem perfil de cliente.']);
        }

        // Upload da foto do carro (opcional). A coluna no banco ainda nao persiste a foto.
        if ($request->hasFile('foto')) {
            $request->file('foto')->store('veiculos/' . $cliente->id, 'public');
        }

        $data['cliente_id'] = $cliente->id;
        $veiculo = Veiculo::create($data);

        return redirect()->route('veiculos.show', $veiculo->id)
            ->with('success', 'Veiculo cadastrado!');
    }

    public function show($id)
    {
        $veiculo = Veiculo::with('cliente')->findOrFail($id);

        if (auth()->check() && auth()->user()->isCliente() && $veiculo->cliente_id !== auth()->user()->cliente?->id) {
            abort(403, 'Acesso nao autorizado.');
        }

        $veiculo->load(['ordens' => fn($q) => $q->latest()->limit(10)]);
        $ordens = $veiculo->ordens;

        return view('veiculos.show', compact('veiculo', 'ordens'));
    }

    public function edit($id)
    {
        $veiculo = Veiculo::findOrFail($id);

        if (auth()->check() && auth()->user()->isCliente() && $veiculo->cliente_id !== auth()->user()->cliente?->id) {
            abort(403, 'Acesso nao autorizado.');
        }

        $marcas = MarcasVeiculos::orderBy('nome')->get(['id', 'nome']);

        // Como o schema atual usa strings em veiculos.marca/modelo, resolvemos a marca selecionada pelo nome.
        $marcaSelecionadaId = $marcas->firstWhere('nome', $veiculo->marca)?->id;

        $modelos = $marcaSelecionadaId
            ? ModelosVeiculos::where('marca_id', $marcaSelecionadaId)->orderBy('nome')->get(['id', 'nome'])
            : collect();

        return view('veiculos.edit', compact('veiculo', 'marcas', 'modelos', 'marcaSelecionadaId'));
    }

    public function update(Request $request, $id)
    {
        $veiculo = Veiculo::findOrFail($id);

        if (auth()->check() && auth()->user()->isCliente() && $veiculo->cliente_id !== auth()->user()->cliente?->id) {
            abort(403, 'Acesso nao autorizado.');
        }

        $data = $request->validate([
            'placa' => 'required|string|max:10|unique:veiculos,placa,' . $veiculo->id,
            'marca' => 'required|string|max:80',
            'modelo' => 'required|string|max:80',
            'ano' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cor' => 'nullable|string|max:50',
            'km_atual' => 'nullable|integer|min:0',
        ]);

        $veiculo->update($data);

        return redirect()->route('veiculos.show', $veiculo->id)->with('success', 'Veiculo atualizado!');
    }

    public function destroy($id)
    {
        $veiculo = Veiculo::findOrFail($id);

        if (auth()->check() && auth()->user()->isCliente() && $veiculo->cliente_id !== auth()->user()->cliente?->id) {
            abort(403, 'Acesso nao autorizado.');
        }

        $clienteId = $veiculo->cliente_id;
        $redirectClienteId = request('redirect_cliente_id');
        $redirectContaVeiculos = request()->boolean('redirect_conta_veiculos');

        if ($veiculo->ordens()->exists()) {
            $redirect = $redirectContaVeiculos
                ? redirect()->route('conta.veiculos')
                : redirect()->back();

            return $redirect->with('error', 'Nao e possivel remover este veiculo porque ele possui OS vinculada.');
        }

        $veiculo->delete();

        if ($redirectContaVeiculos) {
            return redirect()->route('conta.veiculos')->with('success', 'Veiculo removido.');
        }

        if ((string) $redirectClienteId === (string) $clienteId) {
            return redirect()->route('clientes.show', $clienteId)->with('success', 'Veiculo removido.');
        }

        return redirect()->route('veiculos.index')->with('success', 'Veiculo removido.');
    }

    public function modelosPorMarca(int $marcaId)
    {
        $modelos = ModelosVeiculos::where('marca_id', $marcaId)
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return response()->json($modelos);
    }

    public function porCliente(Cliente $cliente)
    {
        return response()->json(
            $cliente->veiculos()
                ->select('id', 'placa', 'marca', 'modelo', 'ano', 'km_atual')
                ->get()
        );
    }
}
