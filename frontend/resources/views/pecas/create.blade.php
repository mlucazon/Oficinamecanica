@extends('layouts.app')
@section('title', isset($peca) ? 'Editar Peca' : 'Nova Peca')
@section('breadcrumb', isset($peca) ? 'Editar Peca' : 'Nova Peca')

@section('content')
@php
    $marcaSelecionada = (int) old('marca_veiculo_id', $peca->marca_veiculo_id ?? 0);
    $modeloSelecionado = (int) old('modelo_veiculo_id', $peca->modelo_veiculo_id ?? 0);
@endphp

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i>{{ isset($peca) ? 'Editar' : 'Nova' }} Peca</div>
            <div class="card-body">
                <form method="POST" action="{{ isset($peca) ? route('pecas.update', $peca) : route('pecas.store') }}">
                    @csrf
                    @if(isset($peca)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome', $peca->nome ?? '') }}" required>
                            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Codigo</label>
                            <input type="text" name="codigo" class="form-control font-mono @error('codigo') is-invalid @enderror" value="{{ old('codigo', $peca->codigo ?? '') }}">
                            @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Fabricante da peca</label>
                            <input type="text" name="fabricante" class="form-control @error('fabricante') is-invalid @enderror" value="{{ old('fabricante', $peca->fabricante ?? '') }}">
                            @error('fabricante')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Marca do veiculo</label>
                            <select name="marca_veiculo_id" id="marca-veiculo" class="form-select @error('marca_veiculo_id') is-invalid @enderror">
                                <option value="">Universal</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}" @selected($marcaSelecionada === $marca->id)>{{ $marca->nome }}</option>
                                @endforeach
                            </select>
                            @error('marca_veiculo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Modelo do veiculo</label>
                            <select name="modelo_veiculo_id" id="modelo-veiculo" class="form-select @error('modelo_veiculo_id') is-invalid @enderror">
                                <option value="">Todos os modelos</option>
                            </select>
                            @error('modelo_veiculo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Unidade</label>
                            <select name="unidade" class="form-select">
                                @foreach(['un','kg','l','m','par','jogo','cx'] as $u)
                                    <option value="{{ $u }}" @selected(old('unidade', $peca->unidade ?? 'un') === $u)>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Preco de custo *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" name="preco_custo" class="form-control font-mono @error('preco_custo') is-invalid @enderror" step="0.01" min="0" value="{{ old('preco_custo', $peca->preco_custo ?? '') }}" required>
                                @error('preco_custo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Preco de venda *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" name="preco_venda" class="form-control font-mono @error('preco_venda') is-invalid @enderror" step="0.01" min="0" value="{{ old('preco_venda', $peca->preco_venda ?? '') }}" required>
                                @error('preco_venda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Estoque atual *</label>
                            <input type="number" name="estoque" class="form-control font-mono @error('estoque') is-invalid @enderror" min="0" value="{{ old('estoque', $peca->estoque ?? 0) }}" required>
                            @error('estoque')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Estoque minimo *</label>
                            <input type="number" name="estoque_minimo" class="form-control font-mono @error('estoque_minimo') is-invalid @enderror" min="0" value="{{ old('estoque_minimo', $peca->estoque_minimo ?? 5) }}" required>
                            @error('estoque_minimo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                        <a href="{{ route('pecas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modelosPorMarcaPeca = @json($marcas->mapWithKeys(fn ($marca) => [$marca->id => $marca->modelos->map(fn ($modelo) => ['id' => $modelo->id, 'nome' => $modelo->nome])->values()]));
    const marcaPeca = document.getElementById('marca-veiculo');
    const modeloPeca = document.getElementById('modelo-veiculo');
    const modeloSelecionadoPeca = Number(@json($modeloSelecionado));

    function preencherModelosPeca(selectedId = null) {
        const marcaId = marcaPeca.value;
        const modelos = modelosPorMarcaPeca[marcaId] || [];
        modeloPeca.innerHTML = '<option value="">Todos os modelos</option>';
        modeloPeca.disabled = modelos.length === 0;

        modelos.forEach((modelo) => {
            const option = document.createElement('option');
            option.value = modelo.id;
            option.textContent = modelo.nome;
            option.selected = Number(selectedId) === Number(modelo.id);
            modeloPeca.appendChild(option);
        });
    }

    marcaPeca?.addEventListener('change', () => preencherModelosPeca(null));
    preencherModelosPeca(modeloSelecionadoPeca);
</script>
@endpush
