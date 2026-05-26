@extends('layouts.app')
@section('title', 'Avaliar OS')
@section('breadcrumb', 'Avaliar OS')

@push('styles')
<style>
    .star-picker {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 4px;
    }

    .star-picker input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .star-picker label {
        cursor: pointer;
        color: var(--text3);
        font-size: 30px;
        line-height: 1;
        transition: color .15s, transform .15s;
    }

    .star-picker label:hover,
    .star-picker label:hover ~ label,
    .star-picker input:checked ~ label {
        color: #f4b740;
        transform: translateY(-1px);
    }

    .review-help {
        color: var(--text2);
        font-size: 13px;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-star me-2 text-warning"></i>Avaliar {{ $ordemServico->numero }}
            </div>
            <div class="card-body">
                <div class="mb-3 p-3 rounded" style="background: var(--surface2); border: 1px solid var(--border);">
                    <div class="fw-500">{{ $ordemServico->veiculo->marca }} {{ $ordemServico->veiculo->modelo }}</div>
                    <div class="small text-muted">
                        Placa {{ $ordemServico->veiculo->placa }}
                        @if($ordemServico->data_conclusao)
                            - finalizada em {{ $ordemServico->data_conclusao->format('d/m/Y H:i') }}
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('avaliacoes.store', $ordemServico) }}" enctype="multipart/form-data" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label class="form-label d-block">Nota</label>
                        <div class="star-picker" aria-label="Escolha uma nota de 1 a 5 estrelas">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="nota" id="nota-{{ $i }}" value="{{ $i }}" @checked((int) old('nota', 5) === $i)>
                                <label for="nota-{{ $i }}" title="{{ $i }} estrela{{ $i > 1 ? 's' : '' }}">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            @endfor
                        </div>
                        @error('nota')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Comentario</label>
                        <textarea name="comentario" rows="5" class="form-control @error('comentario') is-invalid @enderror" maxlength="3000" required>{{ old('comentario') }}</textarea>
                        <div class="review-help mt-1">Conte como foi o atendimento, o reparo e a entrega do veiculo.</div>
                        @error('comentario')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto antes (opcional)</label>
                        <input type="file" name="foto_antes" class="form-control @error('foto_antes') is-invalid @enderror" accept="image/*">
                        @error('foto_antes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto depois (opcional)</label>
                        <input type="file" name="foto_depois" class="form-control @error('foto_depois') is-invalid @enderror" accept="image/*">
                        @error('foto_depois')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 flex-wrap">
                        <a href="{{ route('avaliacoes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Publicar avaliacao
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
