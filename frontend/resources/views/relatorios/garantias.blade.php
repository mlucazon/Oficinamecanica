@extends('layouts.app')
@section('title','Garantias')
@section('breadcrumb','Relatorios / Garantias')

@section('content')
@php
    $garantias = $dados->getCollection();
    $resumo = collect([
        'Ativas' => $garantias->filter(fn($g) => ! $g->acionada && ! $g->expirada())->count(),
        'Acionadas' => $garantias->where('acionada', true)->count(),
        'Expiradas' => $garantias->filter(fn($g) => $g->expirada())->count(),
    ]);
    $maiorResumo = max((int) $resumo->max(), 1);
@endphp

<div class="card">
    <div class="card-header"><i class="bi bi-shield-check me-2"></i>Garantias</div>
    <div class="card-body">
        @if($garantias->count())
            <div class="report-chart">
                @foreach($resumo as $label => $total)
                    <div class="report-chart-row">
                        <div class="report-chart-label">{{ $label }}</div>
                        <div class="report-chart-track">
                            <span class="report-chart-bar" style="width: {{ max(4, ($total / $maiorResumo) * 100) }}%"></span>
                        </div>
                        <div class="report-chart-value">{{ $total }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">Sem garantias.</div>
        @endif
    </div>
    <div class="card-body p-0 border-top">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>OS</th><th>Cliente</th><th>Descricao</th><th>Valida ate</th><th>Situacao</th></tr></thead>
            <tbody>
                @forelse($dados as $g)
                <tr>
                    <td class="font-mono small">{{ $g->ordemServico->numero }}</td>
                    <td>{{ $g->ordemServico->cliente->nome }}</td>
                    <td>{{ $g->descricao }}</td>
                    <td>{{ $g->data_fim->format('d/m/Y') }}</td>
                    <td><span class="badge {{ $g->acionada ? 'bg-warning text-dark' : ($g->expirada() ? 'bg-secondary' : 'bg-success') }}">
                        {{ $g->acionada ? 'Acionada' : ($g->expirada() ? 'Expirada' : 'Ativa') }}
                    </span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Sem garantias.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $dados->links() }}</div>
</div>
@endsection
