@extends('layouts.app')
@section('title', 'Notificacoes')
@section('breadcrumb', 'Notificacoes')

@push('styles')
<style>
    .notification-list {
        display: grid;
        gap: 12px;
        padding: 14px;
    }

    .card-body > .notification-list + .notification-list {
        padding-top: 0;
    }

    .notification-card {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 16px;
        align-items: center;
        border: 1px solid var(--border2);
        border-radius: 8px;
        padding: 18px 20px;
        background:
            linear-gradient(135deg, rgba(255,255,255,.035), rgba(255,255,255,.012)),
            rgba(255,255,255,.018);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.045);
    }

    .notification-main {
        display: grid;
        grid-template-columns: 64px minmax(160px, .75fr) minmax(180px, .9fr) minmax(260px, 1.45fr);
        gap: 18px;
        align-items: center;
        min-width: 0;
    }

    .notification-icon {
        width: 64px;
        height: 64px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(220, 0, 0, .12);
        color: #ff4d4d;
        font-size: 22px;
    }

    .notification-card.is-payment .notification-icon,
    .notification-card.is-done .notification-icon {
        background: rgba(34, 197, 94, .14);
        color: #4ade80;
    }

    .notification-card.is-warning .notification-icon {
        background: rgba(245, 158, 11, .16);
        color: #fbbf24;
    }

    .notification-kicker {
        display: block;
        margin-bottom: 5px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .notification-title,
    .notification-value {
        display: block;
        color: var(--text);
        font-weight: 700;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .notification-title {
        font-family: var(--font-mono);
        font-size: 14px;
    }

    .notification-value {
        font-size: 14px;
    }

    .notification-message {
        color: var(--text2);
        font-size: 14px;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    .notification-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .notification-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
        min-width: 190px;
    }

    .notification-actions .btn {
        min-height: 40px;
    }

    .notification-empty {
        margin: 0;
        padding: 28px 16px;
        color: var(--text2);
        text-align: center;
    }

    :root[data-theme="light"] .notification-card {
        background:
            linear-gradient(135deg, rgba(255,255,255,.82), rgba(255,255,255,.52)),
            rgba(255,255,255,.58);
        border-color: rgba(31,25,20,.14);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.8);
    }

    @media (max-width: 767.98px) {
        .notification-list {
            padding: 12px;
        }

        .notification-card,
        .notification-main {
            grid-template-columns: 1fr;
        }

        .notification-card {
            align-items: stretch;
        }

        .notification-actions {
            justify-content: stretch;
            min-width: 0;
        }

        .notification-actions .btn,
        .notification-actions form {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-end mb-3">
    <form method="POST" action="{{ route('notificacoes.limpar') }}" onsubmit="return confirm('Limpar apenas o histórico de notificações?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-outline-danger">
            <i class="bi bi-trash me-1"></i>Limpar histórico
        </button>
    </form>
</div>

@if(auth()->user()->isMecanico())
	<div class="row g-3">
	    <div class="col-lg-7">
	        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-person-lines-fill me-2 text-warning"></i>Avisos da oficina</span>
                @if($notificacoes_pendentes->count() > 0)
                    <span class="badge bg-danger">{{ $notificacoes_pendentes->count() }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                @forelse($notificacoes_pendentes as $notif)
                    <div class="notification-list">
                        <div class="notification-card {{ $notif->os->status === 'aguardando_finalizacao' ? 'is-payment' : '' }}">
                            <div class="notification-main">
                                <div class="notification-icon">
                                    @if($notif->os->status === 'aguardando_finalizacao')
                                        <i class="bi bi-flag-fill"></i>
                                    @else
                                        <i class="bi bi-tools"></i>
                                    @endif
                                </div>
                                <div>
                                    <span class="notification-kicker">OS</span>
                                    <strong class="notification-title">{{ $notif->os->numero }}</strong>
                                    <div class="notification-meta">
                                        <span class="badge badge-{{ $notif->os->status }}">{{ $notif->os->statusLabel() }}</span>
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $notif->created_at->format('d/m H:i') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="notification-kicker">Cliente</span>
                                    <strong class="notification-value">{{ $notif->os->cliente->nome }}</strong>
                                </div>
                                <div class="notification-message">
                                    {{ $notif->mensagem ?: 'Nova atualizacao de OS.' }}
                                </div>
                            </div>
                            <div class="notification-actions">
                                <a href="{{ route('os.show', $notif->os_id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye me-1"></i>Ver OS
                                </a>
                                @if($notif->os->status === 'aguardando_finalizacao' && $notif->os->mecanico_id === auth()->user()->mecanico?->id)
                                    <form method="POST" action="{{ route('os.fechar', $notif->os_id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-primary" onclick="return confirm('Finalizar esta OS?')">
                                            <i class="bi bi-flag-fill me-1"></i>Finalizar OS
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
	                    <p class="notification-empty">Nenhum aviso da oficina no momento.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Avisos de estoque</span>
                @if($pecas_criticas->count() > 0)
                    <span class="badge bg-danger">{{ $pecas_criticas->count() }}</span>
                @endif
            </div>
            <div class="card-body">
                @forelse($pecas_criticas as $peca)
                    <div class="d-flex align-items-center justify-content-between gap-3 border-bottom py-3">
                        <div>
                            <div class="fw-semibold">{{ $peca->nome }}</div>
	                            <div class="small" style="color: var(--text2);">{{ $peca->codigo ?: 'Sem codigo' }} - minimo {{ $peca->estoque_minimo }} {{ $peca->unidade }}</div>
                        </div>
                        <span class="badge bg-danger">{{ $peca->estoque }} {{ $peca->unidade }}</span>
                    </div>
                @empty
	                    <p class="mb-0" style="color: var(--text2);">Nenhuma peca com estoque critico.</p>
                @endforelse
            </div>
	        </div>
	    </div>

	    <div class="col-12">
	        <div class="card">
	            <div class="card-header">
	                <i class="bi bi-clock-history me-2"></i>Histórico de notificações
	            </div>
	            <div class="card-body p-0">
	                @if($notificacoes_respondidas->isEmpty())
		                    <p class="text-center py-4" style="color: var(--text2);">Nenhum histórico para exibir.</p>
	                @else
	                    <div class="table-responsive">
	                        <table class="table table-sm mb-0">
	                            <thead class="table-light">
	                                <tr>
	                                    <th>OS</th>
	                                    <th>Cliente</th>
	                                    <th>Aviso</th>
	                                    <th>Data</th>
	                                    <th></th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                @foreach($notificacoes_respondidas as $notif)
	                                    <tr>
	                                        <td><span class="font-mono small">{{ $notif->os?->numero ?? '-' }}</span></td>
	                                        <td>{{ $notif->os?->cliente?->nome ?? '-' }}</td>
	                                        <td>
	                                            <span class="badge bg-success me-2">Concluído</span>
		                                            <small style="color: var(--text2);">{{ $notif->mensagem ?: 'Aviso concluído.' }}</small>
	                                        </td>
	                                        <td><small>{{ $notif->updated_at->format('d/m H:i') }}</small></td>
	                                        <td>
	                                            @if($notif->os)
	                                                <a href="{{ route('os.show', $notif->os) }}" class="btn btn-sm btn-outline-secondary">
	                                                    <i class="bi bi-eye"></i>
	                                                </a>
	                                            @endif
	                                        </td>
	                                    </tr>
	                                @endforeach
	                            </tbody>
	                        </table>
	                    </div>
	                @endif
	            </div>
	        </div>
	    </div>
	</div>
	@elseif(auth()->user()->isCliente())
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <span><i class="bi bi-bell-fill me-2 text-warning"></i>Minhas notificacoes</span>
                @if($notificacoes_pendentes->count() > 0)
                    <span class="badge bg-danger">{{ $notificacoes_pendentes->count() }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                @if($notificacoes_pendentes->isEmpty())
                    <p class="notification-empty">Nenhuma notificacao pendente.</p>
                @else
                    <div class="notification-list">
                        @foreach($notificacoes_pendentes as $notif)
                            @php
                                $isReady = $notif->os->status === 'finalizada';
                                $cardClass = $isReady ? 'is-done' : ($notif->os->status === 'aguardando_aprovacao' ? 'is-warning' : '');
                            @endphp
                            <div class="notification-card {{ $cardClass }}">
                                <div class="notification-main">
                                    <div class="notification-icon">
                                        <i class="bi {{ $isReady ? 'bi-car-front-fill' : 'bi-bell' }}"></i>
                                    </div>
                                    <div>
                                        <span class="notification-kicker">OS</span>
                                        <strong class="notification-title">{{ $notif->os->numero }}</strong>
                                        <div class="notification-meta">
                                            <span class="badge badge-{{ $notif->os->status }}">{{ $notif->os->statusLabel() }}</span>
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $notif->created_at->format('d/m H:i') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="notification-kicker">Veiculo</span>
                                        <strong class="notification-value">{{ $notif->os->veiculo->marca }} {{ $notif->os->veiculo->modelo }}</strong>
                                    </div>
                                    <div class="notification-message">
                                        {{ $notif->mensagem ?: 'Nova atualizacao de OS.' }}
                                    </div>
                                </div>
                                <div class="notification-actions">
                                    <a href="{{ route('os.show', $notif->os_id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>Ver OS
                                    </a>
                                    @if($isReady)
                                        <form method="POST" action="{{ route('notificacoes.confirmar', $notif) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-success">
                                                <i class="bi bi-check2-circle me-1"></i>OK
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Historico
            </div>
            <div class="card-body p-0">
                @if($notificacoes_respondidas->isEmpty())
                    <p class="notification-empty">Nenhum historico para exibir.</p>
                @else
                    <div class="notification-list">
                        @foreach($notificacoes_respondidas as $notif)
                            <div class="notification-card is-done">
                                <div class="notification-main">
                                    <div class="notification-icon">
                                        <i class="bi bi-check2-circle"></i>
                                    </div>
                                    <div>
                                        <span class="notification-kicker">OS</span>
                                        <strong class="notification-title">{{ $notif->os->numero }}</strong>
                                        <div class="notification-meta">
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $notif->updated_at->format('d/m H:i') }}</span>
                                        </div>
                                    </div>
                                    <div class="notification-message">
                                        {{ $notif->mensagem ?: 'Notificacao confirmada.' }}
                                    </div>
                                </div>
                                <div class="notification-actions">
                                    <a href="{{ route('os.show', $notif->os_id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>Ver OS
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi bi-bell-fill me-2 text-warning"></i>
                    Solicitacoes Aguardando Resposta
                    @if($notificacoes_pendentes->count() > 0)
                        <span class="badge bg-danger">{{ $notificacoes_pendentes->count() }}</span>
                    @endif
                </span>
            </div>
            <div class="card-body p-0">
                @if($notificacoes_pendentes->isEmpty())
                    <p class="notification-empty">Nenhuma solicitacao pendente no momento.</p>
                @else
                    <div class="notification-list">
                        @foreach($notificacoes_pendentes as $notif)
                            @php
                                $isPayment = $notif->os->status === 'aguardando_finalizacao';
                                $isWaiting = $notif->os->status === 'aguardando_aprovacao';
                                $cardClass = $isPayment ? 'is-payment' : ($isWaiting ? 'is-warning' : '');
                            @endphp
                            <div class="notification-card {{ $cardClass }}">
                                <div class="notification-main">
                                    <div class="notification-icon">
                                        @if($notif->tipo === 'solicitacao_os')
                                            <i class="bi bi-clipboard-plus"></i>
                                        @elseif($isPayment)
                                            <i class="bi bi-credit-card-2-front"></i>
                                        @elseif($notif->os->status === 'orcamento_enviado_atendente')
                                            <i class="bi bi-send"></i>
                                        @else
                                            <i class="bi bi-bell"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="notification-kicker">OS</span>
                                        <strong class="notification-title">{{ $notif->os->numero }}</strong>
                                        <div class="notification-meta">
                                            <span class="badge badge-{{ $notif->os->status }}">{{ $notif->os->statusLabel() }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="notification-kicker">Cliente</span>
                                        <strong class="notification-value">{{ $notif->os->cliente->nome }}</strong>
                                    </div>
                                    <div>
                                        <span class="notification-kicker">Veiculo</span>
                                        <strong class="notification-value">{{ $notif->os->veiculo->marca }} {{ $notif->os->veiculo->modelo }}</strong>
                                        <div class="notification-meta">
                                            <span class="badge bg-light text-dark font-mono">{{ $notif->os->veiculo->placa }}</span>
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $notif->created_at->format('d/m H:i') }}</span>
                                        </div>
                                    </div>
                                    <div class="notification-message">
                                        {{ $notif->mensagem ?: 'Nova atualizacao de OS.' }}
                                    </div>
                                </div>
                                <div class="notification-actions">
                                    <a href="{{ route('os.show', $notif->os) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>Ver OS
                                    </a>
                                    @if($notif->tipo === 'solicitacao_os')
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#aceitarModal{{ $notif->id }}">
                                            <i class="bi bi-check-circle me-1"></i>Aceitar
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#recusarModal{{ $notif->id }}">
                                            <i class="bi bi-x-circle me-1"></i>Recusar
                                        </button>
                                    @elseif($notif->os->status === 'orcamento_enviado_atendente')
                                        <form method="POST" action="{{ route('os.orcamento.cliente', $notif->os_id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-warning btn-sm">
                                                <i class="bi bi-send me-1"></i>Enviar
                                            </button>
                                        </form>
                                    @else
                                        @if($notif->os->status === 'aguardando_finalizacao')
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-2">
                                                Cliente confirmou
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">
                                                Aguardando cliente
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Historico (Ultimas Respostas)
            </div>
            <div class="card-body p-0">
                @if($notificacoes_respondidas->isEmpty())
                    <p class="notification-empty">Nenhum historico para exibir.</p>
                @else
                    <div class="notification-list">
                        @foreach($notificacoes_respondidas as $notif)
                            <div class="notification-card {{ $notif->status === 'aceita' ? 'is-done' : '' }}">
                                <div class="notification-main">
                                    <div class="notification-icon">
                                        @if($notif->status === 'aceita')
                                            <i class="bi bi-check2-circle"></i>
                                        @else
                                            <i class="bi bi-x-circle"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="notification-kicker">OS</span>
                                        <strong class="notification-title">{{ $notif->os->numero }}</strong>
                                        <div class="notification-meta">
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $notif->updated_at->format('d/m H:i') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="notification-kicker">Cliente</span>
                                        <strong class="notification-value">{{ $notif->os->cliente->nome }}</strong>
                                    </div>
                                    <div>
                                        <span class="notification-kicker">Resposta</span>
                                        @if($notif->status === 'aceita')
                                            <span class="badge bg-success px-3 py-2">Aceita</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2">Recusada</span>
                                        @endif
                                        @if($notif->mensagem)
                                            <div class="notification-message mt-2">{{ $notif->mensagem }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="notification-actions">
                                    <a href="{{ route('os.show', $notif->os) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>Ver OS
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@foreach($notificacoes_pendentes as $notif)
    <div class="modal fade" id="aceitarModal{{ $notif->id }}" tabindex="-1" aria-labelledby="aceitarModalLabel{{ $notif->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="aceitarModalLabel{{ $notif->id }}">Aceitar OS #{{ $notif->os->numero }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form action="{{ route('notificacoes.aceitar', $notif) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="mecanico{{ $notif->id }}" class="form-label">Mecanico responsavel *</label>
                        <select class="form-select" id="mecanico{{ $notif->id }}" name="mecanico_id" required>
                            <option value="">Selecione...</option>
                            @forelse($mecanicos as $mecanico)
                                <option value="{{ $mecanico->id }}">{{ $mecanico->nome }}</option>
                            @empty
                                <option value="" disabled>Nenhum mecanico cadastrado</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" @disabled($mecanicos->isEmpty())>Aceitar e encaminhar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="recusarModal{{ $notif->id }}" tabindex="-1" aria-labelledby="recusarModalLabel{{ $notif->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="recusarModalLabel{{ $notif->id }}">Recusar OS #{{ $notif->os->numero }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form action="{{ route('notificacoes.recusar', $notif) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="motivo{{ $notif->id }}" class="form-label">Motivo da recusa *</label>
                            <select class="form-select" id="motivo{{ $notif->id }}" name="motivo" required>
                                <option value="">Selecione...</option>
                                <option value="Falta de material especifico">Falta de material especifico</option>
                                <option value="Nao trabalhamos com esse servico">Nao trabalhamos com esse servico</option>
                                <option value="Oficina sem agenda no momento">Oficina sem agenda no momento</option>
                                <option value="Veiculo fora do perfil atendido">Veiculo fora do perfil atendido</option>
                                <option value="Outro motivo">Outro motivo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="detalhes{{ $notif->id }}" class="form-label">Explique para o cliente *</label>
                            <textarea class="form-control" id="detalhes{{ $notif->id }}" name="detalhes_recusa" rows="3" required placeholder="Escreva com suas palavras o motivo da recusa."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Recusar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endif
@endsection
