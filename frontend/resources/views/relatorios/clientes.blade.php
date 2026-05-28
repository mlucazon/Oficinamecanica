@extends('layouts.app')
@section('title','Clientes Recorrentes')
@section('breadcrumb','Relatorios / Clientes')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-people me-2"></i>Clientes - Recorrentes vs Novos</div>
    <div class="card-body">
        @php($maiorOs = max((int) $dados->max('ordens_count'), 1))
        @if($dados->count())
            <div class="report-chart">
                @foreach($dados->take(12) as $c)
                    <div class="report-chart-row">
                        <div>
                            <div class="report-chart-label">{{ $c->nome }}</div>
                            <div class="report-chart-muted">{{ $c->ordens_count > 1 ? 'Recorrente' : 'Novo' }}</div>
                        </div>
                        <div class="report-chart-track">
                            <span class="report-chart-bar" style="width: {{ max(4, ($c->ordens_count / $maiorOs) * 100) }}%"></span>
                        </div>
                        <div class="report-chart-value">{{ $c->ordens_count }} OS</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">Sem dados.</div>
        @endif
    </div>
    <div class="card-body p-0 border-top">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Cliente</th><th>Total de OS</th><th>Perfil</th></tr></thead>
            <tbody>
                @forelse($dados as $c)
                <tr>
                    <td>{{ $c->nome }}</td>
                    <td>{{ $c->ordens_count }}</td>
                    <td><span class="badge {{ $c->ordens_count > 1 ? 'bg-success' : 'bg-info text-dark' }}">
                        {{ $c->ordens_count > 1 ? 'Recorrente' : 'Novo' }}
                    </span></td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $dados->links() }}</div>
</div>
@endsection
