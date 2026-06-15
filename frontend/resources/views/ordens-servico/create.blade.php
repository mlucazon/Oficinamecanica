@extends('layouts.app')
@section('title', 'Abrir Ordem de Servico')
@section('breadcrumb', 'Nova OS')

@php
    $isAtendimentoPresencial = auth()->user()->isAtendente() || auth()->user()->isGerente();
    $clienteLogado = auth()->user()->cliente;
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <span>
                    <i class="bi bi-clipboard2-plus me-2"></i>
                    {{ $isAtendimentoPresencial ? 'Novo atendimento presencial' : 'Abrir Ordem de Servico' }}
                </span>
                @if($isAtendimentoPresencial)
                    <span class="badge bg-info">Atendente</span>
                @endif
            </div>
            <div class="card-body">
                @if($isAtendimentoPresencial)
                    <div class="alert alert-info d-flex align-items-start gap-2">
                        <i class="bi bi-person-workspace mt-1"></i>
                        <div>
                            <strong>Atendimento presencial</strong>
                            <div>Use esta tela quando o cliente estiver na oficina e o atendente for registrar o pedido por ele.</div>
                            <div class="mt-2 d-flex gap-2 flex-wrap">
                                <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-person-plus me-1"></i>Cadastrar cliente
                                </a>
                                <a href="{{ route('veiculos.create') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-car-front me-1"></i>Cadastrar veiculo
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('os.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        @if($isAtendimentoPresencial)
                            <div class="col-12">
                                <label class="form-label">Cliente *</label>
                                <select name="cliente_id" id="sel-cliente" class="form-select @error('cliente_id') is-invalid @enderror" required>
                                    <option value="">Selecione o cliente...</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" @selected((int) old('cliente_id') === (int) $cliente->id)>
                                            {{ $cliente->nome }} - {{ $cliente->telefone ?? $cliente->email ?? 'sem contato' }} - {{ $cliente->veiculos_count }} veiculo(s)
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Se o cliente ainda nao existir, cadastre o cliente e o veiculo antes de abrir a OS.</div>
                            </div>
                        @else
                            <input type="hidden" name="cliente_id" id="sel-cliente" value="{{ optional($clienteLogado)->id }}">
                        @endif

                        <div class="col-md-6">
                            <label class="form-label">Veiculo *</label>
                            <select name="veiculo_id" id="sel-veiculo" class="form-select @error('veiculo_id') is-invalid @enderror" required>
                                <option value="">Selecione o veiculo...</option>
                            </select>
                            @error('veiculo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Km na entrada</label>
                            <div class="input-group">
                                <input type="number" name="km_entrada" id="km_entrada" class="form-control font-mono"
                                       value="{{ old('km_entrada') }}" placeholder="ex: 45000" min="0" aria-label="Km na entrada" step="1">
                                <span class="input-group-text">km</span>
                            </div>
                            <div class="form-text">Ao selecionar o veiculo, o km atual pode ser preenchido automaticamente.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Sintomas / queixa do cliente *</label>
                            <textarea name="sintomas" class="form-control @error('sintomas') is-invalid @enderror"
                                      rows="4" required placeholder="{{ $isAtendimentoPresencial ? 'Descreva o relato do cliente...' : 'Descreva seu relato...' }}">{{ old('sintomas') }}</textarea>
                            @error('sintomas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Foto do defeito {{ $isAtendimentoPresencial ? '(opcional)' : '(obrigatoria)' }}</label>
                            <input type="file" name="foto_defeito" id="foto_defeito" class="form-control @error('foto_defeito') is-invalid @enderror" accept="image/*" @required(!$isAtendimentoPresencial)>
                            @error('foto_defeito')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Envie uma foto para ajudar no diagnostico.</div>

                            <div class="mt-2" id="preview-foto" style="display:none;">
                                <img id="img-preview" class="img-fluid rounded" style="max-height:160px;object-fit:cover;" alt="Previa da foto">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Video do defeito (opcional)</label>
                            <input type="file" name="video_defeito" id="video_defeito" class="form-control @error('video_defeito') is-invalid @enderror" accept="video/*,.mp4,.mov,.m4v,.webm,.ogg,.avi,.3gp,.3gpp">
                            @error('video_defeito')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Se o defeito for perceptivel ou audivel, envie um video. Limite: 100 MB.</div>

                            <div class="mt-2" id="preview-video" style="display:none;">
                                <video id="video-preview" class="w-100 rounded" style="max-height:160px;object-fit:cover;" controls></video>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-clipboard2-check me-1"></i>
                            {{ $isAtendimentoPresencial ? 'Registrar atendimento presencial' : 'Abrir OS' }}
                        </button>
                        <a href="{{ $isAtendimentoPresencial ? route('os.index') : route('conta.os') }}" class="btn btn-outline-danger" onclick="return confirm('Cancelar o envio desta OS? As informacoes preenchidas serao perdidas.')">
                            <i class="bi bi-x-circle me-1"></i>Cancelar envio
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const clienteSelect = document.getElementById('sel-cliente');
    const veiculoSelect = document.getElementById('sel-veiculo');
    const kmEntrada = document.getElementById('km_entrada');
    const oldVeiculoId = @json(old('veiculo_id'));

    function clienteIdAtual() {
        return clienteSelect?.value || '';
    }

    function carregarVeiculos(clienteId) {
        if (!clienteId) {
            veiculoSelect.innerHTML = '<option value="">Selecione o cliente primeiro...</option>';
            kmEntrada.value = '';
            return;
        }

        veiculoSelect.innerHTML = '<option value="">Carregando...</option>';

        fetch(`/clientes/${clienteId}/veiculos`)
            .then(response => response.json())
            .then(veiculos => {
                if (!veiculos.length) {
                    veiculoSelect.innerHTML = '<option value="">Nenhum veiculo cadastrado</option>';
                    kmEntrada.value = '';
                    return;
                }

                veiculoSelect.innerHTML = '<option value="">Selecione o veiculo...</option>';
                veiculos.forEach(veiculo => {
                    const option = document.createElement('option');
                    option.value = veiculo.id;
                    option.dataset.kmAtual = veiculo.km_atual ?? '';
                    option.textContent = `${veiculo.marca} ${veiculo.modelo} ${veiculo.ano ?? ''} - ${veiculo.placa}`;
                    if (String(oldVeiculoId || '') === String(veiculo.id)) {
                        option.selected = true;
                    }
                    veiculoSelect.appendChild(option);
                });

                preencherKm();
            });
    }

    function preencherKm() {
        const option = veiculoSelect.options[veiculoSelect.selectedIndex];
        const km = option?.dataset?.kmAtual;
        if (km !== undefined && km !== '') {
            kmEntrada.value = km;
        }
    }

    clienteSelect?.addEventListener('change', function () {
        carregarVeiculos(this.value);
    });

    veiculoSelect?.addEventListener('change', preencherKm);

    carregarVeiculos(clienteIdAtual());
})();

(function () {
    const fotoInput = document.getElementById('foto_defeito');
    const videoInput = document.getElementById('video_defeito');
    const previewFoto = document.getElementById('preview-foto');
    const imgPreview = document.getElementById('img-preview');
    const previewVideo = document.getElementById('preview-video');
    const videoPreview = document.getElementById('video-preview');

    fotoInput?.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) {
            previewFoto.style.display = 'none';
            imgPreview.removeAttribute('src');
            return;
        }

        imgPreview.src = URL.createObjectURL(file);
        previewFoto.style.display = '';
    });

    videoInput?.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) {
            previewVideo.style.display = 'none';
            videoPreview.removeAttribute('src');
            videoPreview.load();
            return;
        }

        videoPreview.src = URL.createObjectURL(file);
        previewVideo.style.display = '';
    });
})();
</script>
@endpush
