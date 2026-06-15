@extends('layouts.app')
@section('title','Tempo de Reparo')
@section('breadcrumb','Relatorios / Tempo de Reparo')

@section('content')
@php
    $totalOs = (int) $dados->sum('total');
    $mediaGeral = $dados->count() ? (float) $dados->avg('media_horas') : 0;
    $maiorMedia = max((float) $dados->max('media_horas'), 1);
    $maisRapido = $dados->sortBy('media_horas')->first();
@endphp

<div class="report-hero">
    <div>
        <h1>Tempo medio de reparo</h1>
        <p>Acompanhe quanto tempo a oficina leva para concluir OS e identifique onde os prazos podem melhorar.</p>
    </div>
</div>

<div class="metric-grid">
    <div class="metric-tile">
        <span class="metric-icon blue"><i class="bi bi-stopwatch"></i></span>
        <div>
            <div class="metric-label">Media geral</div>
            <div class="metric-value">{{ number_format($mediaGeral, 1, ',', '.') }}h</div>
            <div class="metric-note">entre mecanicos com OS concluidas</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon green"><i class="bi bi-check2-square"></i></span>
        <div>
            <div class="metric-label">OS concluidas</div>
            <div class="metric-value">{{ $totalOs }}</div>
            <div class="metric-note">base do calculo</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon amber"><i class="bi bi-lightning"></i></span>
        <div>
            <div class="metric-label">Menor media</div>
            <div class="metric-value">{{ $maisRapido?->mecanico?->nome ?? '-' }}</div>
            <div class="metric-note">{{ $maisRapido ? number_format($maisRapido->media_horas, 1, ',', '.') . 'h por OS' : 'Sem dados' }}</div>
        </div>
    </div>
</div>

<div class="viz-grid">
    <div class="viz-card">
        <div class="viz-head">
            <h2 class="viz-title"><i class="bi bi-hourglass-split text-danger"></i>Horas medias por mecanico</h2>
        </div>
        <div class="viz-body">
            @if($dados->count())
                <div class="report-chart">
                    @foreach($dados->sortBy('media_horas') as $d)
                        <div class="report-chart-row">
                            <div>
                                <div class="report-chart-label">{{ $d->mecanico->nome ?? '-' }}</div>
                                <div class="report-chart-muted">{{ $d->total }} OS concluida{{ $d->total == 1 ? '' : 's' }}</div>
                            </div>
                            <div class="report-chart-track">
                                <span class="report-chart-bar" style="width: {{ max(4, ($d->media_horas / $maiorMedia) * 100) }}%"></span>
                            </div>
                            <div class="report-chart-value">{{ number_format($d->media_horas, 1, ',', '.') }}h</div>
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
            <h2 class="viz-title"><i class="bi bi-speedometer2 text-warning"></i>Mais rapidos</h2>
        </div>
        <div class="viz-body">
            <div class="ranking-list">
                @forelse($dados->sortBy('media_horas')->take(5)->values() as $i => $d)
                    <div class="ranking-row">
                        <span class="ranking-pos">{{ $i + 1 }}</span>
                        <div>
                            <div class="ranking-title">{{ $d->mecanico->nome ?? '-' }}</div>
                            <div class="ranking-sub">{{ $d->total }} OS concluidas</div>
                        </div>
                        <div class="ranking-value">{{ number_format($d->media_horas, 1, ',', '.') }}h</div>
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
            <thead class="table-light"><tr><th>Mecanico</th><th>OS Concluidas</th><th>Media de horas</th></tr></thead>
            <tbody>
                @forelse($dados as $d)
                    <tr>
                        <td>{{ $d->mecanico->nome ?? '-' }}</td>
                        <td>{{ $d->total }}</td>
                        <td class="font-mono">{{ number_format($d->media_horas, 1, ',', '.') }}h</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
