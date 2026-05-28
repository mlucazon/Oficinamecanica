@extends('layouts.app')
@section('title','Pecas Mais Usadas')
@section('breadcrumb','Relatorios / Pecas Mais Usadas')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-box-seam me-2"></i>Pecas Mais Utilizadas</div>
    <div class="card-body">
        @php($maiorQtd = max((float) $dados->max('total_qtd'), 1))
        @if($dados->count())
            <div class="report-chart">
                @foreach($dados->take(10) as $d)
                    <div class="report-chart-row">
                        <div>
                            <div class="report-chart-label">{{ $d->peca->nome ?? '-' }}</div>
                            <div class="report-chart-muted">{{ $d->peca->codigo ?? 'Sem codigo' }}</div>
                        </div>
                        <div class="report-chart-track">
                            <span class="report-chart-bar" style="width: {{ max(4, ($d->total_qtd / $maiorQtd) * 100) }}%"></span>
                        </div>
                        <div class="report-chart-value">{{ $d->total_qtd }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">Sem dados.</div>
        @endif
    </div>
    <div class="card-body p-0 border-top">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>#</th><th>Peca</th><th>Qtd. Usada</th><th>Valor Total</th></tr></thead>
            <tbody>
                @forelse($dados as $i => $d)
                <tr>
                    <td class="text-muted">{{ $i+1 }}</td>
                    <td>{{ $d->peca->nome ?? '-' }}<br><span class="font-mono small text-muted">{{ $d->peca->codigo ?? '' }}</span></td>
                    <td class="font-mono">{{ $d->total_qtd }}</td>
                    <td class="font-mono">R$ {{ number_format($d->total_valor,2,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
