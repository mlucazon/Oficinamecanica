@extends('layouts.app')
@section('title', 'Meu perfil')
@section('breadcrumb', 'Meu perfil')

@push('styles')
<style>
    .profile-photo-card .card-body {
        min-height: 340px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .profile-avatar-wrap {
        width: 156px;
        height: 156px;
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
        bottom: 20px;
        width: 38px;
        height: 38px;
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
        color: var(--text3);
        font-size: 12px;
        line-height: 1.5;
        text-align: center;
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
        min-height: 48px;
        background: linear-gradient(180deg, rgba(255,255,255,.045), rgba(255,255,255,.018));
        border: 1px solid rgba(255,255,255,.12);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.04);
        padding: .75rem .95rem;
        font-size: 15px;
    }

    .profile-pretty-form .form-control[readonly],
    .profile-pretty-form .form-select[aria-disabled="true"] {
        color: rgba(255,255,255,.88);
        background: rgba(255,255,255,.026);
        border-color: rgba(255,255,255,.09);
    }

    :root[data-theme="light"] .profile-pretty-form .form-label {
        color: #3b332b;
    }

    :root[data-theme="light"] .profile-pretty-form .form-label i {
        color: var(--red-h);
    }

    :root[data-theme="light"] .profile-pretty-form .form-control,
    :root[data-theme="light"] .profile-pretty-form .form-select {
        color: #17130f;
        background: #ffffff;
        border-color: rgba(31,25,20,.16);
        box-shadow: inset 0 1px 0 rgba(31,25,20,.04);
    }

    :root[data-theme="light"] .profile-pretty-form .form-control[readonly],
    :root[data-theme="light"] .profile-pretty-form .form-select[aria-disabled="true"] {
        color: #17130f;
        background: #f6f2eb;
        border-color: rgba(31,25,20,.14);
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
        color: #6b6258 !important;
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
<div class="row g-3">
    <div class="col-lg-4">
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

    <div class="col-lg-8">
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
                            <select name="estado" id="estado-select" class="form-select js-profile-field @error('estado') is-invalid @enderror">
                                <option value="">Selecione...</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->uf }}" @selected($estadoSelecionado === $estado->uf)>{{ $estado->nome }} / {{ $estado->uf }}</option>
                                @endforeach
                            </select>
                            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-geo-alt"></i>Cidade</label>
                            @php $cidadeSelecionada = old('cidade', $user->cliente?->cidade); @endphp
                            <select name="cidade" id="cidade-select" class="form-select js-profile-field" data-selected="{{ $cidadeSelecionada }}">
                                <option value="">Selecione um estado primeiro...</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-house-door"></i>Endereço</label>
                            <input type="text" name="endereco" class="form-control js-profile-field" value="{{ old('endereco', $user->cliente?->endereco) }}">
                        </div>
                    @endif

                    @if($user->isMecanico())
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-card-text"></i>CPF</label>
                            <input type="text" name="cpf" class="form-control js-profile-field @error('cpf') is-invalid @enderror" value="{{ old('cpf', $user->mecanico?->cpf) }}">
                            @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-telephone"></i>Telefone</label>
                            <input type="text" name="telefone" class="form-control js-profile-field" value="{{ old('telefone', $user->mecanico?->telefone) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-tools"></i>Especialidade</label>
                            <input type="text" name="especialidade" class="form-control js-profile-field" value="{{ old('especialidade', $user->mecanico?->especialidade) }}">
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

        @if($user->isMecanico())
            <div class="card mt-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-tools me-2 text-warning"></i>Minhas OS recebidas</span>
                    <a href="{{ route('os.index') }}" class="btn btn-sm btn-outline-secondary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>OS</th>
                                    <th>Cliente</th>
                                    <th>Veiculo</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ordensMecanico as $os)
                                    <tr>
                                        <td><span class="font-mono small">{{ $os->numero }}</span></td>
                                        <td>{{ $os->cliente->nome }}</td>
                                        <td>{{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}<br><span class="badge bg-light text-dark font-mono">{{ $os->veiculo->placa }}</span></td>
                                        <td><span class="badge badge-{{ $os->status }}">{{ $os->statusLabel() }}</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('os.show', $os->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Nenhuma OS foi encaminhada para voce ainda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($user->isCliente())
            <div class="card mt-3">
                <div class="card-header">Senha</div>
                <div class="card-body d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <div>
                        <div class="fw-500">Troca de senha</div>
                        <div class="text-muted small">
                            {{ $user->password_change_requested_at ? 'Solicitada em '.$user->password_change_requested_at->format('d/m/Y H:i') : 'Solicite ao gerente uma alteração de senha.' }}
                        </div>
                    </div>
	                    <div class="text-end">
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
    const cidadeSelect = document.getElementById('cidade-select');
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

    function carregarCidades() {
        if (!estadoSelect || !cidadeSelect) {
            return;
        }

        const uf = estadoSelect.value;
        const selected = cidadeSelect.dataset.selected || '';
        const cidades = cidadesPorEstado[uf] || [];

        cidadeSelect.innerHTML = '<option value="">Selecione...</option>';

        cidades.forEach((cidade) => {
            const option = document.createElement('option');
            option.value = cidade;
            option.textContent = cidade;
            option.selected = cidade === selected;
            cidadeSelect.appendChild(option);
        });
    }

    if (estadoSelect && cidadeSelect) {
        carregarCidades();
        estadoSelect.addEventListener('change', function () {
            cidadeSelect.dataset.selected = '';
            carregarCidades();
        });
    }

});
</script>
@endpush
