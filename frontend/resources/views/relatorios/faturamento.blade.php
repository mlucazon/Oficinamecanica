@extends('layouts.app')
@section('title','Faturamento')
@section('breadcrumb','Relatorios / Faturamento')

@section('content')
@php
    $meses = [1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'];
    $nomesMeses = [1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Marco', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'];
    $porMes = $dados->keyBy('mes');
    $totalAno = (float) $dados->sum('total');
    $totalOs = (int) $dados->sum('qtd');
    $ticketMedio = $totalOs > 0 ? $totalAno / $totalOs : 0;
    $maiorFaturamento = max((float) $dados->max('total'), 1);
    $melhorMes = $dados->sortByDesc('total')->first();
@endphp

<div class="report-hero">
    <div>
        <h1>Faturamento {{ $ano }}</h1>
        <p>Receita por mes, volume de OS finalizadas e ticket medio para acompanhar o ritmo financeiro da oficina.</p>
    </div>
    <form method="GET" class="report-hero-actions">
        <input type="number" name="ano" class="form-control" value="{{ $ano }}" min="2020" max="{{ date('Y') }}" style="width:110px">
        <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
    </form>
</div>

<div class="metric-grid">
    <div class="metric-tile">
        <span class="metric-icon green"><i class="bi bi-cash-stack"></i></span>
        <div>
            <div class="metric-label">Receita total</div>
            <div class="metric-value">R$ {{ number_format($totalAno, 2, ',', '.') }}</div>
            <div class="metric-note">{{ $totalOs }} OS finalizada{{ $totalOs === 1 ? '' : 's' }}</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon blue"><i class="bi bi-receipt"></i></span>
        <div>
            <div class="metric-label">Ticket medio</div>
            <div class="metric-value">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</div>
            <div class="metric-note">por OS concluida</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon amber"><i class="bi bi-trophy"></i></span>
        <div>
            <div class="metric-label">Melhor mes</div>
            <div class="metric-value">{{ $melhorMes ? $nomesMeses[$melhorMes->mes] : '-' }}</div>
            <div class="metric-note">{{ $melhorMes ? 'R$ ' . number_format($melhorMes->total, 2, ',', '.') : 'Sem faturamento' }}</div>
        </div>
    </div>
</div>

<div class="viz-grid">
    <div class="viz-card">
        <div class="viz-head">
            <h2 class="viz-title"><i class="bi bi-bar-chart-fill text-danger"></i>Receita mensal</h2>
            <span class="badge bg-secondary">{{ $dados->count() }} mes{{ $dados->count() === 1 ? '' : 'es' }} com movimento</span>
        </div>
        <div class="viz-body">
            @if($dados->count())
                <div class="column-chart" style="--columns: 12;">
                    @foreach($meses as $numero => $nome)
                        @php($valor = (float) optional($porMes->get($numero))->total)
                        <div class="column-item" title="{{ $nomesMeses[$numero] }} - R$ {{ number_format($valor, 2, ',', '.') }}">
                            <div class="column-bar-wrap">
                                <span class="column-bar" style="height: {{ max(5, ($valor / $maiorFaturamento) * 100) }}%; opacity: {{ $valor > 0 ? '1' : '.18' }}"></span>
                            </div>
                            <div class="column-label">{{ $nome }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">Sem dados para {{ $ano }}.</div>
            @endif
        </div>
    </div>

    <div class="viz-card">
        <div class="viz-head">
            <h2 class="viz-title"><i class="bi bi-stars text-warning"></i>Meses em destaque</h2>
        </div>
        <div class="viz-body">
            <div class="ranking-list">
                @forelse($dados->sortByDesc('total')->take(5)->values() as $i => $d)
                    <div class="ranking-row">
                        <span class="ranking-pos">{{ $i + 1 }}</span>
                        <div>
                            <div class="ranking-title">{{ $nomesMeses[$d->mes] }}</div>
                            <div class="ranking-sub">{{ $d->qtd }} OS finalizada{{ $d->qtd == 1 ? '' : 's' }}</div>
                        </div>
                        <div class="ranking-value">R$ {{ number_format($d->total, 2, ',', '.') }}</div>
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
            <thead class="table-light"><tr><th>Mes</th><th>OS Finalizadas</th><th>Faturamento</th></tr></thead>
            <tbody>
                @forelse($dados as $d)
                    <tr>
                        <td>{{ $nomesMeses[$d->mes] }}</td>
                        <td>{{ $d->qtd }}</td>
                        <td class="font-mono fw-500">R$ {{ number_format($d->total, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">Sem dados para {{ $ano }}.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
