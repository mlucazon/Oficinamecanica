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
                        <input type="text" name="cartao_numero" class="form-control" inputmode="numeric" maxlength="24" placeholder="0000 0000 0000 0000" value="{{ old('cartao_numero') }}" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nome impresso *</label>
                        <input type="text" name="cartao_nome" class="form-control" placeholder="Nome no cartao" value="{{ old('cartao_nome') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Validade *</label>
                        <input type="text" name="cartao_validade" class="form-control" maxlength="5" placeholder="MM/AA" value="{{ old('cartao_validade') }}" required>
                    </div>

                    <div class="col-12 small text-muted">
                        O CVV e o numero completo nao ficam armazenados. O sistema salva apenas o final do cartao.
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
