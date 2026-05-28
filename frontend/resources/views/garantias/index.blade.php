@extends('layouts.app')
@section('title','Garantias')
@section('breadcrumb','Garantias')

@push('styles')
<style>
    .warranties-table {
        display: block;
    }

    .warranties-mobile {
        display: none;
    }

    .warranty-card {
        border: 1px solid var(--border2);
        border-radius: 10px;
        padding: 1rem;
        background: var(--surface2);
    }

    .warranty-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: .75rem;
        margin-bottom: .85rem;
    }

    .warranty-card-head > div,
    .warranty-desc,
    .warranty-value {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .warranty-customer {
        color: var(--text2);
        font-size: 14px;
        line-height: 1.25;
        margin-top: .15rem;
    }

    .warranty-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .75rem;
    }

    .warranty-field {
        min-width: 0;
    }

    .warranty-label {
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: .18rem;
    }

    .warranty-value {
        color: var(--text);
        font-size: 14px;
        line-height: 1.3;
    }

    .warranty-desc {
        grid-column: 1 / -1;
    }

    .warranty-actions {
        margin-top: .9rem;
    }

    .warranty-actions .btn {
        width: 100%;
        min-height: 42px;
    }

    @media (max-width: 576px) {
        .warranties-table {
            display: none;
        }

        .warranties-mobile {
            display: grid;
            gap: .75rem;
            padding: 1rem;
        }

        .warranties-card .card-footer {
            padding: .75rem 1rem;
        }
    }
</style>
@endpush

@section('content')
@php($mostrarVeiculo = auth()->user()->isCliente())
<div class="card warranties-card">
    <div class="card-header">
        <i class="bi bi-shield-check me-2"></i>Garantias
    </div>
    <div class="card-body p-0">
        @if($garantias->count() > 0)
            <div class="warranties-table table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>OS</th>
                            <th>{{ $mostrarVeiculo ? 'Veiculo' : 'Cliente' }}</th>
                            <th>Descrição</th>
                            <th>Válida até</th>
                            <th>Situação</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($garantias as $g)
                            <tr>
                                <td class="font-mono small">{{ $g->ordemServico->numero }}</td>
                                <td>
                                    @if($mostrarVeiculo)
                                        {{ $g->ordemServico->veiculo->marca }} {{ $g->ordemServico->veiculo->modelo }}
                                        <br><span class="badge bg-light text-dark font-mono">{{ $g->ordemServico->veiculo->placa }}</span>
                                    @else
                                        {{ $g->ordemServico->cliente->nome }}
                                    @endif
                                </td>
                                <td>{{ $g->descricao }}</td>
                                <td>{{ $g->data_fim->format('d/m/Y') }}</td>
                                <td>
                                    @if($g->acionada)
                                        <span class="badge bg-warning text-dark">Acionada</span>
                                    @elseif($g->expirada())
                                        <span class="badge bg-secondary">Expirada</span>
                                    @else
                                        <span class="badge bg-success">Ativa</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($g->ativa())
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modal-{{ $g->id }}">
                                            <i class="bi bi-lightning me-1"></i>Acionar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="warranties-mobile">
                @foreach($garantias as $g)
                    <div class="warranty-card">
                        <div class="warranty-card-head">
                            <div>
                                <strong class="font-mono">OS {{ $g->ordemServico->numero }}</strong>
                                <div class="warranty-customer">
                                    @if($mostrarVeiculo)
                                        {{ $g->ordemServico->veiculo->marca }} {{ $g->ordemServico->veiculo->modelo }}
                                        <span class="font-mono">({{ $g->ordemServico->veiculo->placa }})</span>
                                    @else
                                        {{ $g->ordemServico->cliente->nome }}
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($g->acionada)
                                    <span class="badge bg-warning text-dark">Acionada</span>
                                @elseif($g->expirada())
                                    <span class="badge bg-secondary">Expirada</span>
                                @else
                                    <span class="badge bg-success">Ativa</span>
                                @endif
                            </div>
                        </div>

                        <div class="warranty-grid">
                            <div class="warranty-field warranty-desc">
                                <div class="warranty-label">Descrição</div>
                                <div class="warranty-value">{{ $g->descricao }}</div>
                            </div>
                            <div class="warranty-field">
                                <div class="warranty-label">Válida até</div>
                                <div class="warranty-value">{{ $g->data_fim->format('d/m/Y') }}</div>
                            </div>
                            <div class="warranty-field">
                                <div class="warranty-label">Situação</div>
                                <div class="warranty-value">
                                    @if($g->acionada)
                                        Acionada
                                    @elseif($g->expirada())
                                        Expirada
                                    @else
                                        Ativa
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($g->ativa())
                            <div class="warranty-actions">
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modal-{{ $g->id }}">
                                    <i class="bi bi-lightning me-1"></i>Acionar garantia
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">Nenhuma garantia registrada.</div>
        @endif
    </div>
    @if($garantias->hasPages())
        <div class="card-footer">{{ $garantias->links() }}</div>
    @endif
</div>

@foreach($garantias as $g)
    @if($g->ativa())
        <div class="modal fade" id="modal-{{ $g->id }}" tabindex="-1" aria-labelledby="modal-title-{{ $g->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title-{{ $g->id }}">Acionar Garantia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <form method="POST" action="{{ route('garantias.acionar',$g) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <label class="form-label">Descreva o motivo *</label>
                            <textarea name="observacao" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button class="btn btn-warning">Confirmar Acionamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
