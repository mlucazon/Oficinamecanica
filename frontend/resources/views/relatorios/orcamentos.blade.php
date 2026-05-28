@extends('layouts.app')
@section('title','Orcamentos')
@section('breadcrumb','Relatorios / Orcamentos')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-bar-chart-line me-2"></i>Orcamentos Aprovados vs Rejeitados</div>
    <div class="card-body">
        @php($maiorQtd = max((int) $dados->max('total'), 1))
        @if($dados->count())
            <div class="report-chart">
                @foreach($dados as $d)
                    @php($label = $d->aprovado_cliente ? 'Aprovado' : 'Nao aprovado')
                    <div class="report-chart-row">
                        <div>
                            <div class="report-chart-label">{{ $label }}</div>
                            <div class="report-chart-muted">R$ {{ number_format($d->valor,2,',','.') }}</div>
                        </div>
                        <div class="report-chart-track">
                            <span class="report-chart-bar" style="width: {{ max(4, ($d->total / $maiorQtd) * 100) }}%"></span>
                        </div>
                        <div class="report-chart-value">{{ $d->total }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">Sem dados.</div>
        @endif
    </div>
    <div class="card-body p-0 border-top">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Situacao</th><th>Quantidade</th><th>Valor Total</th></tr></thead>
            <tbody>
                @forelse($dados as $d)
                <tr>
                    <td><span class="badge {{ $d->aprovado_cliente ? 'bg-success' : 'bg-danger' }}">
                        {{ $d->aprovado_cliente ? 'Aprovado' : 'Nao aprovado' }}
                    </span></td>
                    <td>{{ $d->total }}</td>
                    <td class="font-mono">R$ {{ number_format($d->valor,2,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
