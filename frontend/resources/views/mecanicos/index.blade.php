@extends('layouts.app')
@section('title','Mecanicos')
@section('breadcrumb','Mecanicos')

@push('styles')
<style>
    .mecanicos-mobile-list {
        display: none;
    }

    .mecanico-mobile-card {
        padding: 14px;
        border-bottom: 1px solid var(--border);
        background: rgba(255,255,255,.015);
    }

    .mecanico-mobile-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .mecanico-mobile-title {
        min-width: 0;
        flex: 1 1 auto;
    }

    .mecanico-mobile-title strong {
        display: block;
        color: var(--text);
        font-size: 14.5px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .mecanico-mobile-title span {
        display: block;
        margin-top: 2px;
        color: var(--text3);
        font-size: 12px;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .mecanico-mobile-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .mecanico-mobile-field {
        min-width: 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 9px 10px;
        background: rgba(255,255,255,.025);
    }

    .mecanico-mobile-field.full {
        grid-column: 1 / -1;
    }

    .mecanico-mobile-field span {
        display: block;
        margin-bottom: 3px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .mecanico-mobile-field strong {
        display: block;
        color: var(--text);
        font-size: 13px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .mecanico-mobile-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 12px;
    }

    @media (max-width: 700px) {
        .mecanicos-desktop-table {
            display: none !important;
        }

        .mecanicos-mobile-list {
            display: block;
        }

        .mecanicos-mobile-list .btn {
            min-height: 36px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between gap-2 flex-wrap">
        <span><i class="bi bi-person-gear me-2"></i>Mecanicos</span>
        <a href="{{ route('mecanicos.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Novo Mecanico
        </a>
    </div>

    <div class="card-body p-0">
        @if($mecanicos->count() > 0)
            <div class="table-responsive mecanicos-desktop-table">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Especialidade</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mecanicos as $m)
                            <tr>
                                <td>{{ $m->nome }}</td>
                                <td>{{ $m->user->email ?? '-' }}</td>
                                <td class="font-mono small">{{ $m->cpf ?? '-' }}</td>
                                <td>{{ $m->especialidade ?? '-' }}</td>
                                <td>{{ $m->telefone ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $m->ativo ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $m->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('mecanicos.toggle',$m) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-secondary">{{ $m->ativo ? 'Desativar' : 'Ativar' }}</button>
                                    </form>
                                    <a href="{{ route('mecanicos.edit',$m) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('mecanicos.destroy',$m) }}" class="d-inline" onsubmit="return confirm('Excluir?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mecanicos-mobile-list">
                @foreach($mecanicos as $m)
                    <div class="mecanico-mobile-card">
                        <div class="mecanico-mobile-head">
                            <div class="mecanico-mobile-title">
                                <strong>{{ $m->nome }}</strong>
                                <span>{{ $m->user->email ?? 'Sem e-mail vinculado' }}</span>
                            </div>
                            <span class="badge {{ $m->ativo ? 'bg-success' : 'bg-secondary' }}">
                                {{ $m->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>

                        <div class="mecanico-mobile-info">
                            <div class="mecanico-mobile-field">
                                <span>CPF</span>
                                <strong class="font-mono">{{ $m->cpf ?? '-' }}</strong>
                            </div>
                            <div class="mecanico-mobile-field">
                                <span>Telefone</span>
                                <strong>{{ $m->telefone ?? '-' }}</strong>
                            </div>
                            <div class="mecanico-mobile-field full">
                                <span>Especialidade</span>
                                <strong>{{ $m->especialidade ?? '-' }}</strong>
                            </div>
                        </div>

                        <div class="mecanico-mobile-actions">
                            <form method="POST" action="{{ route('mecanicos.toggle',$m) }}" class="m-0">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-secondary">{{ $m->ativo ? 'Desativar' : 'Ativar' }}</button>
                            </form>
                            <a href="{{ route('mecanicos.edit',$m) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                            <form method="POST" action="{{ route('mecanicos.destroy',$m) }}" class="m-0" onsubmit="return confirm('Excluir?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">Nenhum mecanico cadastrado.</div>
        @endif
    </div>

    <div class="card-footer">{{ $mecanicos->links() }}</div>
</div>
@endsection
