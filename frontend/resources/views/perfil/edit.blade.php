@extends('layouts.app')
@section('title', 'Meu perfil')
@section('breadcrumb', 'Meu perfil')

@push('styles')
<style>
	    .profile-photo-card .card-body {
	        min-height: 260px;
	        display: flex;
	        flex-direction: column;
	        align-items: center;
	        justify-content: center;
	        gap: .75rem;
	    }

	    .profile-avatar-wrap {
	        width: 128px;
	        height: 128px;
        border-radius: 50%;
        position: relative;
        padding: 5px;
        background: linear-gradient(135deg, rgba(255,255,255,.16), rgba(196,0,0,.34), rgba(255,255,255,.06));
        box-shadow: 0 24px 60px rgba(0,0,0,.34);
    }

    .profile-avatar-frame {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: radial-gradient(circle at 35% 25%, rgba(255,255,255,.09), var(--surface2) 58%, #080808 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid var(--border2);
    }

    .profile-avatar-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .profile-avatar-initial {
        font-family: 'Syne', sans-serif;
        font-size: 46px;
        font-weight: 800;
        color: #fff;
    }

    .profile-avatar-wrap .status-dot {
        width: 15px;
        height: 15px;
        position: absolute;
        right: 22px;
        bottom: 14px;
        border-width: 3px;
    }

	    .profile-photo-action {
	        position: absolute;
	        right: 3px;
	        bottom: 14px;
	        width: 34px;
	        height: 34px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--surface);
        box-shadow: 0 10px 22px rgba(0,0,0,.35);
    }

    .profile-photo-hint {
        max-width: 290px;
        color: rgba(255,255,255,.72);
        font-size: 12px;
        line-height: 1.5;
        text-align: center;
    }

    .profile-page-grid .text-muted,
    .profile-photo-card .text-muted,
    .profile-crop-modal .text-muted {
        color: rgba(255,255,255,.68) !important;
    }

    .profile-page-grid .small.text-muted {
        color: rgba(255,255,255,.62) !important;
    }

    .profile-page-grid .fw-500 {
        color: rgba(255,255,255,.94);
        font-weight: 700;
    }

    .profile-pretty-form .form-label {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #d8d8d8;
        font-size: 12px;
        font-weight: 700;
    }

    .profile-pretty-form .form-label i {
        color: var(--red-h);
        font-size: 13px;
    }

	    .profile-pretty-form .form-control,
	    .profile-pretty-form .form-select {
	        min-height: 42px;
        background: linear-gradient(180deg, rgba(255,255,255,.045), rgba(255,255,255,.018));
        border: 1px solid rgba(255,255,255,.12);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.04);
	        padding: .58rem .85rem;
	        font-size: 14px;
	    }

	    .profile-page-grid {
	        --bs-gutter-x: 1.6rem;
	        --bs-gutter-y: 1.35rem;
	    }

	    .profile-page-grid .card {
	        max-width: 100%;
	    }

	    .profile-page-grid .card-header {
	        padding-top: .85rem;
	        padding-bottom: .85rem;
	    }

	    .profile-page-grid .card-body {
	        padding: 1rem 1.15rem;
	    }

	    .profile-main-col {
	        display: flex;
	        flex-direction: column;
	        gap: 1.25rem;
	    }

	    .profile-main-col > .card.mt-3 {
	        margin-top: 0 !important;
	    }

	    @media (min-width: 992px) {
	        .profile-photo-col {
	            flex: 0 0 31%;
	            max-width: 31%;
	        }

	        .profile-main-col {
	            flex: 0 0 67%;
	            max-width: 67%;
	        }
	    }

	    @media (max-width: 576px) {
	        .profile-page-grid {
	            --bs-gutter-y: .95rem;
	        }

	        .profile-photo-card .card-body {
	            min-height: 210px;
	            padding-block: 1rem !important;
	        }

	        .profile-avatar-wrap {
	            width: 112px;
	            height: 112px;
	        }

	        .profile-photo-action {
	            width: 32px;
	            height: 32px;
	            bottom: 10px;
	        }

	        .profile-pretty-form {
	            --bs-gutter-y: .8rem;
	        }

	        .profile-pretty-form .form-label {
	            font-size: 11.5px;
	            margin-bottom: .35rem;
	        }

	        .profile-pretty-form .form-control,
	        .profile-pretty-form .form-select {
	            min-height: 40px;
	            padding: .54rem .75rem;
	        }

	        #btn-editar-perfil,
	        #perfil-actions .btn,
	        #btn-trocar-senha-manual {
	            width: 100%;
	        }

	        .profile-main-col .card-header {
	            align-items: stretch !important;
	        }

	        .profile-main-col .card-header > span {
	            width: 100%;
	        }

	        .profile-main-col .card-body.d-flex {
	            align-items: stretch !important;
	        }

	        .profile-main-col .card-body.d-flex > div,
	        .profile-main-col .card-body.d-flex .text-end {
	            width: 100%;
	        }
	    }

    .profile-pretty-form .form-control[readonly],
    .profile-pretty-form .form-select[aria-disabled="true"] {
        color: rgba(255,255,255,.88);
        background: rgba(255,255,255,.026);
        border-color: rgba(255,255,255,.09);
    }

    .searchable-select {
        position: relative;
    }

    .searchable-select .form-control {
        padding-right: 2.35rem;
    }

    .searchable-select::after {
        content: '\F282';
        font-family: 'bootstrap-icons';
        position: absolute;
        right: .85rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text2);
        pointer-events: none;
        font-size: .9rem;
    }

    .searchable-options {
        position: absolute;
        left: 0;
        right: 0;
        top: calc(100% + 6px);
        max-height: 230px;
        overflow-y: auto;
        display: none;
        padding: .35rem;
        border: 1px solid var(--border2);
        border-radius: 8px;
        background: var(--surface);
        box-shadow: 0 18px 42px rgba(0,0,0,.36);
        z-index: 40;
    }

    .searchable-options.show {
        display: grid;
        gap: .25rem;
    }

    .searchable-option {
        width: 100%;
        border: 0;
        border-radius: 6px;
        padding: .62rem .7rem;
        background: transparent;
        color: var(--text);
        text-align: left;
        font: inherit;
        cursor: pointer;
    }

    .searchable-option:hover,
    .searchable-option:focus {
        background: var(--red-dim);
        color: var(--text);
        outline: none;
    }

    .searchable-empty {
        padding: .65rem .7rem;
        color: var(--text2);
        font-size: .9rem;
    }

    :root[data-theme="light"] .profile-pretty-form .form-label {
        color: #3b332b;
    }

    :root[data-theme="light"] .profile-page-grid .fw-500 {
        color: #211a15;
    }

    :root[data-theme="light"] .profile-pretty-form .form-label i {
        color: var(--red-h);
    }

    :root[data-theme="light"] .profile-pretty-form .form-control,
    :root[data-theme="light"] .profile-pretty-form .form-select {
        color: #17130f;
        background: #fffdf9;
        border-color: rgba(31,25,20,.22);
        box-shadow: inset 0 1px 0 rgba(31,25,20,.06);
    }

    :root[data-theme="light"] .profile-pretty-form .form-control[readonly],
    :root[data-theme="light"] .profile-pretty-form .form-select[aria-disabled="true"] {
        color: #17130f;
        background: #eee3d9;
        border-color: rgba(31,25,20,.20);
        -webkit-text-fill-color: #17130f;
        opacity: 1;
    }

    .profile-pretty-form .form-select option {
        background: var(--surface);
        color: var(--text);
    }

    :root[data-theme="light"] .profile-pretty-form .form-select option {
        background: #ffffff;
        color: #17130f;
    }

    :root[data-theme="light"] .profile-photo-hint,
    :root[data-theme="light"] .profile-photo-card .text-muted,
    :root[data-theme="light"] .card .text-muted {
        color: #4f4238 !important;
    }

    :root[data-theme="light"] .profile-avatar-frame {
        background: radial-gradient(circle at 35% 25%, #ffffff 0%, #f1e7de 62%, #d8c7b7 100%);
        border-color: rgba(31,25,20,.18);
    }

    :root[data-theme="light"] .profile-avatar-initial {
        color: #211a15;
    }

    .profile-crop-modal .modal-content {
        background: var(--surface);
        border: 1px solid var(--border2);
        color: var(--text);
    }

    .photo-crop-stage {
        width: min(320px, 100%);
        aspect-ratio: 1;
        border-radius: 50%;
        margin: 0 auto;
        overflow: hidden;
        border: 1px solid var(--border2);
        background: #050505;
        box-shadow: 0 24px 60px rgba(0,0,0,.38);
        touch-action: none;
    }

    #photo-crop-canvas {
        width: 100%;
        height: 100%;
        display: block;
        cursor: grab;
    }
</style>
@endpush

@section('content')
<div class="row profile-page-grid">
    <div class="col-lg-4 profile-photo-col">
        <div class="card h-100 profile-photo-card">
            <div class="card-header">Foto de perfil</div>
            <div class="card-body text-center">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar-frame">
                        @if($user->profilePhotoUrl())
                            <img id="profile_photo_preview" src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}">
                        @else
                            <img id="profile_photo_preview" src="" alt="{{ $user->name }}" style="display:none;">
                            <span id="profile_photo_initial" class="profile-avatar-initial">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <button type="button" id="btn-trocar-foto" class="btn btn-danger btn-sm profile-photo-action" title="Alterar foto">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                </div>
                <p class="text-muted small mb-0">Envie uma imagem JPG, PNG ou WEBP de até 5 MB.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 profile-main-col">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <span>Informações da conta</span>
                <button type="button" class="btn btn-outline-danger btn-sm" id="btn-editar-perfil">
                    <i class="bi bi-pencil-square me-1"></i>Editar informações da conta
                </button>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="row g-3 profile-pretty-form" id="perfil-form">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-person"></i>Nome</label>
                        <input type="text" name="name" class="form-control js-profile-field @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-envelope"></i>E-mail</label>
                        <input type="email" name="email" class="form-control js-profile-field @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <input type="file" name="profile_photo" id="profile_photo" class="d-none @error('profile_photo') is-invalid @enderror" accept="image/*">
                    @error('profile_photo')
                        <div class="col-12">
                            <div class="alert alert-danger mb-0">{{ $message }}</div>
                        </div>
                    @enderror

                    @if($user->isCliente())
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-card-text"></i>CPF</label>
                            <input type="text" name="cpf" class="form-control js-profile-field @error('cpf') is-invalid @enderror" value="{{ old('cpf', $user->cliente?->cpf) }}" required>
                            @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-telephone"></i>Telefone</label>
                            <input type="text" name="telefone" class="form-control js-profile-field @error('telefone') is-invalid @enderror" value="{{ old('telefone', $user->cliente?->telefone) }}" required>
                            @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-map"></i>Estado</label>
                            @php $estadoSelecionado = old('estado', $user->cliente?->estado); @endphp
                            @php $estadoAtual = $estados->firstWhere('uf', $estadoSelecionado); @endphp
                            <input type="hidden" name="estado" id="estado-select" value="{{ $estadoSelecionado }}">
                            <div class="searchable-select">
                                <input
                                    type="text"
                                    id="estado-search"
                                    class="form-control js-profile-field @error('estado') is-invalid @enderror"
                                    value="{{ $estadoAtual ? $estadoAtual->nome . ' / ' . $estadoAtual->uf : '' }}"
                                    placeholder="Digite ou selecione..."
                                    autocomplete="off"
                                >
                                <div id="estado-options" class="searchable-options"></div>
                            </div>
                            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-geo-alt"></i>Cidade</label>
                            @php $cidadeSelecionada = old('cidade', $user->cliente?->cidade); @endphp
                            <div class="searchable-select">
                                <input
                                    type="text"
                                    name="cidade"
                                    id="cidade-select"
                                    class="form-control js-profile-field"
                                    value="{{ $cidadeSelecionada }}"
                                    data-selected="{{ $cidadeSelecionada }}"
                                    placeholder="Digite ou selecione..."
                                    autocomplete="off"
                                >
                                <div id="cidade-options" class="searchable-options"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-house-door"></i>Endereço</label>
                            <input type="text" name="endereco" class="form-control js-profile-field" value="{{ old('endereco', $user->cliente?->endereco) }}">
                        </div>
                    @endif

                    <div class="col-12 text-end d-none" id="perfil-actions">
                        <button class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Salvar perfil
                        </button>
                    </div>
                </form>
            </div>
        </div>


        @if($user->isCliente())
            <div class="card mt-3">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <span><i class="bi bi-credit-card-2-front me-2 text-warning"></i>Meus cartoes</span>
                    <span class="badge bg-secondary">
                        {{ $user->cartoes->count() }} salvo{{ $user->cartoes->count() === 1 ? '' : 's' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="info-block">
                        <span class="info-block-icon">
                                <i class="bi bi-credit-card"></i>
                        </span>
                        <div>
                                @if($user->cartoes->isEmpty())
                                    <div class="info-block-title">Adicione um cartao para facilitar seus pagamentos.</div>
                                    <div class="info-block-text">Quando voce aprovar uma OS, o cartao cadastrado podera ser escolhido na hora do pagamento.</div>
                                @else
                                    <div class="info-block-title">{{ $user->cartoes->count() }} cartao{{ $user->cartoes->count() > 1 ? 'es' : '' }} cadastrado{{ $user->cartoes->count() > 1 ? 's' : '' }}</div>
                                    <div class="info-block-text">Veja seus cartoes salvos ou cadastre um novo quando precisar.</div>
                                @endif
                        </div>

                        <div class="info-block-actions">
                            @if($user->cartoes->isEmpty())
                                <a href="{{ route('cartoes.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>Cadastrar cartao
                                </a>
                            @else
                                <a href="{{ route('cartoes.index') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-eye me-1"></i>Ver cartoes
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <span><i class="bi bi-key me-2 text-warning"></i>Senha</span>
                    @if($user->password_change_requested_at)
                        <span class="badge bg-secondary">Solicitada</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="info-block">
                        <span class="info-block-icon">
                            <i class="bi bi-key"></i>
                        </span>
                        <div>
                        <div class="info-block-title">Troca de senha</div>
                        <div class="info-block-text">
                            {{ $user->password_change_requested_at ? 'Solicitada em '.$user->password_change_requested_at->format('d/m/Y H:i') : 'Solicite ao gerente uma alteração de senha.' }}
                        </div>
                        </div>
	                    <div class="info-block-actions">
	                        @if($user->password_change_requested_at)
	                            <div class="d-flex flex-column align-items-end gap-2">
	                                <button class="btn btn-outline-danger" disabled>
	                                    <i class="bi bi-key me-1"></i>Senha solicitada
	                                </button>
	                                <form method="POST" action="{{ route('conta.senha.cancelar') }}">
	                                    @csrf
	                                    @method('DELETE')
	                                    <button class="btn btn-sm btn-outline-secondary" onclick="return confirm('Cancelar a solicitação de troca de senha?')">
	                                        <i class="bi bi-x-circle me-1"></i>Cancelar solicitação
	                                    </button>
	                                </form>
	                            </div>
	                        @else
	                            <form method="POST" action="{{ route('conta.senha.solicitar') }}">
	                                @csrf
	                                <button class="btn btn-outline-danger">
	                                    <i class="bi bi-key me-1"></i>Solicitar troca de senha
	                                </button>
	                            </form>
	                        @endif
	                        <button type="button" class="btn btn-link btn-sm text-danger text-decoration-none px-0 mt-1" id="btn-trocar-senha-manual">
	                            ou trocar manualmente
	                        </button>
                        </div>
                    </div>
                </div>
                <div class="border-top px-3 py-3" id="painel-trocar-senha-manual" style="{{ $errors->passwordUpdate->any() ? '' : 'display:none;' }}">
                    <form method="POST" action="{{ route('perfil.password') }}" class="row g-3">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-4">
                            <label for="current_password" class="form-label">Senha atual</label>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password', 'passwordUpdate') is-invalid @enderror" required autocomplete="current-password">
                            @error('current_password', 'passwordUpdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="manual_password" class="form-label">Nova senha</label>
                            <input type="password" name="password" id="manual_password" class="form-control @error('password', 'passwordUpdate') is-invalid @enderror" minlength="8" required autocomplete="new-password">
                            @error('password', 'passwordUpdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="manual_password_confirmation" class="form-label">Confirmar senha</label>
                            <input type="password" name="password_confirmation" id="manual_password_confirmation" class="form-control" minlength="8" required autocomplete="new-password">
                        </div>

                        <div class="col-12 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="mostrar-senha-manual">
                                <label class="form-check-label" for="mostrar-senha-manual">Mostrar senha</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="btn-cancelar-senha-manual">Cancelar</button>
                                <button class="btn btn-danger">
                                    <i class="bi bi-check2-circle me-1"></i>Alterar senha
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade profile-crop-modal" id="photoCropModal" tabindex="-1" aria-labelledby="photoCropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoCropModalLabel">Ajustar foto de perfil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="photo-crop-stage">
                    <canvas id="photo-crop-canvas" width="420" height="420"></canvas>
                </div>
                <label for="photo_zoom" class="form-label mt-3">Zoom</label>
                <input type="range" class="form-range" id="photo_zoom" min="1" max="3" step="0.01" value="1">
                <p class="text-muted small mb-0">Arraste a imagem para enquadrar o rosto. Depois confirme e salve o perfil.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btn-confirmar-foto">
                    <i class="bi bi-check2-circle me-1"></i>Usar esta foto
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const photoButton = document.getElementById('btn-trocar-foto');
    const photoInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('profile_photo_preview');
    const photoInitial = document.getElementById('profile_photo_initial');
    const profileForm = document.getElementById('perfil-form');
    const cropModalEl = document.getElementById('photoCropModal');
    const cropModal = cropModalEl ? new bootstrap.Modal(cropModalEl) : null;
    const cropCanvas = document.getElementById('photo-crop-canvas');
    const cropContext = cropCanvas ? cropCanvas.getContext('2d') : null;
    const zoomInput = document.getElementById('photo_zoom');
    const confirmPhotoButton = document.getElementById('btn-confirmar-foto');
    const estadoSelect = document.getElementById('estado-select');
    const estadoSearch = document.getElementById('estado-search');
    const cidadeSelect = document.getElementById('cidade-select');
    const estadoOptions = document.getElementById('estado-options');
    const cidadeOptions = document.getElementById('cidade-options');
    const editButton = document.getElementById('btn-editar-perfil');
    const profileActions = document.getElementById('perfil-actions');
    const profileFields = document.querySelectorAll('.js-profile-field');
    const shouldStartEditing = @json($errors->any());
    const manualPasswordButton = document.getElementById('btn-trocar-senha-manual');
    const manualPasswordPanel = document.getElementById('painel-trocar-senha-manual');
    const cancelManualPassword = document.getElementById('btn-cancelar-senha-manual');
    const showManualPassword = document.getElementById('mostrar-senha-manual');
    const currentPassword = document.getElementById('current_password');
    const manualPassword = document.getElementById('manual_password');
    const manualPasswordConfirmation = document.getElementById('manual_password_confirmation');
    const cidadesPorEstado = @json($estados->mapWithKeys(fn($estado) => [$estado->uf => $estado->cidades->pluck('nome')->values()]));
    const estadosOpcoes = @json($estados->map(fn($estado) => ['uf' => $estado->uf, 'nome' => $estado->nome, 'label' => $estado->nome . ' / ' . $estado->uf])->values());
    let cropImage = null;
    let cropObjectUrl = null;
    let cropScale = 1;
    let cropOffsetX = 0;
    let cropOffsetY = 0;
    let isDraggingCrop = false;
    let lastDragX = 0;
    let lastDragY = 0;

    function setProfileEditing(isEditing) {
        profileFields.forEach((field) => {
            if (field.tagName === 'SELECT') {
                field.classList.toggle('pe-none', !isEditing);
                field.setAttribute('aria-disabled', isEditing ? 'false' : 'true');
                field.tabIndex = isEditing ? 0 : -1;
            } else {
                field.readOnly = !isEditing;
            }
        });

        if (profileActions) {
            profileActions.classList.toggle('d-none', !isEditing);
        }

        if (editButton) {
            editButton.classList.toggle('d-none', isEditing);
        }
    }

    setProfileEditing(shouldStartEditing);

    function showProfileActions() {
        if (profileActions) {
            profileActions.classList.remove('d-none');
        }
    }

    function drawCrop() {
        if (!cropCanvas || !cropContext || !cropImage) {
            return;
        }

        const size = cropCanvas.width;
        const baseScale = Math.max(size / cropImage.width, size / cropImage.height);
        const finalScale = baseScale * cropScale;
        const imageWidth = cropImage.width * finalScale;
        const imageHeight = cropImage.height * finalScale;

        cropOffsetX = Math.min(0, Math.max(size - imageWidth, cropOffsetX));
        cropOffsetY = Math.min(0, Math.max(size - imageHeight, cropOffsetY));

        cropContext.clearRect(0, 0, size, size);
        cropContext.drawImage(cropImage, cropOffsetX, cropOffsetY, imageWidth, imageHeight);
    }

    function loadCropImage(file) {
        if (cropObjectUrl) {
            URL.revokeObjectURL(cropObjectUrl);
        }

        cropObjectUrl = URL.createObjectURL(file);
        cropImage = new Image();
        cropImage.onload = function () {
            const size = cropCanvas.width;
            const baseScale = Math.max(size / cropImage.width, size / cropImage.height);
            cropScale = 1;
            cropOffsetX = (size - cropImage.width * baseScale) / 2;
            cropOffsetY = (size - cropImage.height * baseScale) / 2;

            if (zoomInput) {
                zoomInput.value = '1';
            }

            drawCrop();

            if (cropModal) {
                cropModal.show();
            }
        };
        cropImage.src = cropObjectUrl;
    }

    if (editButton) {
        editButton.addEventListener('click', function () {
            setProfileEditing(true);
            const firstField = document.querySelector('.js-profile-field');
            if (firstField) {
                firstField.focus();
            }
        });
    }

    if (manualPasswordButton && manualPasswordPanel) {
        manualPasswordButton.addEventListener('click', function () {
            manualPasswordPanel.style.display = '';

            if (currentPassword) {
                currentPassword.focus();
            }
        });
    }

    if (cancelManualPassword && manualPasswordPanel) {
        cancelManualPassword.addEventListener('click', function () {
            manualPasswordPanel.style.display = 'none';

            [currentPassword, manualPassword, manualPasswordConfirmation].forEach((field) => {
                if (field) {
                    field.value = '';
                    field.type = 'password';
                }
            });

            if (showManualPassword) {
                showManualPassword.checked = false;
            }
        });
    }

    if (showManualPassword) {
        showManualPassword.addEventListener('change', function () {
            const type = this.checked ? 'text' : 'password';
            [currentPassword, manualPassword, manualPasswordConfirmation].forEach((field) => {
                if (field) {
                    field.type = type;
                }
            });
        });
    }

    if (photoButton && photoInput) {
        photoButton.addEventListener('click', function () {
            photoInput.click();
        });

        photoInput.addEventListener('change', function () {
            if (!this.files || !this.files[0]) {
                return;
            }

            loadCropImage(this.files[0]);
            this.value = '';
        });
    }

    if (zoomInput) {
        zoomInput.addEventListener('input', function () {
            cropScale = Number(this.value) || 1;
            drawCrop();
        });
    }

    if (cropCanvas) {
        cropCanvas.addEventListener('pointerdown', function (event) {
            isDraggingCrop = true;
            lastDragX = event.clientX;
            lastDragY = event.clientY;
            cropCanvas.setPointerCapture(event.pointerId);
        });

        cropCanvas.addEventListener('pointermove', function (event) {
            if (!isDraggingCrop) {
                return;
            }

            cropOffsetX += event.clientX - lastDragX;
            cropOffsetY += event.clientY - lastDragY;
            lastDragX = event.clientX;
            lastDragY = event.clientY;
            drawCrop();
        });

        cropCanvas.addEventListener('pointerup', function (event) {
            isDraggingCrop = false;
            cropCanvas.releasePointerCapture(event.pointerId);
        });

        cropCanvas.addEventListener('pointercancel', function () {
            isDraggingCrop = false;
        });
    }

    if (confirmPhotoButton && cropCanvas && photoInput) {
        confirmPhotoButton.addEventListener('click', function () {
            cropCanvas.toBlob(function (blob) {
                if (!blob) {
                    return;
                }

                const file = new File([blob], 'perfil.jpg', { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                photoInput.files = dataTransfer.files;

                if (photoPreview) {
                    photoPreview.src = URL.createObjectURL(blob);
                    photoPreview.style.display = 'block';
                }

                if (photoInitial) {
                    photoInitial.style.display = 'none';
                }

                showProfileActions();

                if (cropModal) {
                    cropModal.hide();
                }
            }, 'image/jpeg', 0.92);
        });
    }

    if (profileForm) {
        profileForm.addEventListener('submit', function () {
            sincronizarEstadoSelecionado();
            profileFields.forEach((field) => {
                if (field.tagName === 'SELECT') {
                    field.classList.remove('pe-none');
                    field.setAttribute('aria-disabled', 'false');
                } else {
                    field.readOnly = false;
                }
            });
        });
    }

    function sincronizarEstadoSelecionado(permitirParcial = false) {
        if (!estadoSearch || !estadoSelect) {
            return;
        }

        const typed = estadoSearch.value.trim().toLowerCase();
        const encontrado = estadosOpcoes.find((estado) =>
            estado.label.toLowerCase() === typed
            || estado.nome.toLowerCase() === typed
            || estado.uf.toLowerCase() === typed
            || (permitirParcial && estado.label.toLowerCase().startsWith(typed))
        );

        estadoSelect.value = encontrado ? encontrado.uf : '';
        if (encontrado) {
            estadoSearch.value = encontrado.label;
        }
    }

    function filtrarOpcoes(opcoes, termo) {
        const busca = termo.trim().toLowerCase();
        if (!busca) {
            return opcoes;
        }

        return opcoes.filter((opcao) => opcao.label.toLowerCase().includes(busca));
    }

    function renderizarOpcoes(menu, opcoes, selecionar) {
        if (!menu) {
            return;
        }

        menu.innerHTML = '';

        if (!opcoes.length) {
            const vazio = document.createElement('div');
            vazio.className = 'searchable-empty';
            vazio.textContent = 'Nenhuma opcao encontrada.';
            menu.appendChild(vazio);
            menu.classList.add('show');
            return;
        }

        opcoes.slice(0, 80).forEach((opcao) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'searchable-option';
            button.textContent = opcao.label;
            button.addEventListener('mousedown', function (event) {
                event.preventDefault();
                selecionar(opcao);
                fecharOpcoes();
            });
            menu.appendChild(button);
        });

        menu.classList.add('show');
    }

    function fecharOpcoes() {
        estadoOptions?.classList.remove('show');
        cidadeOptions?.classList.remove('show');
    }

    function opcoesDeCidade() {
        const uf = estadoSelect?.value || '';
        return (cidadesPorEstado[uf] || []).map((cidade) => ({ label: cidade, value: cidade }));
    }

    function selecionarEstado(opcao) {
        estadoSelect.value = opcao.uf;
        estadoSearch.value = opcao.label;
        cidadeSelect.value = '';
        cidadeSelect.dataset.selected = '';
        renderizarOpcoes(cidadeOptions, opcoesDeCidade(), selecionarCidade);
    }

    function selecionarCidade(opcao) {
        cidadeSelect.value = opcao.value;
        cidadeSelect.dataset.selected = opcao.value;
    }

    if (estadoSelect && estadoSearch && cidadeSelect && estadoOptions && cidadeOptions) {
        const estadoLista = estadosOpcoes.map((estado) => ({
            label: estado.label,
            value: estado.uf,
            uf: estado.uf,
        }));

        estadoSearch.addEventListener('focus', function () {
            if (this.readOnly) {
                setProfileEditing(true);
            }

            renderizarOpcoes(estadoOptions, estadoLista, selecionarEstado);
        });

        estadoSearch.addEventListener('input', function () {
            sincronizarEstadoSelecionado();
            cidadeSelect.value = '';
            cidadeSelect.dataset.selected = '';
            renderizarOpcoes(estadoOptions, filtrarOpcoes(estadoLista, this.value), selecionarEstado);
        });

        cidadeSelect.addEventListener('focus', function () {
            if (this.readOnly) {
                setProfileEditing(true);
            }

            renderizarOpcoes(cidadeOptions, opcoesDeCidade(), selecionarCidade);
        });

        cidadeSelect.addEventListener('input', function () {
            renderizarOpcoes(cidadeOptions, filtrarOpcoes(opcoesDeCidade(), this.value), selecionarCidade);
        });

        document.addEventListener('mousedown', function (event) {
            if (!event.target.closest('.searchable-select')) {
                fecharOpcoes();
            }
        });

        estadoSearch.addEventListener('blur', function () {
            window.setTimeout(function () {
                sincronizarEstadoSelecionado(true);
            }, 120);
        });
    }

});
</script>
@endpush
