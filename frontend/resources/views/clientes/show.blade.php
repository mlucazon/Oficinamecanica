@extends('layouts.app')
@section('title', $cliente->nome)
@section('breadcrumb', 'Clientes / ' . $cliente->nome)

@push('styles')
<style>
    .cliente-veiculos-list {
        display: grid;
        gap: 12px;
        padding: 12px;
    }

    .cliente-veiculo-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        background: rgba(255,255,255,.02);
    }

    .cliente-veiculo-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .cliente-veiculo-title {
        min-width: 0;
    }

    .cliente-veiculo-title strong {
        display: block;
        color: var(--text);
        font-size: 14px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .cliente-veiculo-title span {
        display: inline-block;
        margin-top: 3px;
    }

    .cliente-veiculo-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 12px;
    }

    .cliente-veiculo-field {
        min-width: 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 9px;
        background: rgba(255,255,255,.018);
    }

    .cliente-veiculo-field span {
        display: block;
        margin-bottom: 3px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .cliente-veiculo-field strong {
        display: block;
        color: var(--text);
        font-size: 13px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .cliente-veiculo-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    @media (max-width: 575.98px) {
        .cliente-veiculo-info {
            grid-template-columns: 1fr;
        }

        .cliente-veiculo-actions {
            justify-content: stretch;
        }

        .cliente-veiculo-actions .btn,
        .cliente-veiculo-actions form {
            flex: 1 1 120px;
        }

        .cliente-veiculo-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person me-2"></i>Dados</span>
                <a href="{{ route('clientes.edit',$cliente) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            </div>
            <div class="card-body">
                <dl class="row small mb-0">
                    <dt class="col-4 text-muted">Nome</dt><dd class="col-8 fw-500">{{ $cliente->nome }}</dd>
                    <dt class="col-4 text-muted">CPF</dt><dd class="col-8 font-mono">{{ $cliente->cpf }}</dd>
                    <dt class="col-4 text-muted">Telefone</dt><dd class="col-8">{{ $cliente->telefone }}</dd>
                    <dt class="col-4 text-muted">E-mail</dt><dd class="col-8">{{ $cliente->email ?? '-' }}</dd>
                    <dt class="col-4 text-muted">Endereco</dt><dd class="col-8">{{ $cliente->endereco ?? '-' }}</dd>
                    <dt class="col-4 text-muted">Cidade</dt><dd class="col-8">{{ $cliente->cidade ?? '-' }} {{ $cliente->estado ?? '' }}</dd>
                </dl>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-car-front me-2"></i>Veiculos</span>
                <a href="{{ route('veiculos.create', ['cliente_id'=>$cliente->id]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i></a>
            </div>
            <div class="card-body p-0">
                @if($cliente->veiculos->isNotEmpty())
                    <div class="cliente-veiculos-list">
                        @foreach($cliente->veiculos as $v)
                            <div class="cliente-veiculo-card">
                                <div class="cliente-veiculo-head">
                                    <div class="cliente-veiculo-title">
                                        <strong>{{ $v->marca }} {{ $v->modelo }}</strong>
                                        <span class="font-mono badge bg-light text-dark">{{ $v->placa }}</span>
                                    </div>
                                    <span class="badge bg-secondary">{{ $v->ano }}</span>
                                </div>

                                <div class="cliente-veiculo-info">
                                    <div class="cliente-veiculo-field">
                                        <span>Cor</span>
                                        <strong>{{ $v->cor ?: 'Nao informada' }}</strong>
                                    </div>
                                    <div class="cliente-veiculo-field">
                                        <span>KM atual</span>
                                        <strong>{{ $v->km_atual ? number_format($v->km_atual, 0, ',', '.') : 'Nao informado' }}</strong>
                                    </div>
                                </div>

                                <div class="cliente-veiculo-actions">
                                    <a href="{{ route('veiculos.show', $v->id) }}?cliente_id={{ $cliente->id }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </a>
                                    <a href="{{ route('veiculos.edit',$v) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil me-1"></i>Editar
                                    </a>
                                    <form method="POST" action="{{ route('veiculos.destroy',$v) }}" onsubmit="return confirm('Tem certeza que deseja remover este veiculo?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="redirect_cliente_id" value="{{ $cliente->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i>Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-3 small">Nenhum veiculo.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard2-check me-2"></i>Ultimas Ordens de Servico</span>
                @if(auth()->user()->isCliente() && auth()->id() === $cliente->user_id)
                    <a href="{{ route('os.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Nova OS
                    </a>
                @endif
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Numero</th><th>Veiculo</th><th>Status</th><th>Total</th><th>Data</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($cliente->ordens as $os)
                        <tr>
                            <td class="font-mono small">{{ $os->numero }}</td>
                            <td>{{ $os->veiculo->modelo }} <span class="badge bg-light text-dark font-mono">{{ $os->veiculo->placa }}</span></td>
                            <td><span class="badge badge-{{ $os->status }}">{{ $os->statusLabel() }}</span></td>
                            <td class="font-mono">R$ {{ number_format($os->valor_total,2,',','.') }}</td>
                            <td class="small text-muted">{{ $os->created_at->format('d/m/Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('os.show', $os->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                @if(!$os->aprovado_cliente || $os->status === 'cancelada')
                                <form method="POST" action="{{ route('os.destroy', $os->id) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta OS?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Nenhuma OS ainda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
