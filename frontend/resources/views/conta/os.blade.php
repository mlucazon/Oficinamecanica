@extends('layouts.app')
@section('title', 'Minhas Ordens de Servico')
@section('breadcrumb', 'Minhas Ordens de Servico')

@php
    $clienteLogado = auth()->user()->cliente;
    $temVeiculos = $clienteLogado && $clienteLogado->veiculos()->exists();
@endphp

@push('styles')
<style>
    .os-mobile-list {
        display: none;
        padding: 12px;
        gap: 12px;
    }

    .os-mobile-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        background: rgba(255,255,255,.02);
    }

    .os-mobile-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .os-mobile-title strong {
        display: block;
        color: var(--text);
        font-size: 14px;
    }

    .os-mobile-title span {
        display: inline-block;
        margin-top: 4px;
    }

    .os-mobile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 12px;
    }

    .os-mobile-field {
        min-width: 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 9px;
        background: rgba(255,255,255,.018);
    }

    .os-mobile-field span {
        display: block;
        margin-bottom: 3px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .os-mobile-field strong {
        display: block;
        color: var(--text);
        font-size: 13px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .os-mobile-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    @media (max-width: 767.98px) {
        .os-desktop-table {
            display: none;
        }

        .os-mobile-list {
            display: grid;
        }

        .os-mobile-actions .btn,
        .os-mobile-actions form {
            flex: 1 1 120px;
        }

        .os-mobile-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 380px) {
        .os-mobile-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <span><i class="bi bi-clipboard2-check me-2"></i>Ordens de Servico</span>
                @if($temVeiculos)
                    <a href="{{ route('os.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Nova OS
                    </a>
                @else
                    <a href="{{ route('conta.veiculos') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-car-front me-1"></i>Cadastrar veiculo primeiro
                    </a>
                @endif
            </div>
            <div class="card-body p-0">
                @if(!$temVeiculos)
                    <div class="alert alert-warning m-3">
                        <i class="bi bi-info-circle me-2"></i>Para abrir uma OS, primeiro cadastre um veiculo.
                    </div>
                @endif

                @if($ordens->count() > 0)
                <div class="os-desktop-table table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Numero</th>
                                <th>Veiculo</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Data</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ordens as $os)
                            <tr>
                                <td class="font-mono small fw-500">{{ $os->numero }}</td>
                                <td>
                                    {{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}
                                    <span class="badge bg-light text-dark font-mono">{{ $os->veiculo->placa }}</span>
                                </td>
                                <td><span class="badge bg-{{ $os->statusCor() }}">{{ $os->statusLabel() }}</span></td>
                                <td class="font-mono">
                                    @if(in_array($os->status, ['aguardando_aceitacao', 'solicitacao_aceita', 'em_diagnostico', 'orcamento_enviado_atendente']) || (float) $os->valor_total <= 0)
                                        <span class="text-light">Aguardando orcamento</span>
                                    @else
                                        R$ {{ number_format($os->valor_total, 2, ',', '.') }}
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $os->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('os.show', $os->id) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar OS">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($os->status === 'finalizada')
                                        @if(!$os->avaliacao)
                                            <a href="{{ route('avaliacoes.create', $os) }}" class="btn btn-sm btn-outline-warning" title="Avaliar OS">
                                                <i class="bi bi-star"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('avaliacoes.index') }}" class="btn btn-sm btn-outline-success" title="Ver avaliacao">
                                                <i class="bi bi-star-fill"></i>
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('os.destroy', $os->id) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja apagar esta OS finalizada do seu historico? Esta acao nao pode ser desfeita.')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Excluir OS">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="os-mobile-list">
                    @foreach($ordens as $os)
                    <div class="os-mobile-card">
                        <div class="os-mobile-head">
                            <div class="os-mobile-title">
                                <strong>{{ $os->numero }}</strong>
                                <span class="badge bg-{{ $os->statusCor() }}">{{ $os->statusLabel() }}</span>
                            </div>
                            <span class="small text-muted">{{ $os->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="os-mobile-grid">
                            <div class="os-mobile-field">
                                <span>Veiculo</span>
                                <strong>{{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}</strong>
                            </div>
                            <div class="os-mobile-field">
                                <span>Placa</span>
                                <strong class="font-mono">{{ $os->veiculo->placa }}</strong>
                            </div>
                            <div class="os-mobile-field">
                                <span>Total</span>
                                <strong>
                                    @if(in_array($os->status, ['aguardando_aceitacao', 'solicitacao_aceita', 'em_diagnostico', 'orcamento_enviado_atendente']) || (float) $os->valor_total <= 0)
                                        Aguardando orcamento
                                    @else
                                        R$ {{ number_format($os->valor_total, 2, ',', '.') }}
                                    @endif
                                </strong>
                            </div>
                            <div class="os-mobile-field">
                                <span>Data</span>
                                <strong>{{ $os->created_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        </div>
                        <div class="os-mobile-actions">
                            <a href="{{ route('os.show', $os->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                            @if($os->status === 'finalizada')
                                @if(!$os->avaliacao)
                                    <a href="{{ route('avaliacoes.create', $os) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-star me-1"></i>Avaliar
                                    </a>
                                @else
                                    <a href="{{ route('avaliacoes.index') }}" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-star-fill me-1"></i>Avaliacao
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('os.destroy', $os->id) }}" onsubmit="return confirm('Tem certeza que deseja apagar esta OS finalizada do seu historico? Esta acao nao pode ser desfeita.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        <i class="bi bi-trash me-1"></i>Excluir
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info m-3">
                    <i class="bi bi-info-circle me-2"></i>Voce ainda nao possui ordens de servico.
                </div>
                @endif
            </div>
            @if($ordens->hasPages())
            <div class="card-footer">
                {{ $ordens->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
