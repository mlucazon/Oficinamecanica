@extends('layouts.app')
@section('title', 'Pecas')
@section('breadcrumb', 'Pecas / Estoque')

@push('styles')
<style>
    .parts-xray {
        display: grid;
        grid-template-columns: minmax(280px, 1.1fr) minmax(260px, .9fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .parts-xray-card {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 12px;
        background:
            radial-gradient(circle at 82% 20%, rgba(196,0,0,.18), transparent 30%),
            linear-gradient(145deg, rgba(255,255,255,.055), rgba(255,255,255,.018));
        box-shadow: 0 24px 70px rgba(0,0,0,.20);
    }

    .parts-xray-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1rem .25rem;
    }

    .parts-xray-kicker {
        display: block;
        margin-bottom: .25rem;
        color: var(--red-h);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .parts-xray-title {
        margin: 0;
        color: var(--text);
        font-family: 'Syne', sans-serif;
        font-size: clamp(1.25rem, 2.3vw, 2rem);
        font-weight: 900;
        line-height: 1;
    }

    .parts-xray-sub {
        margin: .45rem 0 0;
        color: var(--text2);
        font-size: .93rem;
        line-height: 1.5;
    }

    .parts-car-wrap {
        position: relative;
        min-height: 330px;
        padding: 1rem;
    }

    .parts-car-stage {
        position: relative;
        height: 300px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 10px;
        background:
            linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px),
            linear-gradient(0deg, rgba(255,255,255,.03) 1px, transparent 1px),
            radial-gradient(circle at 50% 50%, rgba(255,255,255,.08), transparent 48%);
        background-size: 34px 34px, 34px 34px, auto;
    }

    .parts-car-svg {
        position: absolute;
        inset: 8% 4% 8% 4%;
        width: 92%;
        height: 84%;
        opacity: .9;
        filter: drop-shadow(0 20px 38px rgba(0,0,0,.42));
    }

    .parts-car-svg .shell {
        fill: rgba(210, 238, 255, .13);
        stroke: rgba(215, 242, 255, .82);
        stroke-width: 2.5;
    }

    .parts-car-svg .glass,
    .parts-car-svg .inner {
        fill: rgba(120, 190, 230, .12);
        stroke: rgba(195, 230, 255, .42);
        stroke-width: 1.4;
    }

    .parts-car-svg .wheel {
        fill: rgba(10, 15, 18, .64);
        stroke: rgba(225, 245, 255, .72);
        stroke-width: 2;
    }

    .parts-car-svg .line {
        fill: none;
        stroke: rgba(220, 245, 255, .30);
        stroke-width: 1.4;
    }

    .part-hotspot {
        position: absolute;
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        border: 1px solid rgba(255,255,255,.24);
        border-radius: 14px;
        color: #fff;
        background: rgba(196,0,0,.72);
        box-shadow: 0 14px 34px rgba(196,0,0,.28);
        text-decoration: none;
    }

    .part-hotspot:hover,
    .part-hotspot.active {
        color: #fff;
        transform: translateY(-3px) scale(1.04);
        border-color: rgba(255,255,255,.46);
        background: linear-gradient(135deg, #ff2525, #b00000);
        box-shadow: 0 18px 46px rgba(196,0,0,.42);
    }

    .part-hotspot::after {
        content: attr(data-label);
        position: absolute;
        left: 50%;
        bottom: calc(100% + 7px);
        transform: translateX(-50%);
        white-space: nowrap;
        padding: 5px 8px;
        border-radius: 999px;
        background: rgba(0,0,0,.72);
        color: #fff;
        font-size: .72rem;
        font-weight: 800;
        opacity: 0;
        pointer-events: none;
        transition: opacity .16s ease, transform .16s ease;
    }

    .part-hotspot:hover::after,
    .part-hotspot.active::after {
        opacity: 1;
        transform: translateX(-50%) translateY(-2px);
    }

    .part-hotspot.motor { left: 68%; top: 39%; }
    .part-hotspot.freios { left: 20%; top: 62%; }
    .part-hotspot.suspensao { left: 70%; top: 66%; }
    .part-hotspot.rodas { left: 13%; top: 72%; }
    .part-hotspot.eletrica { left: 80%; top: 25%; }
    .part-hotspot.transmissao { left: 48%; top: 58%; }
    .part-hotspot.carroceria { left: 54%; top: 22%; }
    .part-hotspot.interior { left: 37%; top: 34%; }

    .parts-region-panel {
        padding: 1rem;
    }

    .parts-region-active {
        min-height: 150px;
        display: grid;
        align-content: center;
        gap: .6rem;
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: rgba(255,255,255,.035);
    }

    .parts-region-active .icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        color: #fff;
        background: linear-gradient(135deg, #ff2020, #970000);
    }

    .parts-region-active strong {
        color: var(--text);
        font-family: 'Syne', sans-serif;
        font-size: 1.15rem;
    }

    .parts-region-active p {
        margin: 0;
        color: var(--text2);
        line-height: 1.45;
    }

    .parts-region-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .65rem;
        margin-top: .85rem;
    }

    .parts-region-chip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .7rem;
        min-height: 52px;
        padding: .65rem .75rem;
        border: 1px solid var(--border);
        border-radius: 9px;
        color: var(--text);
        background: rgba(255,255,255,.025);
        text-decoration: none;
    }

    .parts-region-chip:hover,
    .parts-region-chip.active {
        color: var(--text);
        border-color: rgba(196,0,0,.35);
        background: rgba(196,0,0,.12);
    }

    .parts-region-chip span {
        display: flex;
        align-items: center;
        gap: .45rem;
        min-width: 0;
        font-weight: 800;
    }

    .parts-region-chip small {
        flex: 0 0 auto;
        padding: .2rem .45rem;
        border-radius: 999px;
        color: #fff;
        background: rgba(108,117,125,.8);
        font-weight: 800;
    }

    .parts-region-chip.active small {
        background: var(--red);
    }

    .parts-clear-region {
        margin-top: .85rem;
    }

    .parts-catalog-filter {
        margin-bottom: 1rem;
        border: 1px solid var(--border);
        border-radius: 12px;
        background:
            radial-gradient(circle at 8% 20%, rgba(196,0,0,.10), transparent 28%),
            rgba(255,255,255,.028);
        overflow: hidden;
    }

    .parts-catalog-filter-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .parts-filter-title {
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        min-width: 0;
    }

    .parts-filter-title-icon {
        flex: 0 0 auto;
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 11px;
        color: #fff;
        background: linear-gradient(135deg, #ff2020, #8f0000);
        box-shadow: 0 16px 38px rgba(196,0,0,.22);
    }

    .parts-catalog-filter-head h2 {
        margin: 0;
        color: var(--text);
        font-family: 'Syne', sans-serif;
        font-size: clamp(1rem, 1.6vw, 1.35rem);
        font-weight: 900;
        line-height: 1.1;
    }

    .parts-catalog-filter-head p {
        margin: .35rem 0 0;
        color: var(--text2);
        font-size: .9rem;
        line-height: 1.45;
    }

    .parts-catalog-filter form {
        padding: 1rem;
    }

    .parts-filter-grid {
        display: grid;
        grid-template-columns: minmax(180px, 1fr) minmax(180px, 1fr) minmax(220px, 1.15fr) auto;
        gap: .75rem;
        align-items: end;
    }

    .parts-filter-field {
        min-width: 0;
    }

    .parts-filter-field .form-label {
        display: flex;
        align-items: center;
        gap: .35rem;
        min-height: 20px;
        margin-bottom: .35rem;
    }

    .parts-filter-step {
        display: inline-grid;
        place-items: center;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        color: #fff;
        background: rgba(196,0,0,.86);
        font-size: .7rem;
        font-weight: 900;
    }

    .parts-filter-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .5rem;
        flex-wrap: wrap;
        min-width: 230px;
    }

    .parts-stock-toggle {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        min-height: 42px;
        padding: .45rem .65rem;
        border: 1px solid var(--border);
        border-radius: 9px;
        color: var(--text2);
        background: rgba(255,255,255,.025);
        font-weight: 800;
        white-space: nowrap;
    }

    .parts-active-tags {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-top: .9rem;
        padding-top: .9rem;
        border-top: 1px solid var(--border);
    }

    .parts-active-tags span {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .35rem .6rem;
        border-radius: 999px;
        color: var(--text);
        background: rgba(196,0,0,.12);
        border: 1px solid rgba(196,0,0,.22);
        font-size: .78rem;
        font-weight: 800;
    }

    .parts-compat-stack {
        display: grid;
        gap: 2px;
    }

    .parts-compat-stack strong {
        color: var(--text);
    }

    .parts-compat-stack span {
        color: var(--text2);
        font-size: .8rem;
    }

    :root[data-theme="light"] .parts-xray-card {
        background:
            radial-gradient(circle at 82% 20%, rgba(196,0,0,.12), transparent 30%),
            linear-gradient(145deg, rgba(255,255,255,.92), rgba(247,241,233,.84));
    }

    :root[data-theme="light"] .parts-car-stage {
        border-color: rgba(31,25,20,.10);
        background:
            linear-gradient(90deg, rgba(31,25,20,.05) 1px, transparent 1px),
            linear-gradient(0deg, rgba(31,25,20,.04) 1px, transparent 1px),
            radial-gradient(circle at 50% 50%, rgba(176,0,0,.08), transparent 48%);
        background-size: 34px 34px, 34px 34px, auto;
    }

    :root[data-theme="light"] .parts-car-svg .shell {
        fill: rgba(75, 125, 160, .12);
        stroke: rgba(35, 65, 85, .48);
    }

    :root[data-theme="light"] .parts-car-svg .glass,
    :root[data-theme="light"] .parts-car-svg .inner {
        stroke: rgba(35, 65, 85, .24);
    }

    @media (max-width: 980px) {
        .parts-xray {
            grid-template-columns: 1fr;
        }

        .parts-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .parts-filter-actions {
            justify-content: flex-start;
            min-width: 0;
        }
    }

    @media (max-width: 560px) {
        .parts-catalog-filter-head,
        .parts-filter-title {
            flex-direction: column;
        }

        .parts-filter-grid {
            grid-template-columns: 1fr;
        }

        .parts-filter-actions .btn,
        .parts-catalog-filter-head .btn {
            width: 100%;
        }

        .parts-stock-toggle {
            width: 100%;
            justify-content: center;
        }

        .parts-region-grid {
            grid-template-columns: 1fr;
        }

        .parts-car-wrap {
            min-height: 280px;
        }

        .parts-car-stage {
            height: 250px;
        }

        .part-hotspot {
            width: 38px;
            height: 38px;
            border-radius: 12px;
        }
    }
</style>
@endpush

@section('content')
@php
    $queryBase = request()->except(['page', 'regiao']);
    $regionQueryBase = request()->except(['page']);
    $activeRegion = $regiaoAtiva ? $regioesPecas[$regiaoAtiva] : null;
    $marcaNome = $marcaAtiva ? optional($marcas->firstWhere('id', $marcaAtiva))->nome : null;
    $modeloNome = $modeloAtivo ? optional($modelosDisponiveis->firstWhere('id', $modeloAtivo))->nome : null;
@endphp

@if($criticas > 0)
<div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <strong>{{ $criticas }} peca(s)</strong> com estoque abaixo do minimo!
    <a href="{{ route('pecas.index', ['estoque_baixo'=>1]) }}" class="ms-auto btn btn-sm btn-danger">Ver criticas</a>
</div>
@endif

<section class="parts-catalog-filter">
    <div class="parts-catalog-filter-head">
        <div class="parts-filter-title">
            <span class="parts-filter-title-icon"><i class="bi bi-car-front"></i></span>
            <div>
                <span class="parts-xray-kicker">Catalogo por veiculo</span>
                <h2>Filtre o estoque pelo carro do cliente</h2>
                <p>Selecione marca e modelo, depois refine por nome, codigo, fabricante ou pela regiao do carro no mapa abaixo.</p>
            </div>
        </div>
        <a href="{{ route('pecas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nova Peca</a>
    </div>

    <form method="GET">
        @if($regiaoAtiva)
            <input type="hidden" name="regiao" value="{{ $regiaoAtiva }}">
        @endif

        <div class="parts-filter-grid">
            <div class="parts-filter-field">
                <label class="form-label"><span class="parts-filter-step">1</span>Marca</label>
                <select name="marca_id" id="parts-filter-marca" class="form-select">
                    <option value="">Todas as marcas</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}" @selected($marcaAtiva === $marca->id)>{{ $marca->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="parts-filter-field">
                <label class="form-label"><span class="parts-filter-step">2</span>Modelo</label>
                <select name="modelo_id" id="parts-filter-modelo" class="form-select" @disabled(! $marcaAtiva)>
                    <option value="">Todos os modelos</option>
                    @foreach($modelosDisponiveis as $modelo)
                        <option value="{{ $modelo->id }}" @selected($modeloAtivo === $modelo->id)>{{ $modelo->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="parts-filter-field">
                <label class="form-label"><span class="parts-filter-step">3</span>Busca rapida</label>
                <input type="text" name="busca" class="form-control" placeholder="Nome, codigo ou fabricante..." value="{{ request('busca') }}">
            </div>

            <div class="parts-filter-actions">
                <label class="parts-stock-toggle">
                    <input class="form-check-input m-0" type="checkbox" name="estoque_baixo" value="1" {{ request('estoque_baixo') ? 'checked' : '' }}>
                    <span>Estoque critico</span>
                </label>
                <button class="btn btn-outline-secondary"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('pecas.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </div>

        @if($marcaNome || $modeloNome || $activeRegion || request('busca') || request('estoque_baixo'))
            <div class="parts-active-tags">
                @if($marcaNome)<span><i class="bi bi-car-front"></i>{{ $marcaNome }}</span>@endif
                @if($modeloNome)<span><i class="bi bi-tag"></i>{{ $modeloNome }}</span>@endif
                @if($activeRegion)
                    <span>
                        <i class="bi {{ $activeRegion['icone'] }}"></i>
                        {{ $activeRegion['label'] }}{{ ($regiaoInferida ?? null) ? ' detectada pela busca' : '' }}
                    </span>
                @endif
                @if(request('busca'))<span><i class="bi bi-search"></i>{{ request('busca') }}</span>@endif
                @if(request('estoque_baixo'))<span><i class="bi bi-exclamation-triangle"></i>Estoque critico</span>@endif
            </div>
        @endif
    </form>
</section>

<section class="parts-xray">
    <div class="parts-xray-card">
        <div class="parts-xray-head">
            <div>
                <span class="parts-xray-kicker">Mapa inteligente de pecas</span>
                <h2 class="parts-xray-title">Clique na regiao do carro</h2>
                <p class="parts-xray-sub">O estoque sera filtrado pela area escolhida: motor, freios, suspensao, eletrica e mais.</p>
            </div>
            @if($regiaoAtiva)
                <a href="{{ route('pecas.index', request()->except(['page', 'regiao'])) }}" class="btn btn-sm btn-outline-secondary">Limpar regiao</a>
            @endif
        </div>

        <div class="parts-car-wrap">
            <div class="parts-car-stage" aria-label="Modelo transparente de carro com regioes clicaveis">
                <svg class="parts-car-svg" viewBox="0 0 760 320" aria-hidden="true">
                    <path class="shell" d="M58 214 C92 147 161 123 239 118 L299 63 C327 37 380 31 425 54 L548 116 C625 123 685 154 716 210 L697 241 L87 241 Z"/>
                    <path class="glass" d="M272 116 L323 68 C348 51 382 49 418 66 L491 116 Z"/>
                    <path class="glass" d="M505 120 L566 126 C614 133 655 158 681 198 L576 191 Z"/>
                    <path class="inner" d="M302 132 L464 132 L519 198 L245 198 Z"/>
                    <path class="line" d="M110 210 C190 172 316 159 438 178 C524 191 608 191 685 211"/>
                    <path class="line" d="M233 120 L229 236"/>
                    <path class="line" d="M501 118 L560 236"/>
                    <path class="line" d="M354 132 L355 235"/>
                    <path class="line" d="M603 151 L642 218"/>
                    <circle class="wheel" cx="177" cy="238" r="48"/>
                    <circle class="wheel" cx="604" cy="238" r="48"/>
                    <circle class="line" cx="177" cy="238" r="25"/>
                    <circle class="line" cx="604" cy="238" r="25"/>
                    <path class="line" d="M536 149 C588 151 633 170 666 205"/>
                    <path class="line" d="M96 205 C124 181 161 168 217 160"/>
                </svg>

                @foreach($regioesPecas as $key => $regiao)
                    <a
                        href="{{ route('pecas.index', array_merge($queryBase, ['regiao' => $key])) }}"
                        class="part-hotspot {{ $key }} {{ $regiaoAtiva === $key ? 'active' : '' }}"
                        data-label="{{ $regiao['label'] }}"
                        title="{{ $regiao['label'] }}"
                    >
                        <i class="bi {{ $regiao['icone'] }}"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <aside class="parts-xray-card parts-region-panel">
        <div class="parts-region-active">
            @if($activeRegion)
                <span class="icon"><i class="bi {{ $activeRegion['icone'] }}"></i></span>
                <strong>{{ $activeRegion['label'] }}</strong>
                <p>{{ $activeRegion['descricao'] }}</p>
                @if($regiaoInferida ?? null)
                    <span class="badge bg-danger">Regiao detectada pela busca</span>
                @endif
                <span class="badge bg-danger">{{ $contagemRegioes[$regiaoAtiva] ?? 0 }} peca(s) relacionadas</span>
            @else
                <span class="icon"><i class="bi bi-hand-index-thumb"></i></span>
                <strong>Escolha uma regiao</strong>
                <p>Use o modelo do carro para encontrar rapidamente as pecas que combinam com a necessidade do atendimento.</p>
            @endif
        </div>

        <div class="parts-region-grid">
            @foreach($regioesPecas as $key => $regiao)
                <a href="{{ route('pecas.index', array_merge($queryBase, ['regiao' => $key])) }}" class="parts-region-chip {{ $regiaoAtiva === $key ? 'active' : '' }}">
                    <span><i class="bi {{ $regiao['icone'] }}"></i>{{ $regiao['label'] }}</span>
                    <small>{{ $contagemRegioes[$key] ?? 0 }}</small>
                </a>
            @endforeach
        </div>

        @if($regiaoAtiva)
            <a href="{{ route('pecas.index', request()->except(['page', 'regiao'])) }}" class="btn btn-outline-danger w-100 parts-clear-region">
                <i class="bi bi-x-circle me-1"></i>Remover filtro da regiao
            </a>
        @endif
    </aside>
</section>

<div class="card">
    <div class="card-header d-flex justify-content-between gap-2 flex-wrap">
        <span><i class="bi bi-box-seam me-2"></i>Resultados do estoque</span>
        <span class="badge bg-secondary">{{ $pecas->total() }} encontrada(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Codigo</th>
                        <th>Nome</th>
                        <th>Fabricante</th>
                        <th>Compatibilidade</th>
                        <th>Preco venda</th>
                        <th>Estoque</th>
                        <th>Minimo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pecas as $p)
                    <tr>
                        <td class="font-mono small">{{ $p->codigo ?? '-' }}</td>
                        <td>{{ $p->nome }}</td>
                        <td>{{ $p->fabricante ?? '-' }}</td>
                        <td>
                            <span class="parts-compat-stack">
                                <strong>{{ $p->marcaVeiculo?->nome ?? 'Universal' }}</strong>
                                <span>{{ $p->modeloVeiculo?->nome ?? 'Todos os modelos' }}</span>
                            </span>
                        </td>
                        <td class="font-mono">R$ {{ number_format($p->preco_venda, 2, ',', '.') }}</td>
                        <td class="{{ $p->estoqueAbaixoMinimo() ? 'estoque-critico' : '' }} font-mono">
                            {{ $p->estoque }} {{ $p->unidade }}
                            @if($p->estoqueAbaixoMinimo())<i class="bi bi-exclamation-triangle-fill ms-1"></i>@endif
                        </td>
                        <td class="font-mono text-muted">{{ $p->estoque_minimo }}</td>
                        <td class="text-end">
                            <a href="{{ route('pecas.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('pecas.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Excluir peca?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Nenhuma peca encontrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $pecas->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

@push('scripts')
<script>
    const modelosPorMarcaFiltroPeca = @json($marcas->mapWithKeys(fn ($marca) => [$marca->id => $marca->modelos->map(fn ($modelo) => ['id' => $modelo->id, 'nome' => $modelo->nome])->values()]));
    const marcaFiltroPeca = document.getElementById('parts-filter-marca');
    const modeloFiltroPeca = document.getElementById('parts-filter-modelo');
    const modeloAtivoPeca = Number(@json($modeloAtivo));

    function preencherModelosFiltroPeca(selectedId = null) {
        if (!marcaFiltroPeca || !modeloFiltroPeca) return;

        const marcaId = marcaFiltroPeca.value;
        const modelos = modelosPorMarcaFiltroPeca[marcaId] || [];
        modeloFiltroPeca.innerHTML = '<option value="">Todos os modelos</option>';
        modeloFiltroPeca.disabled = modelos.length === 0;

        modelos.forEach((modelo) => {
            const option = document.createElement('option');
            option.value = modelo.id;
            option.textContent = modelo.nome;
            option.selected = Number(selectedId) === Number(modelo.id);
            modeloFiltroPeca.appendChild(option);
        });
    }

    marcaFiltroPeca?.addEventListener('change', () => preencherModelosFiltroPeca(null));
    preencherModelosFiltroPeca(modeloAtivoPeca);
</script>
@endpush
