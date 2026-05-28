@extends('layouts.app')
@section('title','OS por Status')
@section('breadcrumb','Relatorios / OS por Status')

@push('styles')
<style>
    .status-report-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: .85rem;
        margin-bottom: 1rem;
    }

    .status-report-card {
        padding: 1rem;
        border: 1px solid var(--border2);
        border-radius: 8px;
        background: rgba(255,255,255,.025);
    }

    .status-report-card strong {
        display: block;
        margin-top: .55rem;
        color: var(--text);
        font-size: 1.8rem;
        line-height: 1;
    }

    .status-detail-row {
        border-top: 1px solid var(--border2);
        padding: 1rem;
    }

    .status-detail-row:first-child {
        border-top: 0;
    }

    :root[data-theme="light"] .status-report-card {
        background: rgba(255,255,255,.72);
        border-color: rgba(31,25,20,.14);
    }
</style>
@endpush

@section('content')
@php
    $totalOrdens = $dados->sum('total');
    $meses = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Marco', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
    ];
@endphp

<div class="card mb-3">
    <div class="card-header">
        <i class="bi bi-funnel me-2"></i>Filtros
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Mes</label>
                <select name="mes" class="form-select">
                    <option value="">Todos os meses</option>
                    @foreach($meses as $numero => $nome)
                        <option value="{{ $numero }}" @selected((string)($filtros['mes'] ?? '') === (string)$numero)>{{ $nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ano</label>
                <input type="number" name="ano" class="form-control" value="{{ $filtros['ano'] ?? '' }}" min="2020" max="2100" placeholder="{{ now()->year }}">
            </div>
            <div class="col-md-4 d-flex gap-2 flex-wrap">
                <button class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('relatorios.os-status') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <span><i class="bi bi-clipboard2-data me-2"></i>Ordens de Servico por Status</span>
        <span class="badge bg-secondary">{{ $totalOrdens }} OS</span>
    </div>
    <div class="card-body">
        @if($dados->isEmpty())
            <div class="info-block">
                <span class="info-block-icon warning">
                    <i class="bi bi-clipboard-x"></i>
                </span>
                <div>
                    <div class="info-block-title">Sem dados para o periodo.</div>
                    <div class="info-block-text">Altere os filtros para consultar outras ordens de servico.</div>
                </div>
            </div>
        @else
            <div class="status-report-summary">
                @foreach($dados as $d)
                    @php($statusOrdens = $ordensPorStatus->get($d->status, collect()))
                    @php($primeiraOs = $statusOrdens->first())
                    <div class="status-report-card">
                        <span class="badge badge-{{ $d->status }}">{{ $primeiraOs?->statusLabel() ?? ucfirst(str_replace('_',' ',$d->status)) }}</span>
                        <strong>{{ $d->total }}</strong>
                        <div class="small" style="color: var(--text2);">ordem{{ $d->total == 1 ? '' : 's' }} neste status</div>
                    </div>
                @endforeach
            </div>

            @php($maiorStatus = max((int) $dados->max('total'), 1))
            <div class="report-chart mb-4">
                @foreach($dados as $d)
                    @php($statusOrdens = $ordensPorStatus->get($d->status, collect()))
                    @php($primeiraOs = $statusOrdens->first())
                    <div class="report-chart-row">
                        <div class="report-chart-label">{{ $primeiraOs?->statusLabel() ?? ucfirst(str_replace('_',' ',$d->status)) }}</div>
                        <div class="report-chart-track">
                            <span class="report-chart-bar" style="width: {{ max(4, ($d->total / $maiorStatus) * 100) }}%"></span>
                        </div>
                        <div class="report-chart-value">{{ $d->total }} OS</div>
                    </div>
                @endforeach
            </div>

            <div class="info-block-list">
                @foreach($dados as $d)
                    @php($statusOrdens = $ordensPorStatus->get($d->status, collect()))
                    @php($primeiraOs = $statusOrdens->first())
                    @php($collapseId = 'status-detalhes-' . preg_replace('/[^a-z0-9]+/i', '-', $d->status))
                    <div class="info-block">
                        <span class="info-block-icon info">
                            <i class="bi bi-kanban"></i>
                        </span>
                        <div>
                            <span class="info-block-kicker">Status</span>
                            <div class="info-block-title">{{ $primeiraOs?->statusLabel() ?? ucfirst(str_replace('_',' ',$d->status)) }}</div>
                            <div class="info-block-text">{{ $d->total }} OS com {{ $statusOrdens->pluck('cliente_id')->unique()->count() }} cliente{{ $statusOrdens->pluck('cliente_id')->unique()->count() == 1 ? '' : 's' }} envolvido{{ $statusOrdens->pluck('cliente_id')->unique()->count() == 1 ? '' : 's' }}.</div>
                        </div>
                        <div class="info-block-actions">
                            <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                <i class="bi bi-people me-1"></i>Ver clientes
                            </button>
                        </div>
                    </div>

                    <div class="collapse" id="{{ $collapseId }}">
                        <div class="card mt-2 mb-3">
                            <div class="card-body p-0">
                                @foreach($statusOrdens as $os)
                                    <div class="status-detail-row">
                                        <div class="info-block">
                                            <span class="info-block-icon">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <div>
                                                <span class="info-block-kicker">Cliente</span>
                                                <div class="info-block-title">{{ $os->cliente->nome }}</div>
                                                <div class="info-block-text">
                                                    {{ $os->veiculo->marca }} {{ $os->veiculo->modelo }} - <span class="font-mono">{{ $os->veiculo->placa }}</span>
                                                </div>
                                                <div class="info-block-meta">
                                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">OS {{ $os->numero }}</span>
                                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $os->created_at->format('d/m/Y H:i') }}</span>
                                                    @if($os->mecanico)
                                                        <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $os->mecanico->nome }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="info-block-actions">
                                                <a href="{{ route('os.show', $os->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-eye me-1"></i>Ver OS
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
