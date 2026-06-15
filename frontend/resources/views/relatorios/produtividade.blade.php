@extends('layouts.app')
@section('title','Produtividade')
@section('breadcrumb','Relatorios / Produtividade')

@section('content')
@php
    $totalOs = (int) $dados->sum('os_total');
    $totalMes = (int) $dados->sum('os_mes');
    $faturamento = (float) $dados->sum('faturamento');
    $maiorOs = max((int) $dados->max('os_total'), 1);
    $top = $dados->sortByDesc('os_total')->first();
@endphp

<div class="report-hero">
    <div>
        <h1>Produtividade da equipe</h1>
        <p>Volume de OS por mecanico, desempenho do mes e faturamento gerado pelos servicos finalizados.</p>
    </div>
</div>

<div class="metric-grid">
    <div class="metric-tile">
        <span class="metric-icon blue"><i class="bi bi-kanban"></i></span>
        <div>
            <div class="metric-label">OS registradas</div>
            <div class="metric-value">{{ $totalOs }}</div>
            <div class="metric-note">distribuidas entre {{ $dados->count() }} mecanico{{ $dados->count() === 1 ? '' : 's' }}</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon green"><i class="bi bi-calendar-check"></i></span>
        <div>
            <div class="metric-label">OS este mes</div>
            <div class="metric-value">{{ $totalMes }}</div>
            <div class="metric-note">movimento recente</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon amber"><i class="bi bi-award"></i></span>
        <div>
            <div class="metric-label">Maior volume</div>
            <div class="metric-value">{{ $top?->nome ?? '-' }}</div>
            <div class="metric-note">{{ $top ? $top->os_total . ' OS no total' : 'Sem dados' }}</div>
        </div>
    </div>
</div>

<div class="viz-grid">
    <div class="viz-card">
        <div class="viz-head">
            <h2 class="viz-title"><i class="bi bi-bar-chart-steps text-danger"></i>OS por mecanico</h2>
            <span class="badge bg-secondary">R$ {{ number_format($faturamento, 2, ',', '.') }}</span>
        </div>
        <div class="viz-body">
            @if($dados->count())
                <div class="report-chart">
                    @foreach($dados->sortByDesc('os_total') as $m)
                        <div class="report-chart-row">
                            <div>
                                <div class="report-chart-label">{{ $m->nome }}</div>
                                <div class="report-chart-muted">{{ $m->os_mes }} OS neste mes</div>
                            </div>
                            <div class="report-chart-track">
                                <span class="report-chart-bar" style="width: {{ max(4, ($m->os_total / $maiorOs) * 100) }}%"></span>
                            </div>
                            <div class="report-chart-value">{{ $m->os_total }} OS</div>
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
            <h2 class="viz-title"><i class="bi bi-list-stars text-warning"></i>Ranking</h2>
        </div>
        <div class="viz-body">
            <div class="ranking-list">
                @forelse($dados->sortByDesc('os_total')->take(5)->values() as $i => $m)
                    <div class="ranking-row">
                        <span class="ranking-pos">{{ $i + 1 }}</span>
                        <div>
                            <div class="ranking-title">{{ $m->nome }}</div>
                            <div class="ranking-sub">R$ {{ number_format($m->faturamento ?? 0, 2, ',', '.') }}</div>
                        </div>
                        <div class="ranking-value">{{ $m->os_total }} OS</div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Sem dados.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="bi bi-table me-2"></i>Detalhamento por mecanico</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Mecanico</th><th>OS Total</th><th>OS este mes</th><th>Faturamento</th></tr></thead>
            <tbody>
                @forelse($dados as $m)
                    <tr>
                        <td>{{ $m->nome }}</td>
                        <td>{{ $m->os_total }}</td>
                        <td>{{ $m->os_mes }}</td>
                        <td class="font-mono">R$ {{ number_format($m->faturamento ?? 0, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
