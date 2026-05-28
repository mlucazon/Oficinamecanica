@extends('layouts.app')
@section('title', 'Notificacoes')
@section('breadcrumb', 'Notificacoes')

@push('styles')
<style>
    .notifications-mobile-list {
        display: none;
        padding: 12px;
        gap: 12px;
    }

    .notification-mobile-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        background: rgba(255,255,255,.02);
    }

    .notification-mobile-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .notification-mobile-head strong {
        display: block;
        color: var(--text);
        font-size: 14px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .notification-mobile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 12px;
    }

    .notification-mobile-field {
        min-width: 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 9px;
        background: rgba(255,255,255,.018);
    }

    .notification-mobile-field span {
        display: block;
        margin-bottom: 3px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .notification-mobile-field strong,
    .notification-mobile-field small {
        display: block;
        color: var(--text);
        font-size: 13px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .notification-mobile-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    @media (max-width: 767.98px) {
        .notifications-desktop-table {
            display: none;
        }

        .notifications-mobile-list {
            display: grid;
        }

        .notification-mobile-actions .btn,
        .notification-mobile-actions form {
            flex: 1 1 130px;
        }

        .notification-mobile-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 380px) {
        .notification-mobile-grid {
            grid-template-columns: 1fr;
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
            <div class="card-body">
                @forelse($notificacoes_pendentes as $notif)
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap border-bottom py-3">
                        <div>
                            <div class="fw-semibold">{{ $notif->mensagem ?: 'Nova atualizacao de OS.' }}</div>
	                            <div class="small" style="color: var(--text2);">
	                                OS {{ $notif->os->numero }} - {{ $notif->os->cliente->nome }} - {{ $notif->created_at->format('d/m/Y H:i') }}
	                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
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
                @empty
	                    <p class="mb-0" style="color: var(--text2);">Nenhum aviso da oficina no momento.</p>
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
<div class="card">
    <div class="card-header">
        <i class="bi bi-bell-fill me-2 text-warning"></i>Minhas notificacoes
    </div>
    <div class="card-body">
        @forelse($notificacoes_pendentes as $notif)
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap border-bottom py-3">
                <div>
                    <div class="fw-semibold">{{ $notif->mensagem ?: 'Nova atualizacao de OS.' }}</div>
                    <div class="small text-muted">
                        OS {{ $notif->os->numero }} - {{ $notif->os->cliente->nome }} - {{ $notif->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
                <a href="{{ route('os.show', $notif->os_id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eye me-1"></i>Ver OS
                </a>
            </div>
        @empty
            <p class="text-muted mb-0">Nenhuma notificacao pendente.</p>
        @endforelse
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
                    <p class="text-center text-muted py-4">Nenhuma solicitacao pendente no momento.</p>
                @else
                    <div class="table-responsive notifications-desktop-table">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>OS</th>
                                    <th>Cliente</th>
                                    <th>Veiculo</th>
                                    <th>Aviso</th>
                                    <th>Data</th>
                                    <th>Acoes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notificacoes_pendentes as $notif)
                                    <tr>
                                        <td>
                                            <span class="font-mono small">{{ $notif->os->numero }}</span>
                                        </td>
                                        <td>{{ $notif->os->cliente->nome }}</td>
                                        <td>
                                            <small>{{ $notif->os->veiculo->marca }} {{ $notif->os->veiculo->modelo }}</small>
                                            <br>
                                            <span class="badge bg-light text-dark font-mono">{{ $notif->os->veiculo->placa }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $notif->mensagem ?: 'Nova atualizacao de OS.' }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $notif->created_at->format('d/m H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <a href="{{ route('os.show', $notif->os) }}" class="btn btn-outline-secondary" title="Ver detalhes">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($notif->tipo === 'solicitacao_os')
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#aceitarModal{{ $notif->id }}" title="Aceitar">
                                                        <i class="bi bi-check-circle"></i> Aceitar
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#recusarModal{{ $notif->id }}" title="Recusar">
                                                        <i class="bi bi-x-circle"></i> Recusar
                                                    </button>
                                                @elseif($notif->os->status === 'orcamento_enviado_atendente')
                                                    <form method="POST" action="{{ route('os.orcamento.cliente', $notif->os_id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="btn btn-warning btn-sm">
                                                            <i class="bi bi-send me-1"></i>Enviar ao cliente
                                                        </button>
                                                    </form>
                                                @else
                                                    @if($notif->os->status === 'aguardando_finalizacao')
                                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-2">
                                                            Cliente confirmou
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">
                                                            Aguardando resposta do cliente
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="notifications-mobile-list">
                        @foreach($notificacoes_pendentes as $notif)
                            <div class="notification-mobile-card">
                                <div class="notification-mobile-head">
                                    <div>
                                        <strong>{{ $notif->os->numero }}</strong>
                                        <span class="badge bg-warning text-dark mt-1">Pendente</span>
                                    </div>
                                    <small style="color: var(--text2);">{{ $notif->created_at->format('d/m H:i') }}</small>
                                </div>

                                <div class="notification-mobile-grid">
                                    <div class="notification-mobile-field">
                                        <span>Cliente</span>
                                        <strong>{{ $notif->os->cliente->nome }}</strong>
                                    </div>
                                    <div class="notification-mobile-field">
                                        <span>Veiculo</span>
                                        <strong>{{ $notif->os->veiculo->marca }} {{ $notif->os->veiculo->modelo }}</strong>
                                    </div>
                                    <div class="notification-mobile-field">
                                        <span>Placa</span>
                                        <strong class="font-mono">{{ $notif->os->veiculo->placa }}</strong>
                                    </div>
                                    <div class="notification-mobile-field">
                                        <span>Tipo</span>
                                        <strong>{{ $notif->tipo === 'solicitacao_os' ? 'Solicitacao de OS' : 'Atualizacao' }}</strong>
                                    </div>
                                    <div class="notification-mobile-field">
                                        <span>Aviso</span>
                                        <small>{{ $notif->mensagem ?: 'Nova atualizacao de OS.' }}</small>
                                    </div>
                                </div>

                                <div class="notification-mobile-actions">
                                    <a href="{{ route('os.show', $notif->os) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>Ver
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
                    <p class="text-center text-muted py-4">Nenhum historico para exibir.</p>
                @else
                    <div class="table-responsive notifications-desktop-table">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>OS</th>
                                    <th>Cliente</th>
                                    <th>Resposta</th>
                                    <th>Data</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notificacoes_respondidas as $notif)
                                    <tr>
                                        <td>
                                            <span class="font-mono small">{{ $notif->os->numero }}</span>
                                        </td>
                                        <td>{{ $notif->os->cliente->nome }}</td>
                                        <td>
                                            @if($notif->status === 'aceita')
                                                <span class="badge bg-success">Aceita</span>
                                            @else
                                                <span class="badge bg-danger">Recusada</span>
                                                @if($notif->mensagem)
                                                    <br>
                                                    <small class="text-muted">{{ $notif->mensagem }}</small>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $notif->updated_at->format('d/m H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('os.show', $notif->os) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="notifications-mobile-list">
                        @foreach($notificacoes_respondidas as $notif)
                            <div class="notification-mobile-card">
                                <div class="notification-mobile-head">
                                    <div>
                                        <strong>{{ $notif->os->numero }}</strong>
                                        @if($notif->status === 'aceita')
                                            <span class="badge bg-success mt-1">Aceita</span>
                                        @else
                                            <span class="badge bg-danger mt-1">Recusada</span>
                                        @endif
                                    </div>
                                    <small style="color: var(--text2);">{{ $notif->updated_at->format('d/m H:i') }}</small>
                                </div>

                                <div class="notification-mobile-grid">
                                    <div class="notification-mobile-field">
                                        <span>Cliente</span>
                                        <strong>{{ $notif->os->cliente->nome }}</strong>
                                    </div>
                                    <div class="notification-mobile-field">
                                        <span>Resposta</span>
                                        <strong>{{ $notif->status === 'aceita' ? 'Aceita' : 'Recusada' }}</strong>
                                    </div>
                                    @if($notif->mensagem)
                                        <div class="notification-mobile-field" style="grid-column: 1 / -1;">
                                            <span>Mensagem</span>
                                            <small>{{ $notif->mensagem }}</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="notification-mobile-actions">
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
