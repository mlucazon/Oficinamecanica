@extends('layouts.app')
@section('title', 'Meus Veículos')
@section('breadcrumb', 'Meus Veículos')

@push('styles')
<style>
    .my-vehicles-table {
        display: block;
    }

    .my-vehicles-mobile {
        display: none;
    }

    .vehicle-mobile-card {
        border: 1px solid var(--border2);
        border-radius: 10px;
        padding: 1rem;
        background: var(--surface2);
    }

    .vehicle-mobile-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        margin-bottom: .85rem;
    }

    .vehicle-mobile-title strong,
    .vehicle-mobile-model {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .vehicle-mobile-model {
        color: var(--text2);
        font-size: 14px;
        margin-top: .1rem;
    }

    .vehicle-mobile-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .75rem;
    }

    .vehicle-mobile-field {
        min-width: 0;
    }

    .vehicle-mobile-label {
        color: var(--text3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: .18rem;
    }

    .vehicle-mobile-value {
        color: var(--text);
        font-size: 14px;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .vehicle-mobile-actions {
        display: grid;
        grid-template-columns: 1fr 44px;
        gap: .5rem;
        margin-top: .9rem;
    }

    .vehicle-mobile-actions .btn,
    .vehicle-mobile-title .btn {
        min-height: 42px;
    }

    @media (max-width: 576px) {
        .vehicles-card-header {
            align-items: stretch !important;
        }

        .vehicles-card-header > div,
        .vehicles-card-header .btn {
            width: 100%;
        }

        .my-vehicles-table {
            display: none;
        }

        .my-vehicles-mobile {
            display: grid;
            gap: .75rem;
            padding: 1rem;
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
                    <i class="bi bi-car-front me-2"></i>Meus Veículos
                </div>
                <a href="{{ route('veiculos.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> Cadastrar meu veículo
                </a>
            </div>
            <div class="card-body p-0">
                @if($veiculos->count() > 0)
                    <div class="my-vehicles-table table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Placa</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Ano</th>
                                    <th>Cor</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($veiculos as $v)
                                    <tr>
                                        <td class="font-mono fw-500">{{ $v->placa }}</td>
                                        <td>{{ $v->marca }}</td>
                                        <td>{{ $v->modelo }}</td>
                                        <td>{{ $v->ano }}</td>
                                        <td>{{ $v->cor ?? '—' }}</td>
                                        <td class="text-end">
                                            @if($v->ordens->first())
                                                <a href="{{ route('os.show', $v->ordens->first()->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-arrow-right-circle me-1"></i>Ir para OS
                                                </a>
                                            @endif
                                            <a href="{{ route('veiculos.show', $v->id) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="my-vehicles-mobile">
                        @foreach($veiculos as $v)
                            <div class="vehicle-mobile-card">
                                <div class="vehicle-mobile-title">
                                    <div>
                                        <strong class="font-mono">{{ $v->placa }}</strong>
                                        <div class="vehicle-mobile-model">{{ $v->marca }} {{ $v->modelo }}</div>
                                    </div>
                                    @unless($v->ordens->first())
                                        <a href="{{ route('veiculos.show', $v->id) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endunless
                                </div>

                                <div class="vehicle-mobile-grid">
                                    <div class="vehicle-mobile-field">
                                        <div class="vehicle-mobile-label">Marca</div>
                                        <div class="vehicle-mobile-value">{{ $v->marca }}</div>
                                    </div>
                                    <div class="vehicle-mobile-field">
                                        <div class="vehicle-mobile-label">Modelo</div>
                                        <div class="vehicle-mobile-value">{{ $v->modelo }}</div>
                                    </div>
                                    <div class="vehicle-mobile-field">
                                        <div class="vehicle-mobile-label">Ano</div>
                                        <div class="vehicle-mobile-value">{{ $v->ano }}</div>
                                    </div>
                                    <div class="vehicle-mobile-field">
                                        <div class="vehicle-mobile-label">Cor</div>
                                        <div class="vehicle-mobile-value">{{ $v->cor ?? '—' }}</div>
                                    </div>
                                </div>

                                @if($v->ordens->first())
                                    <div class="vehicle-mobile-actions">
                                        <a href="{{ route('os.show', $v->ordens->first()->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-arrow-right-circle me-1"></i>Ir para OS
                                        </a>
                                        <a href="{{ route('veiculos.show', $v->id) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-danger m-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>Você ainda não possui veículos cadastrados.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
