@extends('layouts.app')
@section('title', 'Meus cartoes')
@section('breadcrumb', 'Meus cartoes')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <span><i class="bi bi-credit-card-2-front me-2 text-warning"></i>Meus cartoes</span>
                <a href="{{ route('cartoes.create') }}" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-plus-lg me-1"></i>Adicionar cartao
                </a>
            </div>
            <div class="card-body">
                @if($cartoes->isEmpty())
                    <p class="text-muted mb-0">Nenhum cartao cadastrado.</p>
                @else
                    <div class="saved-card-list">
                        @foreach($cartoes as $cartao)
                            <div class="saved-card-item">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="saved-card-brand">
                                        <i class="bi bi-credit-card"></i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">{{ $cartao->bandeira }} final {{ $cartao->final }}</div>
                                        <div class="small text-muted">
                                            {{ ucfirst($cartao->tipo) }} - {{ $cartao->titular }} - validade {{ $cartao->validade }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                                    <span class="badge bg-secondary">Salvo em {{ $cartao->created_at->format('d/m/Y') }}</span>
                                    <form method="POST" action="{{ route('cartoes.destroy', $cartao) }}" onsubmit="return confirm('Remover este cartao?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i>Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('perfil.edit') }}" class="btn btn-outline-secondary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
