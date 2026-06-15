@extends('layouts.app')
@section('title','Pecas Mais Usadas')
@section('breadcrumb','Relatorios / Pecas Mais Usadas')

@section('content')
@php
    $totalQtd = (float) $dados->sum('total_qtd');
    $totalValor = (float) $dados->sum('total_valor');
    $maiorQtd = max((float) $dados->max('total_qtd'), 1);
    $top = $dados->sortByDesc('total_qtd')->first();
@endphp

<div class="report-hero">
    <div>
        <h1>Pecas mais utilizadas</h1>
        <p>Entenda quais itens mais saem nas OS e use esse sinal para planejar compras e evitar falta de estoque.</p>
    </div>
</div>

<div class="metric-grid">
    <div class="metric-tile">
        <span class="metric-icon blue"><i class="bi bi-box-seam"></i></span>
        <div>
            <div class="metric-label">Pecas movimentadas</div>
            <div class="metric-value">{{ number_format($totalQtd, 0, ',', '.') }}</div>
            <div class="metric-note">unidades usadas nas OS</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon green"><i class="bi bi-cash-coin"></i></span>
        <div>
            <div class="metric-label">Valor vendido</div>
            <div class="metric-value">R$ {{ number_format($totalValor, 2, ',', '.') }}</div>
            <div class="metric-note">soma das pecas aplicadas</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon amber"><i class="bi bi-star"></i></span>
        <div>
            <div class="metric-label">Item mais usado</div>
            <div class="metric-value">{{ $top?->peca?->nome ?? '-' }}</div>
            <div class="metric-note">{{ $top ? number_format($top->total_qtd, 0, ',', '.') . ' unidades' : 'Sem dados' }}</div>
        </div>
    </div>
</div>

<div class="viz-grid">
    <div class="viz-card">
        <div class="viz-head">
            <h2 class="viz-title"><i class="bi bi-bar-chart-fill text-danger"></i>Top 10 por quantidade</h2>
        </div>
        <div class="viz-body">
            @if($dados->count())
                <div class="report-chart">
                    @foreach($dados->sortByDesc('total_qtd')->take(10) as $d)
                        <div class="report-chart-row">
                            <div>
                                <div class="report-chart-label">{{ $d->peca->nome ?? '-' }}</div>
                                <div class="report-chart-muted">{{ $d->peca->codigo ?? 'Sem codigo' }}</div>
                            </div>
                            <div class="report-chart-track">
                                <span class="report-chart-bar" style="width: {{ max(4, ($d->total_qtd / $maiorQtd) * 100) }}%"></span>
                            </div>
                            <div class="report-chart-value">{{ number_format($d->total_qtd, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">Sem dados.</div>
            @endif
        </div>
    </div>

    <div class="viz-card">
        <div class="viz-head">
            <h2 class="viz-title"><i class="bi bi-list-ol text-warning"></i>Ranking</h2>
        </div>
        <div class="viz-body">
            <div class="ranking-list">
                @forelse($dados->sortByDesc('total_valor')->take(5)->values() as $i => $d)
                    <div class="ranking-row">
                        <span class="ranking-pos">{{ $i + 1 }}</span>
                        <div>
                            <div class="ranking-title">{{ $d->peca->nome ?? '-' }}</div>
                            <div class="ranking-sub">{{ number_format($d->total_qtd, 0, ',', '.') }} unidade{{ $d->total_qtd == 1 ? '' : 's' }}</div>
                        </div>
                        <div class="ranking-value">R$ {{ number_format($d->total_valor, 2, ',', '.') }}</div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Sem dados.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="bi bi-table me-2"></i>Detalhamento</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>#</th><th>Peca</th><th>Qtd. Usada</th><th>Valor Total</th></tr></thead>
            <tbody>
                @forelse($dados as $i => $d)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td>{{ $d->peca->nome ?? '-' }}<br><span class="font-mono small text-muted">{{ $d->peca->codigo ?? '' }}</span></td>
                        <td class="font-mono">{{ number_format($d->total_qtd, 0, ',', '.') }}</td>
                        <td class="font-mono">R$ {{ number_format($d->total_valor, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
