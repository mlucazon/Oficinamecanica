@extends('layouts.app')
@section('title', 'Clientes Logados')
@section('breadcrumb', 'Clientes Logados')

@push('styles')
<style>
    .clientes-mobile-list {
        display: none;
    }

    .cliente-mobile-card {
        padding: 14px;
        border-bottom: 1px solid var(--border);
        background: rgba(255,255,255,.015);
    }

    .cliente-mobile-head {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        margin-bottom: 12px;
    }

    .cliente-mobile-name {
        min-width: 0;
        flex: 1 1 auto;
    }

    .cliente-mobile-name strong {
        display: block;
        color: var(--text);
        font-size: 14.5px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .cliente-mobile-status {
        display: block;
        margin-top: 2px;
        color: var(--text3);
        font-size: 12px;
    }

    .cliente-mobile-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        min-width: 0;
    }

    .cliente-mobile-field {
        min-width: 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 9px 10px;
        background: rgba(255,255,255,.025);
    }

    .cliente-mobile-field.full {
        grid-column: 1 / -1;
    }

    .cliente-mobile-field span {
        display: block;
        margin-bottom: 3px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .cliente-mobile-field strong,
    .cliente-mobile-field a {
        display: block;
        min-width: 0;
        color: var(--text);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.25;
        overflow-wrap: anywhere;
        text-decoration: none;
    }

    .cliente-mobile-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 12px;
    }

    @media (max-width: 700px) {
        .clientes-desktop-table {
            display: none !important;
        }

        .clientes-mobile-list {
            display: block;
        }

        .clientes-mobile-list .client-avatar {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
        }
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-people me-2"></i>Clientes Logados no Sistema</span>
    </div>
    <div class="card-body p-0">
        @if($clientes->count() > 0)
        <div class="table-responsive clientes-desktop-table">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th>E-mail (Login)</th>
                        <th>Veículos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $c)
                    <tr>
                        <td class="text-muted small">{{ $c->id }}</td>
                        <td>
                            <div class="client-name-stack">
                                <span class="client-avatar" title="{{ $c->user?->isOnline() ? 'Online' : 'Offline' }}">
                                    @if($c->user?->profilePhotoUrl())
                                        <img src="{{ $c->user->profilePhotoUrl() }}" alt="{{ $c->nome }}">
                                    @else
                                        {{ strtoupper(substr($c->nome, 0, 1)) }}{{ strtoupper(substr(explode(' ', $c->nome)[1] ?? 'X', 0, 1)) }}
                                    @endif
                                    <span class="status-dot {{ $c->user?->isOnline() ? 'online' : 'offline' }}"></span>
                                </span>
                                <span>
                                    <strong>{{ $c->nome }}</strong>
                                    <br>
                                    <span class="small text-muted">{{ $c->user?->isOnline() ? 'Online agora' : 'Offline' }}</span>
                                </span>
                            </div>
                        </td>
                        <td class="font-mono small">{{ $c->cpf }}</td>
                        <td>{{ $c->telefone }}</td>
                        <td>{{ $c->cidade }} {{ $c->estado ? "/ {$c->estado}" : '' }}</td>
                        <td class="small"><span class="badge bg-info text-dark">{{ $c->user?->email }}</span></td>
                        <td><span class="badge bg-secondary">{{ $c->veiculos->count() }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('clientes.show', $c->id) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="clientes-mobile-list">
            @foreach($clientes as $c)
                <div class="cliente-mobile-card">
                    <div class="cliente-mobile-head">
                        <span class="client-avatar" title="{{ $c->user?->isOnline() ? 'Online' : 'Offline' }}">
                            @if($c->user?->profilePhotoUrl())
                                <img src="{{ $c->user->profilePhotoUrl() }}" alt="{{ $c->nome }}">
                            @else
                                {{ strtoupper(substr($c->nome, 0, 1)) }}{{ strtoupper(substr(explode(' ', $c->nome)[1] ?? 'X', 0, 1)) }}
                            @endif
                            <span class="status-dot {{ $c->user?->isOnline() ? 'online' : 'offline' }}"></span>
                        </span>

                        <div class="cliente-mobile-name">
                            <strong>{{ $c->nome }}</strong>
                            <span class="cliente-mobile-status">{{ $c->user?->isOnline() ? 'Online agora' : 'Offline' }}</span>
                        </div>
                    </div>

                    <div class="cliente-mobile-info">
                        <div class="cliente-mobile-field">
                            <span>CPF</span>
                            <strong class="font-mono">{{ $c->cpf }}</strong>
                        </div>
                        <div class="cliente-mobile-field">
                            <span>Telefone</span>
                            <strong>{{ $c->telefone }}</strong>
                        </div>
                        <div class="cliente-mobile-field">
                            <span>Cidade</span>
                            <strong>{{ $c->cidade ?: '-' }} {{ $c->estado ? "/ {$c->estado}" : '' }}</strong>
                        </div>
                        <div class="cliente-mobile-field">
                            <span>Veiculos</span>
                            <strong>{{ $c->veiculos->count() }}</strong>
                        </div>
                        <div class="cliente-mobile-field full">
                            <span>E-mail de login</span>
                            <strong>{{ $c->user?->email ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="cliente-mobile-actions">
                        <span class="text-muted small">#{{ $c->id }}</span>
                        <a href="{{ route('clientes.show', $c->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye me-1"></i>Visualizar
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info m-3">
            <i class="bi bi-info-circle me-2"></i>Nenhum cliente logado no momento.
        </div>
        @endif
    </div>
</div>
@endsection
