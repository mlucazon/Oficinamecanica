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
                    <div class="info-block">
                        <span class="info-block-icon">
                            <i class="bi bi-credit-card"></i>
                        </span>
                        <div>
                            <div class="info-block-title">Nenhum cartao cadastrado.</div>
                            <div class="info-block-text">Cadastre um cartao para ele aparecer como opcao na hora de pagar uma OS.</div>
                        </div>
                        <div class="info-block-actions">
                            <a href="{{ route('cartoes.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Adicionar cartao
                            </a>
                        </div>
                    </div>
                @else
                    <div class="info-block-list">
                        @foreach($cartoes as $cartao)
                            <div class="info-block">
                                <span class="info-block-icon">
                                    <i class="bi bi-credit-card"></i>
                                </span>
                                <div>
                                    <span class="info-block-kicker">{{ ucfirst($cartao->tipo) }}</span>
                                    <div class="info-block-title">{{ $cartao->bandeira }} final {{ $cartao->final }}</div>
                                    <div class="info-block-text">{{ $cartao->titular }} - validade {{ $cartao->validade }}</div>
                                    <div class="info-block-meta">
                                        <span class="badge bg-secondary">Salvo em {{ $cartao->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div class="info-block-actions">
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
