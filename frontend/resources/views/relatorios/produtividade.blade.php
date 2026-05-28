@extends('layouts.app')
@section('title','Produtividade')
@section('breadcrumb','Relatorios / Produtividade')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-person-gear me-2"></i>Produtividade dos Mecanicos</div>
    <div class="card-body">
        @php($maiorOs = max((int) $dados->max('os_total'), 1))
        @if($dados->count())
            <div class="report-chart">
                @foreach($dados as $m)
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
    <div class="card-body p-0 border-top">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Mecanico</th><th>OS Total</th><th>OS esse mes</th><th>Faturamento</th></tr></thead>
            <tbody>
                @forelse($dados as $m)
                <tr>
                    <td>{{ $m->nome }}</td>
                    <td>{{ $m->os_total }}</td>
                    <td>{{ $m->os_mes }}</td>
                    <td class="font-mono">R$ {{ number_format($m->faturamento ?? 0,2,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
