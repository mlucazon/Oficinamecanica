<?php

namespace App\Http\Controllers;

use App\Models\MarcasVeiculos;
use App\Models\ModelosVeiculos;
use App\Models\Peca;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PecaController extends Controller
{
    public function index(Request $request)
    {
        $regioesPecas = $this->regioesPecas();
        $regiaoAtiva = $request->string('regiao')->toString();
        $regiaoInferida = null;
        $marcaAtiva = $request->integer('marca_id') ?: null;
        $modeloAtivo = $request->integer('modelo_id') ?: null;

        if (! array_key_exists($regiaoAtiva, $regioesPecas)) {
            $regiaoAtiva = null;
        }

        if (! $regiaoAtiva && $request->filled('busca')) {
            $regiaoInferida = $this->inferirRegiaoPelaBusca($request->string('busca')->toString(), $regioesPecas);
            $regiaoAtiva = $regiaoInferida;
        }

        $marcas = MarcasVeiculos::with(['modelos' => fn ($query) => $query->orderBy('nome')])
            ->orderBy('nome')
            ->get();

        $modelosDisponiveis = $marcaAtiva
            ? ModelosVeiculos::where('marca_id', $marcaAtiva)->orderBy('nome')->get()
            : collect();

        if ($modeloAtivo && ! $modelosDisponiveis->contains('id', $modeloAtivo)) {
            $modeloAtivo = null;
        }

        $pecas = Peca::with(['marcaVeiculo', 'modeloVeiculo'])
            ->when($request->busca, function ($q, $busca) {
                $q->where(function ($query) use ($busca) {
                    $query->where('nome', 'like', "%{$busca}%")
                        ->orWhere('codigo', 'like', "%{$busca}%")
                        ->orWhere('fabricante', 'like', "%{$busca}%");
                });
            })
            ->when($regiaoAtiva, function ($q) use ($regioesPecas, $regiaoAtiva) {
                $termos = $regioesPecas[$regiaoAtiva]['termos'];

                $q->where(function ($query) use ($termos) {
                    foreach ($termos as $termo) {
                        $query->orWhere('nome', 'like', "%{$termo}%")
                            ->orWhere('codigo', 'like', "%{$termo}%")
                            ->orWhere('fabricante', 'like', "%{$termo}%");
                    }
                });
            })
            ->when($marcaAtiva, fn ($q) => $q->where(fn ($query) => $query->whereNull('marca_veiculo_id')->orWhere('marca_veiculo_id', $marcaAtiva)))
            ->when($modeloAtivo, fn ($q) => $q->where(fn ($query) => $query->whereNull('modelo_veiculo_id')->orWhere('modelo_veiculo_id', $modeloAtivo)))
            ->when($request->estoque_baixo, fn($q) => $q->whereColumn('estoque', '<=', 'estoque_minimo'))
            ->orderBy('nome')->paginate(20)->withQueryString();
        $criticas = Peca::whereColumn('estoque', '<=', 'estoque_minimo')->count();

        $contagemRegioes = collect($regioesPecas)->mapWithKeys(function ($regiao, $key) use ($marcaAtiva, $modeloAtivo) {
            $count = Peca::query()
                ->when($marcaAtiva, fn ($q) => $q->where(fn ($query) => $query->whereNull('marca_veiculo_id')->orWhere('marca_veiculo_id', $marcaAtiva)))
                ->when($modeloAtivo, fn ($q) => $q->where(fn ($query) => $query->whereNull('modelo_veiculo_id')->orWhere('modelo_veiculo_id', $modeloAtivo)))
                ->where(function ($query) use ($regiao) {
                    foreach ($regiao['termos'] as $termo) {
                        $query->orWhere('nome', 'like', "%{$termo}%")
                            ->orWhere('codigo', 'like', "%{$termo}%")
                            ->orWhere('fabricante', 'like', "%{$termo}%");
                    }
                })
                ->count();

            return [$key => $count];
        });

        return view('pecas.index', compact(
            'pecas',
            'criticas',
            'regioesPecas',
            'regiaoAtiva',
            'contagemRegioes',
            'marcas',
            'modelosDisponiveis',
            'marcaAtiva',
            'modeloAtivo',
            'regiaoInferida'
        ));
    }

    public function create()
    {
        return view('pecas.create', [
            'marcas' => MarcasVeiculos::with(['modelos' => fn ($query) => $query->orderBy('nome')])->orderBy('nome')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:150',
            'codigo'         => 'nullable|string|max:80|unique:pecas,codigo',
            'fabricante'     => 'nullable|string|max:100',
            'marca_veiculo_id' => 'nullable|exists:marcas_veiculos,id',
            'modelo_veiculo_id' => [
                'nullable',
                Rule::exists('modelos_veiculos', 'id')->where(fn ($query) => $query->where('marca_id', $request->input('marca_veiculo_id'))),
            ],
            'preco_custo'    => 'required|numeric|min:0',
            'preco_venda'    => 'required|numeric|min:0',
            'estoque'        => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'unidade'        => 'required|string|max:20',
        ]);
        Peca::create($data);
        return redirect()->route('pecas.index')->with('success', 'Peça cadastrada!');
    }

    public function show(Peca $peca) { return view('pecas.show', compact('peca')); }
    public function edit(Peca $peca)
    {
        return view('pecas.create', [
            'peca' => $peca,
            'marcas' => MarcasVeiculos::with(['modelos' => fn ($query) => $query->orderBy('nome')])->orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, Peca $peca)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:150',
            'codigo'         => 'nullable|string|max:80|unique:pecas,codigo,' . $peca->id,
            'fabricante'     => 'nullable|string|max:100',
            'marca_veiculo_id' => 'nullable|exists:marcas_veiculos,id',
            'modelo_veiculo_id' => [
                'nullable',
                Rule::exists('modelos_veiculos', 'id')->where(fn ($query) => $query->where('marca_id', $request->input('marca_veiculo_id'))),
            ],
            'preco_custo'    => 'required|numeric|min:0',
            'preco_venda'    => 'required|numeric|min:0',
            'estoque'        => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'unidade'        => 'required|string|max:20',
        ]);
        $peca->update($data);
        return redirect()->route('pecas.index')->with('success', 'Peça atualizada!');
    }

    public function destroy(Peca $peca)
    {
        $peca->delete();
        return redirect()->route('pecas.index')->with('success', 'Peça removida.');
    }

    public function ajustarEstoque(Request $request, Peca $peca)
    {
        $request->validate(['quantidade' => 'required|integer', 'motivo' => 'required|string|max:255']);
        $peca->increment('estoque', $request->quantidade);
        return back()->with('success', 'Estoque ajustado!');
    }

    private function regioesPecas(): array
    {
        return [
            'motor' => [
                'label' => 'Motor',
                'descricao' => 'Filtros, oleo, correias, velas, bombas e arrefecimento.',
                'icone' => 'bi-cpu',
                'termos' => ['motor', 'filtro', 'oleo', 'correia', 'vela', 'bomba', 'radiador', 'arrefecimento', 'injecao'],
            ],
            'freios' => [
                'label' => 'Freios',
                'descricao' => 'Pastilhas, discos, fluido, cilindros e sensores de freio.',
                'icone' => 'bi-record-circle',
                'termos' => ['freio', 'pastilha', 'disco', 'tambor', 'fluido', 'cilindro', 'abs'],
            ],
            'suspensao' => [
                'label' => 'Suspensao',
                'descricao' => 'Amortecedores, molas, bandejas, buchas e terminais.',
                'icone' => 'bi-bezier2',
                'termos' => ['suspensao', 'amortecedor', 'mola', 'bandeja', 'bucha', 'pivo', 'terminal', 'bieleta'],
            ],
            'rodas' => [
                'label' => 'Rodas',
                'descricao' => 'Pneus, rolamentos, cubos, parafusos e componentes das rodas.',
                'icone' => 'bi-circle',
                'termos' => ['pneu', 'roda', 'rolamento', 'cubo', 'parafuso', 'calota'],
            ],
            'eletrica' => [
                'label' => 'Eletrica',
                'descricao' => 'Bateria, lampadas, fusiveis, sensores e alternador.',
                'icone' => 'bi-lightning-charge',
                'termos' => ['bateria', 'lampada', 'fusivel', 'sensor', 'alternador', 'chicote', 'eletrico', 'eletrica'],
            ],
            'transmissao' => [
                'label' => 'Transmissao',
                'descricao' => 'Embreagem, cambio, homocinetica, trizeta e semi-eixo.',
                'icone' => 'bi-diagram-3',
                'termos' => ['cambio', 'embreagem', 'homocinetica', 'trizeta', 'semi eixo', 'semieixo', 'transmissao'],
            ],
            'carroceria' => [
                'label' => 'Carroceria',
                'descricao' => 'Parachoque, farol, retrovisor, macanetas e acabamento externo.',
                'icone' => 'bi-car-front',
                'termos' => ['parachoque', 'farol', 'lanterna', 'retrovisor', 'porta', 'capo', 'grade', 'carroceria'],
            ],
            'interior' => [
                'label' => 'Interior',
                'descricao' => 'Painel, comandos, filtros de cabine, bancos e acabamento interno.',
                'icone' => 'bi-speedometer2',
                'termos' => ['painel', 'banco', 'cabine', 'interno', 'tapete', 'comando', 'ar condicionado', 'filtro cabine'],
            ],
        ];
    }

    private function inferirRegiaoPelaBusca(string $busca, array $regioesPecas): ?string
    {
        $buscaNormalizada = $this->normalizarTexto($busca);

        if ($buscaNormalizada === '') {
            return null;
        }

        foreach ($regioesPecas as $key => $regiao) {
            foreach ($regiao['termos'] as $termo) {
                $termoNormalizado = $this->normalizarTexto($termo);

                if (str_contains($buscaNormalizada, $termoNormalizado) || (strlen($buscaNormalizada) >= 4 && str_contains($termoNormalizado, $buscaNormalizada))) {
                    return $key;
                }
            }
        }

        $peca = Peca::query()
            ->where('nome', 'like', "%{$busca}%")
            ->orWhere('codigo', 'like', "%{$busca}%")
            ->orWhere('fabricante', 'like', "%{$busca}%")
            ->orderBy('nome')
            ->first(['nome', 'codigo', 'fabricante']);

        if (! $peca) {
            return null;
        }

        $textoPeca = $this->normalizarTexto(implode(' ', array_filter([
            $peca->nome,
            $peca->codigo,
            $peca->fabricante,
        ])));

        foreach ($regioesPecas as $key => $regiao) {
            foreach ($regiao['termos'] as $termo) {
                if (str_contains($textoPeca, $this->normalizarTexto($termo))) {
                    return $key;
                }
            }
        }

        return null;
    }

    private function normalizarTexto(string $texto): string
    {
        $texto = mb_strtolower(trim($texto));
        $semAcento = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);

        return trim(preg_replace('/[^a-z0-9 ]+/', ' ', $semAcento ?: $texto));
    }
}
