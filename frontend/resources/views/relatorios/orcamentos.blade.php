@extends('layouts.app')
@section('title','Orcamentos')
@section('breadcrumb','Relatorios / Orcamentos')

@section('content')
@php
    $total = (int) $dados->sum('total');
    $aprovados = (int) optional($dados->firstWhere('aprovado_cliente', 1))->total;
    $valorAprovado = (float) optional($dados->firstWhere('aprovado_cliente', 1))->valor;
    $valorTotal = (float) $dados->sum('valor');
    $taxa = $total > 0 ? round(($aprovados / $total) * 100) : 0;
    $maiorQtd = max((int) $dados->max('total'), 1);
@endphp

<div class="report-hero">
    <div>
        <h1>Conversao de orcamentos</h1>
        <p>Veja quantos orcamentos viraram servico, o valor envolvido e onde a oficina pode melhorar a aprovacao.</p>
    </div>
</div>

<div class="metric-grid">
    <div class="metric-tile">
        <span class="metric-icon green"><i class="bi bi-check2-circle"></i></span>
        <div>
            <div class="metric-label">Taxa de aprovacao</div>
            <div class="metric-value">{{ $taxa }}%</div>
            <div class="metric-note">{{ $aprovados }} de {{ $total }} orcamento{{ $total === 1 ? '' : 's' }}</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon blue"><i class="bi bi-cash-coin"></i></span>
        <div>
            <div class="metric-label">Valor aprovado</div>
            <div class="metric-value">R$ {{ number_format($valorAprovado, 2, ',', '.') }}</div>
            <div class="metric-note">receita aceita pelos clientes</div>
        </div>
    </div>
    <div class="metric-tile">
        <span class="metric-icon amber"><i class="bi bi-clipboard-data"></i></span>
        <div>
            <div class="metric-label">Valor analisado</div>
            <div class="metric-value">R$ {{ number_format($valorTotal, 2, ',', '.') }}</div>
            <div class="metric-note">soma dos orcamentos</div>
        </div>
    </div>
</div>

<div class="viz-grid">
    <div class="viz-card">
        <div class="viz-head">
            <h2 class="viz-title"><i class="bi bi-pie-chart-fill text-success"></i>Aprovacao</h2>
        </div>
        <div class="viz-body text-center">
            @if($total > 0)
                <div class="donut-viz" style="--percent: {{ $taxa }}; --donut-color: var(--success);">
                    <div><strong>{{ $taxa }}%</strong><span>aprovados</span></div>
                </div>
                <div class="report-chart">
                    @foreach($dados as $d)
                        @php($label = $d->aprovado_cliente ? 'Aprovado' : 'Nao aprovado')
                        <div class="report-chart-row">
                            <div>
                                <div class="report-chart-label">{{ $label }}</div>
                                <div class="report-chart-muted">R$ {{ number_format($d->valor, 2, ',', '.') }}</div>
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
    </div>

    <div class="viz-card">
        <div class="viz-head">
            <h2 class="viz-title"><i class="bi bi-lightning-charge-fill text-warning"></i>Leitura rapida</h2>
        </div>
        <div class="viz-body">
            <div class="info-block-list">
                <div class="info-block">
                    <span class="info-block-icon success"><i class="bi bi-arrow-up-right"></i></span>
                    <div>
                        <div class="info-block-title">Orcamentos aprovados sustentam o faturamento</div>
                        <div class="info-block-text">Acompanhe a taxa de aprovacao para ajustar valores, prazo de entrega e comunicacao com o cliente.</div>
                    </div>
                </div>
                <div class="info-block">
                    <span class="info-block-icon warning"><i class="bi bi-chat-dots"></i></span>
                    <div>
                        <div class="info-block-title">Rejeicoes pedem contato rapido</div>
                        <div class="info-block-text">Quando o cliente recusa, vale entender se foi preco, urgencia ou falta de detalhe no diagnostico.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="bi bi-table me-2"></i>Resumo por situacao</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Situacao</th><th>Quantidade</th><th>Valor Total</th></tr></thead>
            <tbody>
                @forelse($dados as $d)
                    <tr>
                        <td><span class="badge {{ $d->aprovado_cliente ? 'bg-success' : 'bg-danger' }}">{{ $d->aprovado_cliente ? 'Aprovado' : 'Nao aprovado' }}</span></td>
                        <td>{{ $d->total }}</td>
                        <td class="font-mono">R$ {{ number_format($d->valor, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
