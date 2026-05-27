@extends('layouts.app')
@section('title','Veiculos')
@section('breadcrumb','Veiculos')

@push('styles')
<style>
    .veiculos-mobile-list {
        display: none;
        padding: 12px;
        gap: 12px;
    }

    .veiculo-mobile-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        background: rgba(255,255,255,.02);
    }

    .veiculo-mobile-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .veiculo-mobile-title {
        min-width: 0;
    }

    .veiculo-mobile-title strong {
        display: block;
        color: var(--text);
        font-size: 14px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .veiculo-mobile-title span {
        display: inline-block;
        margin-top: 4px;
    }

    .veiculo-mobile-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 12px;
    }

    .veiculo-mobile-field {
        min-width: 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 9px;
        background: rgba(255,255,255,.018);
    }

    .veiculo-mobile-field span {
        display: block;
        margin-bottom: 3px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .veiculo-mobile-field strong {
        display: block;
        color: var(--text);
        font-size: 13px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .veiculo-mobile-actions {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
    }

    .veiculo-mobile-actions .btn,
    .veiculo-mobile-actions form {
        width: 100%;
    }

    .veiculo-mobile-actions .btn {
        white-space: nowrap;
    }

    @media (max-width: 767.98px) {
        .veiculos-desktop-table {
            display: none;
        }

        .veiculos-mobile-list {
            display: grid;
        }
    }

    @media (max-width: 380px) {
        .veiculo-mobile-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-car-front me-2"></i>Veiculos</span>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="busca" class="form-control" placeholder="Placa, modelo ou cliente..." value="{{ request('busca') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('veiculos.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="veiculos-desktop-table">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Placa</th><th>Veiculo</th><th>Ano</th><th>Cor</th><th>Cliente</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($veiculos as $v)
                    <tr>
                        <td class="font-mono fw-500">{{ $v->placa }}</td>
                        <td>{{ $v->marca }} {{ $v->modelo }}</td>
                        <td>{{ $v->ano }}</td>
                        <td>{{ $v->cor ?? '-' }}</td>
                        <td>{{ $v->cliente?->nome ?? '-' }}</td>
                        <td class="text-end">
                            @if($v->ordens->first())
                                <a href="{{ route('os.show', $v->ordens->first()->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-arrow-right-circle me-1"></i>Ir para OS
                                </a>
                            @endif
                            <a href="{{ route('veiculos.show',$v->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('veiculos.edit',$v->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('veiculos.destroy',$v->id) }}" class="d-inline" onsubmit="return confirm('Excluir veiculo?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Nenhum veiculo encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($veiculos->count())
            <div class="veiculos-mobile-list">
                @foreach($veiculos as $v)
                    <div class="veiculo-mobile-card">
                        <div class="veiculo-mobile-head">
                            <div class="veiculo-mobile-title">
                                <strong>{{ $v->marca }} {{ $v->modelo }}</strong>
                                <span class="font-mono badge bg-light text-dark">{{ $v->placa }}</span>
                            </div>
                            <span class="badge bg-secondary">{{ $v->ano }}</span>
                        </div>

                        <div class="veiculo-mobile-info">
                            <div class="veiculo-mobile-field">
                                <span>Cor</span>
                                <strong>{{ $v->cor ?: 'Nao informada' }}</strong>
                            </div>
                            <div class="veiculo-mobile-field">
                                <span>Cliente</span>
                                <strong>{{ $v->cliente?->nome ?? 'Sem cliente' }}</strong>
                            </div>
                        </div>

                        @if($v->ordens->first())
                            <div class="mb-2">
                                <a href="{{ route('os.show', $v->ordens->first()->id) }}" class="btn btn-sm btn-primary w-100">
                                    <i class="bi bi-arrow-right-circle me-1"></i>Ir para OS
                                </a>
                            </div>
                        @endif

                        <div class="veiculo-mobile-actions">
                            <a href="{{ route('veiculos.show',$v->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                            <a href="{{ route('veiculos.edit',$v->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                            <form method="POST" action="{{ route('veiculos.destroy',$v->id) }}" onsubmit="return confirm('Excluir veiculo?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                    <i class="bi bi-trash me-1"></i>Remover
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="d-md-none text-center text-muted py-4">Nenhum veiculo encontrado.</div>
        @endif
    </div>
    <div class="card-footer">{{ $veiculos->links() }}</div>
</div>
@endsection
