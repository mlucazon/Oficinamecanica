@extends('layouts.app')
@section('title', 'Meus Veiculos')
@section('breadcrumb', 'Meus Veiculos')

@push('styles')
<style>
    .my-vehicles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 12px;
        padding: 12px;
    }

    .my-vehicle-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 14px;
        background: rgba(255,255,255,.02);
    }

    .my-vehicle-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 12px;
    }

    .my-vehicle-title {
        min-width: 0;
    }

    .my-vehicle-title strong {
        display: block;
        color: var(--text);
        font-size: 15px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .my-vehicle-title span {
        display: inline-block;
        margin-top: 4px;
    }

    .my-vehicle-info {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 12px;
    }

    .my-vehicle-field {
        min-width: 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 9px;
        background: rgba(255,255,255,.018);
    }

    .my-vehicle-field span {
        display: block;
        margin-bottom: 3px;
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .my-vehicle-field strong {
        display: block;
        color: var(--text);
        font-size: 13px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .my-vehicle-actions {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
    }

    .my-vehicle-actions .btn,
    .my-vehicle-actions form {
        width: 100%;
    }

    .my-vehicle-actions .btn {
        white-space: nowrap;
    }

    .my-vehicle-os {
        margin-bottom: 8px;
    }

    @media (max-width: 576px) {
        .vehicles-card-header {
            align-items: stretch !important;
        }

        .vehicles-card-header > div,
        .vehicles-card-header .btn {
            width: 100%;
        }

        .my-vehicles-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 380px) {
        .my-vehicle-info,
        .my-vehicle-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header vehicles-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-car-front me-2"></i>Meus Veiculos
                </div>
                <a href="{{ route('veiculos.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> Cadastrar meu veiculo
                </a>
            </div>
            <div class="card-body p-0">
                @if($veiculos->count() > 0)
                    <div class="my-vehicles-grid">
                        @foreach($veiculos as $v)
                            <div class="my-vehicle-card">
                                <div class="my-vehicle-head">
                                    <div class="my-vehicle-title">
                                        <strong>{{ $v->marca }} {{ $v->modelo }}</strong>
                                        <span class="font-mono badge bg-light text-dark">{{ $v->placa }}</span>
                                    </div>
                                    <span class="badge bg-secondary">{{ $v->ano }}</span>
                                </div>

                                <div class="my-vehicle-info">
                                    <div class="my-vehicle-field">
                                        <span>Marca</span>
                                        <strong>{{ $v->marca }}</strong>
                                    </div>
                                    <div class="my-vehicle-field">
                                        <span>Modelo</span>
                                        <strong>{{ $v->modelo }}</strong>
                                    </div>
                                    <div class="my-vehicle-field">
                                        <span>Cor</span>
                                        <strong>{{ $v->cor ?: 'Nao informada' }}</strong>
                                    </div>
                                    <div class="my-vehicle-field">
                                        <span>KM atual</span>
                                        <strong>{{ $v->km_atual ? number_format($v->km_atual, 0, ',', '.') : 'Nao informado' }}</strong>
                                    </div>
                                </div>

                                @if($v->ordens->first())
                                    <div class="my-vehicle-os">
                                        <a href="{{ route('os.show', $v->ordens->first()->id) }}" class="btn btn-sm btn-primary w-100">
                                            <i class="bi bi-arrow-right-circle me-1"></i>Ir para OS
                                        </a>
                                    </div>
                                @endif

                                <div class="my-vehicle-actions">
                                    <a href="{{ route('veiculos.show', $v->id) }}?cliente_id={{ auth()->user()->cliente?->id }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </a>
                                    <a href="{{ route('veiculos.edit', $v->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil me-1"></i>Editar
                                    </a>
                                    <form method="POST" action="{{ route('veiculos.destroy', $v->id) }}" onsubmit="return confirm('Tem certeza que deseja remover este veiculo?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="redirect_conta_veiculos" value="1">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i>Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3">
                        <div class="info-block">
                            <span class="info-block-icon warning">
                                <i class="bi bi-car-front"></i>
                            </span>
                            <div>
                                <div class="info-block-title">Voce ainda nao possui veiculos cadastrados.</div>
                                <div class="info-block-text">Cadastre seu primeiro veiculo para conseguir abrir uma OS.</div>
                            </div>
                            <div class="info-block-actions">
                                <a href="{{ route('veiculos.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>Cadastrar veiculo
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
