@extends('layouts.app')
@section('title','Relatorios')
@section('breadcrumb','Relatorios')

@push('styles')
<style>
    .report-intro {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 1rem;
    }

    .report-intro h1 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 800;
    }

    .report-intro p {
        margin: .35rem 0 0;
        color: var(--text2);
        max-width: 720px;
        font-size: .95rem;
    }

    .report-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 1.2rem 0 .7rem;
        color: var(--text);
        font-weight: 800;
        font-size: .92rem;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .report-card {
        height: 100%;
        color: var(--text);
        text-decoration: none;
        display: block;
    }

    .report-card .card {
        height: 100%;
        transition: transform .16s ease, border-color .16s ease, background .16s ease;
    }

    .report-card:hover .card {
        transform: translateY(-2px);
        border-color: var(--red-border);
        background: var(--surface2);
    }

    .report-icon {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--red-dim);
        color: var(--red-h);
        font-size: 1.25rem;
        flex: 0 0 42px;
    }

    .report-card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: .75rem;
    }

    .report-card-title h2 {
        margin: 0;
        font-size: 1rem;
        line-height: 1.2;
        font-weight: 800;
    }

    .report-meta {
        display: grid;
        gap: .55rem;
        font-size: .86rem;
        color: var(--text2);
    }

    .report-meta strong {
        color: var(--text);
        font-weight: 800;
    }

    @media (max-width: 576px) {
        .report-intro {
            display: block;
        }
    }
</style>
@endpush

@section('content')
@php
    $grupos = [
        'Operacao' => [
            [
                'route' => 'relatorios.os-status',
                'icon' => 'bi-clipboard2-data',
                'titulo' => 'OS por Status',
                'mostra' => 'Quantidade de ordens em cada etapa da oficina.',
                'uso' => 'Identificar gargalos entre abertura, diagnostico, execucao e finalizacao.',
            ],
            [
                'route' => 'relatorios.tempo-reparo',
                'icon' => 'bi-stopwatch',
                'titulo' => 'Tempo Medio de Reparo',
                'mostra' => 'Tempo medio gasto para concluir os servicos.',
                'uso' => 'Comparar prazos e melhorar previsoes para os clientes.',
            ],
            [
                'route' => 'relatorios.produtividade',
                'icon' => 'bi-person-gear',
                'titulo' => 'Produtividade dos Mecanicos',
                'mostra' => 'Volume de OS e desempenho por mecanico.',
                'uso' => 'Distribuir melhor o trabalho e acompanhar a equipe.',
            ],
        ],
        'Financeiro' => [
            [
                'route' => 'relatorios.faturamento',
                'icon' => 'bi-cash-stack',
                'titulo' => 'Faturamento Mensal',
                'mostra' => 'Receita agrupada por mes.',
                'uso' => 'Acompanhar crescimento, queda de receita e meses mais fortes.',
            ],
            [
                'route' => 'relatorios.orcamentos',
                'icon' => 'bi-bar-chart-line',
                'titulo' => 'Orcamentos Aprovados e Rejeitados',
                'mostra' => 'Taxa de aprovacao dos orcamentos enviados.',
                'uso' => 'Entender conversao e ajustar valores, prazos ou comunicacao.',
            ],
        ],
        'Estoque e Garantia' => [
            [
                'route' => 'relatorios.pecas',
                'icon' => 'bi-box-seam',
                'titulo' => 'Pecas Mais Usadas',
                'mostra' => 'Pecas com maior saida nas ordens de servico.',
                'uso' => 'Planejar compras e evitar falta de itens importantes.',
            ],
            [
                'route' => 'relatorios.garantias',
                'icon' => 'bi-shield-check',
                'titulo' => 'Garantias Acionadas',
                'mostra' => 'Garantias abertas, aceitas e recusadas.',
                'uso' => 'Medir custos e recorrencia de problemas apos o reparo.',
            ],
        ],
        'Clientes' => [
            [
                'route' => 'relatorios.clientes',
                'icon' => 'bi-people',
                'titulo' => 'Clientes Recorrentes e Novos',
                'mostra' => 'Clientes que retornam e novos cadastros.',
                'uso' => 'Avaliar fidelizacao e oportunidades de retorno.',
            ],
        ],
    ];
@endphp

<div class="report-intro">
    <div>
        <h1>Relatorios da oficina</h1>
        <p>Escolha um relatorio conforme a decisao que voce precisa tomar: operacao, faturamento, estoque, garantia ou relacionamento com clientes.</p>
    </div>
</div>

@foreach($grupos as $grupo => $relatorios)
    <div class="report-section-title">
        <i class="bi bi-folder2-open"></i>
        <span>{{ $grupo }}</span>
    </div>

    <div class="row g-3">
        @foreach($relatorios as $relatorio)
            <div class="col-md-6 col-xl-4">
                <a href="{{ route($relatorio['route']) }}" class="report-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="report-card-title">
                                <span class="report-icon"><i class="bi {{ $relatorio['icon'] }}"></i></span>
                                <h2>{{ $relatorio['titulo'] }}</h2>
                            </div>
                            <div class="report-meta">
                                <div><strong>Mostra:</strong> {{ $relatorio['mostra'] }}</div>
                                <div><strong>Serve para:</strong> {{ $relatorio['uso'] }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endforeach
@endsection
