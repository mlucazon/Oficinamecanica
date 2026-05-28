@extends('layouts.app')
@section('title','Tempo de Reparo')
@section('breadcrumb','Relatorios / Tempo de Reparo')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-stopwatch me-2"></i>Tempo Medio de Reparo por Mecanico</div>
    <div class="card-body">
        @php($maiorMedia = max((float) $dados->max('media_horas'), 1))
        @if($dados->count())
            <div class="report-chart">
                @foreach($dados as $d)
                    <div class="report-chart-row">
                        <div>
                            <div class="report-chart-label">{{ $d->mecanico->nome ?? '-' }}</div>
                            <div class="report-chart-muted">{{ $d->total }} OS concluida{{ $d->total == 1 ? '' : 's' }}</div>
                        </div>
                        <div class="report-chart-track">
                            <span class="report-chart-bar" style="width: {{ max(4, ($d->media_horas / $maiorMedia) * 100) }}%"></span>
                        </div>
                        <div class="report-chart-value">{{ number_format($d->media_horas,1) }}h</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">Sem dados.</div>
        @endif
    </div>
    <div class="card-body p-0 border-top">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Mecanico</th><th>OS Concluidas</th><th>Media de horas</th></tr></thead>
            <tbody>
                @forelse($dados as $d)
                <tr>
                    <td>{{ $d->mecanico->nome ?? '-' }}</td>
                    <td>{{ $d->total }}</td>
                    <td class="font-mono">{{ number_format($d->media_horas,1) }}h</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
