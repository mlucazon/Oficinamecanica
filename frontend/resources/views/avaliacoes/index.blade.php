@extends('layouts.app')
@section('title', 'Avaliacoes')
@section('breadcrumb', 'Avaliacoes')

@push('styles')
<style>
    .rating-stars {
        display: inline-flex;
        gap: 3px;
        color: #f4b740;
        letter-spacing: 1px;
    }

    .review-media {
        width: 100%;
        aspect-ratio: 16 / 10;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border2);
        background: var(--surface2);
    }

    .review-reply {
        border-left: 3px solid var(--red);
        background: rgba(196,0,0,.08);
        border-radius: 0 8px 8px 0;
        padding: .85rem 1rem;
    }

    .reviews-page .text-muted,
    .reviews-page .review-muted {
        color: #d2d2d2 !important;
    }

    .review-choice {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: .75rem;
        align-items: end;
    }

    :root[data-theme="light"] .review-reply {
        background: rgba(176,0,0,.08);
    }

    :root[data-theme="light"] .reviews-page .text-muted,
    :root[data-theme="light"] .reviews-page .review-muted {
        color: #5f554b !important;
    }

    @media (max-width: 576px) {
        .review-choice {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="reviews-page">
@if(auth()->user()->isCliente() && $ordensPendentes->count() > 0)
    <div class="card mb-3">
        <div class="card-header">
            <i class="bi bi-star me-2 text-warning"></i>OS finalizadas para avaliar
        </div>
        <div class="card-body">
            <form class="review-choice" id="review-os-form" data-base-url="{{ url('/avaliacoes/os') }}">
                <div>
                    <label for="review-os-select" class="form-label">Escolha qual OS deseja avaliar</label>
                    <select id="review-os-select" class="form-select" required>
                        <option value="">Selecione uma OS finalizada...</option>
                        @foreach($ordensPendentes as $os)
                            <option value="{{ $os->id }}">
                                {{ $os->numero }} - {{ $os->veiculo->marca }} {{ $os->veiculo->modelo }} - {{ $os->veiculo->placa }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-star-fill me-1"></i>Avaliar OS
                </button>
            </form>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <span><i class="bi bi-chat-square-heart me-2"></i>Avaliacoes dos clientes</span>
        <span class="badge bg-secondary">{{ $avaliacoes->total() }}</span>
    </div>
    <div class="card-body">
        @forelse($avaliacoes as $avaliacao)
            <article class="border-bottom pb-3 mb-3" style="border-color: var(--border) !important;">
                <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <strong>{{ $avaliacao->cliente->nome }}</strong>
                            <span class="font-mono small text-muted">OS {{ $avaliacao->ordemServico->numero }}</span>
                        </div>
                        <div class="small text-muted">
                            {{ $avaliacao->ordemServico->veiculo->marca }} {{ $avaliacao->ordemServico->veiculo->modelo }}
                            - {{ $avaliacao->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="rating-stars" aria-label="{{ $avaliacao->nota }} de 5 estrelas">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $avaliacao->nota ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </div>
                </div>

                <p class="mt-3 mb-3">{{ $avaliacao->comentario }}</p>

                @if($avaliacao->fotoAntesUrl() || $avaliacao->fotoDepoisUrl())
                    <div class="row g-2 mb-3">
                        @if($avaliacao->fotoAntesUrl())
                            <div class="col-md-6">
                                <div class="small text-muted mb-1">Antes</div>
                                <img src="{{ $avaliacao->fotoAntesUrl() }}" alt="Foto antes da OS" class="review-media">
                            </div>
                        @endif
                        @if($avaliacao->fotoDepoisUrl())
                            <div class="col-md-6">
                                <div class="small text-muted mb-1">Depois</div>
                                <img src="{{ $avaliacao->fotoDepoisUrl() }}" alt="Foto depois da OS" class="review-media">
                            </div>
                        @endif
                    </div>
                @endif

                @if($avaliacao->resposta)
                    <div class="review-reply">
                        <div class="small text-muted mb-1">
                            Resposta da oficina
                            @if($avaliacao->respondente)
                                por {{ $avaliacao->respondente->name }}
                            @endif
                        </div>
                        <div>{{ $avaliacao->resposta }}</div>
                    </div>
                @endif

                @if(auth()->user()->isAtendente() || auth()->user()->isGerente())
                    <form method="POST" action="{{ route('avaliacoes.responder', $avaliacao) }}" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <label class="form-label">Responder avaliacao</label>
                        <textarea name="resposta" rows="3" class="form-control" maxlength="3000" required>{{ old('resposta', $avaliacao->resposta) }}</textarea>
                        <div class="text-end mt-2">
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-reply-fill me-1"></i>Publicar resposta
                            </button>
                        </div>
                    </form>
                @endif
            </article>
        @empty
            <div class="text-center text-muted py-4">Nenhuma avaliacao publicada ainda.</div>
        @endforelse
    </div>
    @if($avaliacoes->hasPages())
        <div class="card-footer">
            {{ $avaliacoes->links() }}
        </div>
    @endif
</div>
</div>

@push('scripts')
<script>
document.getElementById('review-os-form')?.addEventListener('submit', function (event) {
    event.preventDefault();

    const select = document.getElementById('review-os-select');
    if (!select?.value) return;

    window.location.href = `${this.dataset.baseUrl}/${select.value}/criar`;
});
</script>
@endpush
@endsection
