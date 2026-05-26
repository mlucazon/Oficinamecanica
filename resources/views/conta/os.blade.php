@extends('layouts.app')
@section('title', 'Minhas Ordens de Serviço')
@section('breadcrumb', 'Minhas Ordens de Serviço')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
	            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
	                <span><i class="bi bi-clipboard2-check me-2"></i>Ordens de Serviço</span>
	                <a href="{{ route('os.create') }}" class="btn btn-sm btn-primary">
	                    <i class="bi bi-plus-lg me-1"></i>Nova OS
	                </a>
	            </div>
            <div class="card-body p-0">
                @if($ordens->count() > 0)
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Número</th>
                            <th>Veículo</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ordens as $os)
                        <tr>
                            <td class="font-mono small fw-500">{{ $os->numero }}</td>
                            <td>
                                {{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}
                                <span class="badge bg-light text-dark font-mono">{{ $os->veiculo->placa }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $os->statusCor() }}">{{ $os->statusLabel() }}</span>
                            </td>
	                            <td class="font-mono">
	                                @if(in_array($os->status, ['aguardando_aceitacao', 'solicitacao_aceita', 'em_diagnostico', 'orcamento_enviado_atendente']) || (float) $os->valor_total <= 0)
	                                    <span class="text-light">Aguardando orçamento</span>
	                                @else
	                                    R$ {{ number_format($os->valor_total, 2, ',', '.') }}
	                                @endif
	                            </td>
                            <td class="small text-muted">{{ $os->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
	                                <a href="{{ route('os.show', $os->id) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar OS">
	                                    <i class="bi bi-eye"></i>
	                                </a>
	                                @if($os->status === 'finalizada')
                                    @if(!$os->avaliacao)
                                        <a href="{{ route('avaliacoes.create', $os) }}" class="btn btn-sm btn-outline-warning" title="Avaliar OS">
                                            <i class="bi bi-star"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('avaliacoes.index') }}" class="btn btn-sm btn-outline-success" title="Ver avaliacao">
                                            <i class="bi bi-star-fill"></i>
                                        </a>
                                    @endif
	                                <form method="POST" action="{{ route('os.destroy', $os->id) }}" class="d-inline" 
	                                      onsubmit="return confirm('Tem certeza que deseja apagar esta OS finalizada do seu histórico? Esta ação não pode ser desfeita.')">
	                                    @csrf @method('DELETE')
	                                    <button class="btn btn-sm btn-outline-danger" title="Excluir OS">
	                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-info m-3">
                    <i class="bi bi-info-circle me-2"></i>Você ainda não possui ordens de serviço.
                </div>
                @endif
            </div>
            @if($ordens->hasPages())
            <div class="card-footer">
                {{ $ordens->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
