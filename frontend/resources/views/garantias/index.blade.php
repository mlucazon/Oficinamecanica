@extends('layouts.app')
@section('title','Garantias')
@section('breadcrumb','Garantias')

@push('styles')
<style>
    .warranty-list {
        display: grid;
        gap: 12px;
        padding: 14px;
    }

    .warranty-card {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 16px;
        align-items: center;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 16px 18px;
        background: rgba(255,255,255,.025);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.035);
    }

    .warranty-main {
        display: grid;
        grid-template-columns: 56px minmax(150px, .7fr) minmax(180px, .95fr) minmax(240px, 1.35fr);
        gap: 16px;
        align-items: center;
        min-width: 0;
    }

    .warranty-icon {
        width: 56px;
        height: 56px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(34, 197, 94, .14);
        color: #4ade80;
        font-size: 22px;
    }

    .warranty-card.is-used .warranty-icon {
        background: rgba(245, 158, 11, .16);
        color: #fbbf24;
    }

    .warranty-card.is-expired .warranty-icon {
        background: rgba(148, 163, 184, .14);
        color: #cbd5e1;
    }

    .warranty-kicker {
        display: block;
        margin-bottom: 5px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .warranty-title,
    .warranty-value {
        display: block;
        color: var(--text);
        font-size: 14px;
        font-weight: 700;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .warranty-title {
        font-family: var(--font-mono);
    }

    .warranty-desc,
    .warranty-note {
        color: var(--text2);
        font-size: 14px;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    .warranty-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .warranty-status {
        display: grid;
        justify-items: end;
        gap: 8px;
        min-width: 190px;
        text-align: right;
    }

    .warranty-note {
        max-width: 260px;
    }

    .warranty-empty {
        margin: 0;
        padding: 28px 16px;
        color: var(--text2);
        text-align: center;
    }

    @media (max-width: 900px) {
        .warranty-card,
        .warranty-main {
            grid-template-columns: 1fr;
        }

        .warranty-status {
            justify-items: start;
            min-width: 0;
            text-align: left;
        }

        .warranty-note {
            max-width: none;
        }
    }
</style>
@endpush

@section('content')
@php
    $mostrarVeiculo = auth()->user()->isCliente();
@endphp
<div class="card warranties-card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-shield-check me-2"></i>Garantias</span>
        @if($garantias->count() > 0)
            <span class="badge bg-secondary">{{ $garantias->total() }} registradas</span>
        @endif
    </div>
    <div class="card-body p-0">
        @if($garantias->count() > 0)
            <div class="warranty-list">
                @foreach($garantias as $g)
                    @php
                        $expirada = $g->expirada();
                        $ativa = $g->ativa();
                        $situacao = $g->acionada ? 'Acionada' : ($expirada ? 'Expirada' : ($ativa ? 'Ativa' : ucfirst(str_replace('_', ' ', $g->status))));
                        $cardClass = $g->acionada ? 'is-used' : ($expirada ? 'is-expired' : '');
                    @endphp

                    <div class="warranty-card {{ $cardClass }}">
                        <div class="warranty-main">
                            <div class="warranty-icon">
                                @if($g->acionada)
                                    <i class="bi bi-lightning-charge"></i>
                                @elseif($expirada)
                                    <i class="bi bi-shield-x"></i>
                                @else
                                    <i class="bi bi-shield-check"></i>
                                @endif
                            </div>

                            <div>
                                <span class="warranty-kicker">OS</span>
                                <strong class="warranty-title">{{ $g->ordemServico->numero }}</strong>
                                <div class="warranty-meta">
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">Ate {{ $g->data_fim->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div>
                                <span class="warranty-kicker">{{ $mostrarVeiculo ? 'Veiculo' : 'Cliente' }}</span>
                                @if($mostrarVeiculo)
                                    <strong class="warranty-value">{{ $g->ordemServico->veiculo->marca }} {{ $g->ordemServico->veiculo->modelo }}</strong>
                                    <div class="warranty-meta">
                                        <span class="badge bg-light text-dark font-mono">{{ $g->ordemServico->veiculo->placa }}</span>
                                    </div>
                                @else
                                    <strong class="warranty-value">{{ $g->ordemServico->cliente->nome }}</strong>
                                    <div class="warranty-meta">
                                        <span class="badge bg-light text-dark font-mono">{{ $g->ordemServico->veiculo->placa }}</span>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <span class="warranty-kicker">Descricao</span>
                                <div class="warranty-desc">{{ $g->descricao }}</div>
                            </div>
                        </div>

                        <div class="warranty-status">
                            @if($g->acionada)
                                <span class="badge bg-warning text-dark px-3 py-2">Acionada</span>
                                <div class="warranty-note">
                                    Usada automaticamente em {{ optional($g->data_acionamento)->format('d/m/Y H:i') ?? 'nova OS' }}.
                                </div>
                            @elseif($expirada)
                                <span class="badge bg-secondary px-3 py-2">Expirada</span>
                                <div class="warranty-note">Prazo encerrado em {{ $g->data_fim->format('d/m/Y') }}.</div>
                            @elseif($ativa)
                                <span class="badge bg-success px-3 py-2">Ativa</span>
                                <div class="warranty-note">Sera acionada automaticamente quando o cliente abrir uma nova OS para este veiculo.</div>
                            @else
                                <span class="badge bg-info text-dark px-3 py-2">{{ $situacao }}</span>
                                <div class="warranty-note">Aguardando conclusao do fluxo da garantia.</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="warranty-empty">Nenhuma garantia registrada.</p>
        @endif
    </div>
    @if($garantias->hasPages())
        <div class="card-footer">{{ $garantias->links() }}</div>
    @endif
</div>
@endsection
