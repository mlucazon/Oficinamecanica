@extends('layouts.app')
@section('title','Faturamento')
@section('breadcrumb','Relatorios / Faturamento')

@section('content')
@php
    $meses = ['','Janeiro','Fevereiro','Marco','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    $maiorFaturamento = max((float) $dados->max('total'), 1);
@endphp

<div class="card">
    <div class="card-header d-flex justify-content-between gap-2 flex-wrap">
        <span><i class="bi bi-cash-stack me-2"></i>Faturamento Mensal - {{ $ano }}</span>
        <form method="GET" class="d-flex gap-2">
            <input type="number" name="ano" class="form-control form-control-sm" value="{{ $ano }}" min="2020" max="{{ date('Y') }}" style="width:100px">
            <button class="btn btn-sm btn-outline-secondary">Filtrar</button>
        </form>
    </div>

    <div class="card-body">
        @if($dados->count())
            <div class="report-chart">
                @foreach($dados as $d)
                    <div class="report-chart-row">
                        <div>
                            <div class="report-chart-label">{{ $meses[$d->mes] }}</div>
                            <div class="report-chart-muted">{{ $d->qtd }} OS finalizada{{ $d->qtd == 1 ? '' : 's' }}</div>
                        </div>
                        <div class="report-chart-track">
                            <span class="report-chart-bar" style="width: {{ max(4, ($d->total / $maiorFaturamento) * 100) }}%"></span>
                        </div>
                        <div class="report-chart-value">R$ {{ number_format($d->total,2,',','.') }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">Sem dados para {{ $ano }}.</div>
        @endif
    </div>

    <div class="card-body p-0 border-top">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Mes</th><th>OS Finalizadas</th><th>Faturamento</th></tr></thead>
            <tbody>
                @forelse($dados as $d)
                <tr>
                    <td>{{ $meses[$d->mes] }}</td>
                    <td>{{ $d->qtd }}</td>
                    <td class="font-mono fw-500">R$ {{ number_format($d->total,2,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Sem dados para {{ $ano }}.</td></tr>
                @endforelse
            </tbody>
            @if($dados->count())
            <tfoot class="table-light fw-600">
                <tr>
                    <td>Total</td>
                    <td>{{ $dados->sum('qtd') }}</td>
                    <td class="font-mono">R$ {{ number_format($dados->sum('total'),2,',','.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
