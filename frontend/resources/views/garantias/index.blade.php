@extends('layouts.app')
@section('title','Garantias')
@section('breadcrumb','Garantias')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-shield-check me-2"></i>Garantias (UC013)</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>OS</th><th>Cliente</th><th>Descrição</th><th>Válida até</th><th>Situação</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($garantias as $g)
                <tr>
                    <td class="font-mono small">{{ $g->ordemServico->numero }}</td>
                    <td>{{ $g->ordemServico->cliente->nome }}</td>
                    <td>{{ $g->descricao }}</td>
                    <td>{{ $g->data_fim->format('d/m/Y') }}</td>
                    <td>
                        @if($g->acionada)
                            <span class="badge bg-warning text-dark">Acionada</span>
                        @elseif($g->expirada())
                            <span class="badge bg-secondary">Expirada</span>
                        @else
                            <span class="badge bg-success">Ativa</span>
                        @endif
                    </td>
	                    <td>
	                        @if($g->ativa())
	                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modal-{{ $g->id }}">
	                            <i class="bi bi-lightning me-1"></i>Acionar
	                        </button>
	                        @endif
	                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhuma garantia registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
	    <div class="card-footer">{{ $garantias->links() }}</div>
	</div>

	@foreach($garantias as $g)
	    @if($g->ativa())
	        <div class="modal fade" id="modal-{{ $g->id }}" tabindex="-1" aria-labelledby="modal-title-{{ $g->id }}" aria-hidden="true">
	            <div class="modal-dialog modal-dialog-centered">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h5 class="modal-title" id="modal-title-{{ $g->id }}">Acionar Garantia</h5>
	                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
	                    </div>
	                    <form method="POST" action="{{ route('garantias.acionar',$g) }}">
	                        @csrf
	                        @method('PATCH')
	                        <div class="modal-body">
	                            <label class="form-label">Descreva o motivo *</label>
	                            <textarea name="observacao" class="form-control" rows="4" required></textarea>
	                        </div>
	                        <div class="modal-footer">
	                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
	                            <button class="btn btn-warning">Confirmar Acionamento</button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    @endif
	@endforeach
	@endsection
