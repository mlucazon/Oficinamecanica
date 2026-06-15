@extends('layouts.app')
@section('title','Ordens de Servico')
@section('breadcrumb','Ordens de Servico')

@php
    $clienteLogado = auth()->user()->cliente;
    $temVeiculos = !auth()->user()->isCliente() || ($clienteLogado && $clienteLogado->veiculos()->exists());
@endphp

@push('styles')
<style>
    .os-index-mobile {
        display: none;
        padding: 12px;
        gap: 12px;
    }

    .os-index-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        background: rgba(255,255,255,.02);
    }

    .os-index-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .os-index-head strong {
        display: block;
        color: var(--text);
        font-size: 14px;
    }

    .os-index-head span.badge {
        margin-top: 4px;
    }

    .os-index-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 12px;
    }

    .os-index-field {
        min-width: 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 9px;
        background: rgba(255,255,255,.018);
    }

    .os-index-field span {
        display: block;
        margin-bottom: 3px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .os-index-field strong {
        display: block;
        color: var(--text);
        font-size: 13px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    @media (max-width: 767.98px) {
        .os-index-table {
            display: none;
        }

        .os-index-mobile {
            display: grid;
        }

        .os-filter-actions,
        .os-filter-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 380px) {
        .os-index-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <span><i class="bi bi-clipboard2-check me-2"></i>Ordens de Servico</span>
        @if(auth()->user()->isAtendente() || auth()->user()->isGerente())
            <a href="{{ route('os.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-person-workspace me-1"></i>Atendimento presencial
            </a>
        @elseif(auth()->user()->isCliente())
            @if($temVeiculos)
                <a href="{{ route('os.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Abrir OS
                </a>
            @else
                <a href="{{ route('conta.veiculos') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-car-front me-1"></i>Cadastrar veiculo primeiro
                </a>
            @endif
        @endif
    </div>

    @if(auth()->user()->isCliente() && !$temVeiculos)
        <div class="alert alert-warning m-3 mb-0">
            <i class="bi bi-info-circle me-2"></i>Para abrir uma OS, primeiro cadastre um veiculo.
        </div>
    @endif

    <div class="card-body border-bottom">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="busca" class="form-control" placeholder="Numero ou placa..." value="{{ request('busca') }}">
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Todos os status</option>
                    @foreach(['aberta','em_diagnostico','aguardando_aprovacao','aprovada','em_execucao','aguardando_finalizacao','aguardando_pecas','finalizada','cancelada'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto os-filter-actions d-flex gap-2">
                <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('os.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="os-index-table table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Numero</th>
                        <th>Cliente</th>
                        <th>Veiculo</th>
                        <th>Mecanico</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordens as $os)
                    <tr>
                        <td><span class="font-mono small fw-500">{{ $os->numero }}</span></td>
                        <td>{{ $os->cliente->nome }}</td>
                        <td>
                            {{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}
                            <br><span class="badge bg-light text-dark font-mono" style="font-size:.7rem">{{ $os->veiculo->placa }}</span>
                        </td>
                        <td>
                            {{ $os->mecanico?->nome ?? '-' }}
                            <br><small style="color: var(--text2);">{{ $os->mecanico?->user?->email ?? '-' }}</small>
                        </td>
                        <td><span class="badge badge-{{ $os->status }}">{{ $os->statusLabel() }}</span></td>
                        <td class="small" style="color: var(--text2);">{{ $os->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('os.show',$os->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4" style="color: var(--text2);">Nenhuma ordem de servico encontrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ordens->count())
            <div class="os-index-mobile">
                @foreach($ordens as $os)
                    <div class="os-index-card">
                        <div class="os-index-head">
                            <div>
                                <strong>{{ $os->numero }}</strong>
                                <span class="badge badge-{{ $os->status }}">{{ $os->statusLabel() }}</span>
                            </div>
                            <span class="small text-muted">{{ $os->created_at->format('d/m/Y') }}</span>
                        </div>

                        <div class="os-index-grid">
                            <div class="os-index-field">
                                <span>Cliente</span>
                                <strong>{{ $os->cliente->nome }}</strong>
                            </div>
                            <div class="os-index-field">
                                <span>Veiculo</span>
                                <strong>{{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}</strong>
                            </div>
                            <div class="os-index-field">
                                <span>Placa</span>
                                <strong class="font-mono">{{ $os->veiculo->placa }}</strong>
                            </div>
                            <div class="os-index-field">
                                <span>Mecanico</span>
                                <strong>{{ $os->mecanico?->nome ?? 'Sem mecanico' }}</strong>
                            </div>
                        </div>

                        <a href="{{ route('os.show',$os->id) }}" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-eye me-1"></i>Ver OS
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="card-footer">{{ $ordens->links() }}</div>
</div>
@endsection
