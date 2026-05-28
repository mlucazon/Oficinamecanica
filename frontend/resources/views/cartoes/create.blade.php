@extends('layouts.app')
@section('title', 'Adicionar cartao')
@section('breadcrumb', 'Adicionar cartao')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-credit-card-2-front me-2 text-warning"></i>Adicionar cartao
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('cartoes.store') }}" class="row g-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="redirect_to" value="perfil">

                    <div class="col-md-4">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo_cartao" class="form-select" required>
                            <option value="debito" {{ old('tipo_cartao') === 'debito' ? 'selected' : '' }}>Debito</option>
                            <option value="credito" {{ old('tipo_cartao') === 'credito' ? 'selected' : '' }}>Credito</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Numero *</label>
                        <input type="text" name="cartao_numero" class="form-control js-card-number @error('cartao_numero') is-invalid @enderror" inputmode="numeric" maxlength="19" placeholder="0000 0000 0000 0000" value="{{ old('cartao_numero') }}" required>
                        @error('cartao_numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nome impresso *</label>
                        <input type="text" name="cartao_nome" class="form-control @error('cartao_nome') is-invalid @enderror" placeholder="Nome no cartao" value="{{ old('cartao_nome') }}" required>
                        @error('cartao_nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Validade *</label>
                        <input type="text" name="cartao_validade" class="form-control js-card-validity @error('cartao_validade') is-invalid @enderror" inputmode="numeric" maxlength="5" placeholder="MM/AA" value="{{ old('cartao_validade') }}" required>
                        @error('cartao_validade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">CVV *</label>
                        <input type="text" name="cartao_cvv" class="form-control js-card-cvv @error('cartao_cvv') is-invalid @enderror" inputmode="numeric" maxlength="4" placeholder="123" value="{{ old('cartao_cvv') }}" required>
                        @error('cartao_cvv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 d-flex gap-2 flex-wrap">
                        <button class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Salvar cartao
                        </button>
                        <a href="{{ route('perfil.edit') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-card-number').forEach((input) => {
        const formatCardNumber = () => {
            input.value = input.value
                .replace(/\D/g, '')
                .slice(0, 16)
                .replace(/(\d{4})(?=\d)/g, '$1 ');
        };

        input.addEventListener('input', formatCardNumber);
        formatCardNumber();
    });

    document.querySelectorAll('.js-card-validity').forEach((input) => {
        const formatValidity = () => {
            const value = input.value.replace(/\D/g, '').slice(0, 4);
            input.value = value.length > 2 ? `${value.slice(0, 2)}/${value.slice(2)}` : value;
        };

        input.addEventListener('input', formatValidity);
        formatValidity();
    });

    document.querySelectorAll('.js-card-cvv').forEach((input) => {
        const formatCvv = () => {
            input.value = input.value.replace(/\D/g, '').slice(0, 4);
        };

        input.addEventListener('input', formatCvv);
        formatCvv();
    });
});
</script>
@endpush
