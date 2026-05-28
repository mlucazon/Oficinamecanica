@extends('layouts.app')
@section('title', $ordemServico->numero)
@section('breadcrumb', 'OS / ' . $ordemServico->numero)

@push('styles')
<style>
    .os-show-page {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .os-show-header {
        display: flex;
        align-items: center;
        gap: .65rem;
        flex-wrap: wrap;
        padding: .25rem 0 .35rem;
    }

    .os-show-header h5 {
        font-size: .95rem;
    }

    .os-show-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .5rem;
        flex-wrap: wrap;
        margin-left: auto;
    }

    .os-show-actions form {
        margin: 0;
    }

    .os-show-grid {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 1rem;
    }

    .os-show-page .card {
        overflow: hidden;
    }

    .os-show-page .card-header {
        min-height: 48px;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .os-show-page .card-body {
        padding: 1.15rem 1.25rem;
    }

    .payment-panel {
        padding: 1rem;
        border: 1px solid var(--border2);
        border-radius: 8px;
        background: rgba(255,255,255,.025);
    }

    .payment-form {
        display: grid;
        grid-template-columns: minmax(260px, 420px) 1fr;
        gap: .9rem;
        align-items: end;
    }

    .payment-method-field,
    #pagamento-cartao-os,
    .payment-actions {
        grid-column: 1 / -1;
    }

    .payment-actions {
        display: flex;
        justify-content: flex-end;
        gap: .6rem;
        flex-wrap: wrap;
        padding-top: .2rem;
    }

    #pagamento-pix-os,
    #pagamento-cartao-os .alert {
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .os-show-header,
        .os-show-actions,
        .payment-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .os-show-actions,
        .os-show-actions .btn,
        .os-show-actions form,
        .payment-actions .btn {
            width: 100%;
        }

        .payment-form {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="os-show-page">
<div class="os-show-header">
	    <h5 class="mb-0 font-mono">{{ $ordemServico->numero }}</h5>
	    <span class="badge badge-{{ $ordemServico->status }} fs-6">{{ $ordemServico->statusLabel() }}</span>
	    <div class="os-show-actions no-print">
	        <a href="{{ auth()->user()->isCliente() ? route('conta.os') : route('os.index') }}" class="btn btn-sm btn-outline-secondary">
	            <i class="bi bi-arrow-left me-1"></i>Voltar às OS
	        </a>
	        @if($ordemServico->aprovado_cliente)
        <a href="{{ route('os.print', $ordemServico->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-printer me-1"></i>Imprimir
        </a>
        @endif
        @if(auth()->user()->isCliente() && $ordemServico->cliente?->user_id === auth()->id() && in_array($ordemServico->status, ['aguardando_aceitacao', 'solicitacao_aceita', 'solicitacao_recusada']))
        <form method="POST" action="{{ route('os.destroy', $ordemServico->id) }}" style="display:inline;">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancelar o envio desta OS? Esta acao nao pode ser desfeita.')">
                <i class="bi bi-x-circle me-1"></i>Cancelar envio
            </button>
        </form>
        @endif
        @if(!$ordemServico->aprovado_cliente && auth()->user()->isGerente())
        <form method="POST" action="{{ route('os.aprovar', $ordemServico->id) }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-success"><i class="bi bi-check2-circle me-1"></i>Aprovar Orçamento</button>
        </form>
        @endif
        @if(!auth()->user()->isCliente() && $ordemServico->aprovado_cliente && $ordemServico->status !== 'finalizada' && $ordemServico->status !== 'cancelada')
        <form method="POST" action="{{ route('os.fechar', $ordemServico->id) }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-primary" onclick="return confirm('Finalizar esta OS?')">
                <i class="bi bi-flag-fill me-1"></i>Finalizar OS
            </button>
        </form>
        @endif
	        @if(auth()->user()->isCliente() && $ordemServico->status === 'finalizada' && $ordemServico->cliente?->user_id === auth()->id())
            @if(!$ordemServico->avaliacao)
                <a href="{{ route('avaliacoes.create', $ordemServico) }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-star me-1"></i>Avaliar OS
                </a>
            @else
                <a href="{{ route('avaliacoes.index') }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-star-fill me-1"></i>Ver avaliacao
                </a>
            @endif
	        <form method="POST" action="{{ route('os.destroy', $ordemServico->id) }}" style="display:inline;">
	            @csrf @method('DELETE')
	            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja apagar esta OS finalizada do seu histórico? Esta ação não pode ser desfeita.')">
	                <i class="bi bi-trash me-1"></i>Excluir
	            </button>
	        </form>
	        @elseif(!auth()->user()->isCliente() && !$ordemServico->aprovado_cliente)
	        <form method="POST" action="{{ route('os.destroy', $ordemServico->id) }}" style="display:inline;">
	            @csrf @method('DELETE')
	            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta OS? Esta ação não pode ser desfeita.')">
	                <i class="bi bi-trash me-1"></i>Excluir
	            </button>
	        </form>
	        @elseif(!auth()->user()->isCliente() && $ordemServico->status === 'cancelada')
	        <form method="POST" action="{{ route('os.destroy', $ordemServico->id) }}" style="display:inline;">
	            @csrf @method('DELETE')
	            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta OS? Esta ação não pode ser desfeita.')">
                <i class="bi bi-trash me-1"></i>Excluir
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row g-3 os-show-grid">

    @if(str_contains((string) $ordemServico->observacoes, 'garantia ativa do veiculo'))
        <div class="col-12">
            <div class="alert alert-success mb-0">
                <i class="bi bi-shield-check me-1"></i>
                Esta OS foi aberta usando uma garantia ativa deste veiculo.
            </div>
        </div>
    @endif

    @if(auth()->user()->isCliente() && $ordemServico->cliente?->user_id === auth()->id() && $ordemServico->aprovado_cliente && in_array($ordemServico->status, ['em_execucao', 'aprovada', 'aguardando_finalizacao']))
        <div class="col-12">
            <div class="alert alert-warning d-flex align-items-start justify-content-between gap-3 flex-wrap mb-0">
                <div>
                    <div class="fw-semibold">
                        <i class="bi bi-geo-alt-fill me-1"></i>Compareca a oficina com o veiculo
                    </div>
                    <div class="small">
                        O mecanico solicitou sua presenca com o veiculo na oficina para prosseguir com o servico aprovado.
                    </div>
                </div>
                <a href="{{ route('localizacao') }}" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-map me-1"></i>Ver localizacao
                </a>
            </div>
        </div>
    @endif

    {{-- Info geral --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Informações</div>
            <div class="card-body">
                <dl class="row mb-0 small">
	                    <dt class="col-5" style="color: var(--text2);">Cliente</dt>
                    <dd class="col-7 fw-500">{{ $ordemServico->cliente->nome }}</dd>

	                    <dt class="col-5" style="color: var(--text2);">Veículo</dt>
                    <dd class="col-7">{{ $ordemServico->veiculo->marca }} {{ $ordemServico->veiculo->modelo }}</dd>

	                    <dt class="col-5" style="color: var(--text2);">Placa</dt>
                    <dd class="col-7 font-mono">{{ $ordemServico->veiculo->placa }}</dd>


	                    <dt class="col-5" style="color: var(--text2);">Km entrada</dt>
                    <dd class="col-7 font-mono">{{ $ordemServico->km_entrada ? number_format($ordemServico->km_entrada,0,',','.') : '—' }}</dd>


	                    <dt class="col-5" style="color: var(--text2);">Abertura</dt>
                    <dd class="col-7">{{ $ordemServico->created_at->format('d/m/Y H:i') }}</dd>

                    @if($ordemServico->data_previsao)
	                    <dt class="col-5" style="color: var(--text2);">Previsão</dt>
                    <dd class="col-7">{{ $ordemServico->data_previsao->format('d/m/Y') }}</dd>
                    @endif

                    @if($ordemServico->data_conclusao)
	                    <dt class="col-5" style="color: var(--text2);">Concluída</dt>
                    <dd class="col-7">{{ $ordemServico->data_conclusao->format('d/m/Y H:i') }}</dd>
                    @endif

	                    @if($ordemServico->mecanico)
		                    <dt class="col-5" style="color: var(--text2);">Mecânico</dt>
	                    <dd class="col-7">
	                        {{ $ordemServico->mecanico->nome }}
	                        @unless(auth()->user()->isCliente())
		                            <br><small style="color: var(--text2);">{{ $ordemServico->mecanico->user->email }}</small>
	                        @endunless
	                    </dd>
	                    @endif
                </dl>
            </div>
        </div>
    </div>

    {{-- Sintomas / Diagnóstico --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                Sintomas
                {{-- Botão do cabeçalho removido para evitar duplicidade; edição agora é feita via botão inline abaixo --}}
            </div>


            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
	                        <p class="small mb-1" style="color: var(--text2);">Sintomas:</p>
                        <p class="mb-0" id="sintomas-texto">{{ $ordemServico->sintomas ?: '—' }}</p>
                    </div>
                    @if(auth()->user()->isCliente())
                        <button type="button" class="btn btn-sm btn-outline-primary no-print" id="btn-editar-sintomas" onclick="editarSintomas()">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </button>
                    @endif
                </div>

                @if(auth()->user()->isCliente())
                    <form method="POST" action="{{ route('os.update', $ordemServico->id) }}" class="no-print" id="sintomas-form" style="display:none;">
                        @csrf
                        @method('PUT')
                        <textarea name="sintomas" class="form-control" rows="4">{{ $ordemServico->sintomas }}</textarea>
                        <div class="mt-2 d-flex gap-2">
                            <button class="btn btn-sm btn-primary" type="submit">Salvar</button>
                            <button class="btn btn-sm btn-outline-secondary" type="button" onclick="cancelarEdicaoSintomas()">Cancelar</button>
                        </div>
                    </form>
                @endif

            </div>

    </div>


    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-camera me-2"></i>Fotos e vídeos do cliente</div>
            <div class="card-body">
                <div class="row g-2 mb-0">
                    @forelse($ordemServico->fotos as $foto)
                        @php
                            $url = $foto->url();
                            $urlPath = parse_url($url, PHP_URL_PATH) ?: $url;
                            $ext = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));
                            $isVideo = in_array($ext, ['mp4','webm','ogg','mov','avi','m4v','3gp']);
                        @endphp
                        <div class="col-6 col-md-3 col-xl-2">
                            <div class="position-relative">
                                @if($isVideo)
                                    <video src="{{ $url }}" class="img-fluid rounded" style="height:110px;width:100%;object-fit:cover" controls></video>
                                @else
                                    <img src="{{ $url }}" class="img-fluid rounded" style="height:110px;width:100%;object-fit:cover"
                                         title="{{ $foto->tipo }} / {{ $foto->lado }}"
                                         onerror="this.onerror=null;this.src='{{ asset('images/no-photo.png') }}';">
                                @endif
                                <span class="badge bg-dark position-absolute bottom-0 start-0 m-1" style="font-size:.6rem">{{ $foto->tipo }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-3">Nenhuma mídia enviada.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>


    {{-- Orçamento e diagnóstico do mecânico --}}
    @php
        $mostrarOrcamentoDiagnostico =
            $ordemServico->diagnostico ||
            $ordemServico->itens->count() > 0 ||
            in_array($ordemServico->status, [
                'em_diagnostico',
                'orcamento_enviado_atendente',
                'aguardando_aprovacao',
                'aprovada',
                'em_execucao',
                'aguardando_finalizacao',
                'aguardando_pecas',
                'finalizada',
            ]);
    @endphp

    @if($mostrarOrcamentoDiagnostico)
	    <div class="col-12">
	        <div class="card">
	            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span><i class="bi bi-clipboard2-pulse me-2 text-warning"></i>Orcamento e diagnostico</span>
                <span class="font-mono">R$ {{ number_format($ordemServico->valor_total, 2, ',', '.') }}</span>
            </div>
            <div class="card-body">
                @if(auth()->user()->isMecanico() && $ordemServico->mecanico_id === auth()->user()->mecanico?->id && in_array($ordemServico->status, ['em_diagnostico', 'orcamento_enviado_atendente']))
                    <form method="POST" action="{{ route('os.update', $ordemServico->id) }}" class="row g-3 mb-3">
                        @csrf
                        @method('PUT')
                        <div class="col-md-8">
                            <label class="form-label">Diagnostico da queixa do cliente</label>
                            <textarea name="diagnostico" id="diagnostico-input" class="form-control" rows="4" required>{{ old('diagnostico', $ordemServico->diagnostico) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Observacoes internas</label>
                            <textarea name="observacoes" id="observacoes-input" class="form-control" rows="4">{{ old('observacoes', $ordemServico->observacoes) }}</textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Salvar diagnostico</button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('os.itens.store', $ordemServico->id) }}" class="row g-2 align-items-end mb-3" id="orcamento-item-form">
                        @csrf
                        <input type="hidden" name="diagnostico" id="orcamento-diagnostico">
                        <input type="hidden" name="observacoes" id="orcamento-observacoes">
                        <div class="col-md-2">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" id="orcamento-tipo" class="form-select" required>
                                <option value="servico">Servico</option>
                                <option value="peca">Peca</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="orcamento-servico-wrap">
                            <label class="form-label">Servico</label>
                            <select name="servico_id" id="orcamento-servico" class="form-select">
                                <option value="">Selecione...</option>
                                @foreach($servicos as $servico)
                                    <option value="{{ $servico->id }}" data-descricao="{{ $servico->nome }}" data-valor="{{ $servico->valor_mao_obra }}">
                                        {{ $servico->nome }} - R$ {{ number_format($servico->valor_mao_obra, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-none" id="orcamento-peca-wrap">
                            <label class="form-label">Peca</label>
                            <select name="peca_id" id="orcamento-peca" class="form-select">
                                <option value="">Selecione...</option>
                                @foreach($pecas as $peca)
                                    <option value="{{ $peca->id }}" data-descricao="{{ $peca->nome }}" data-valor="{{ $peca->preco_venda }}" data-estoque="{{ $peca->estoque }}" data-unidade="{{ $peca->unidade }}">
                                        {{ $peca->nome }} - estoque {{ $peca->estoque }} {{ $peca->unidade }} - R$ {{ number_format($peca->preco_venda, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Item do orcamento</label>
                            <input type="text" name="descricao" id="orcamento-descricao" class="form-control" placeholder="Selecione acima" required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Qtd.</label>
                            <input type="number" name="quantidade" class="form-control" step="0.001" min="0.001" value="1" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Valor unitario</label>
                            <input type="number" name="valor_unitario" id="orcamento-valor" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-outline-danger w-100"><i class="bi bi-plus-lg me-1"></i>Adicionar</button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('os.orcamento.atendente', $ordemServico->id) }}" class="text-end mb-3">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success" onclick="return confirm('Enviar este orcamento para o atendente?')">
                            <i class="bi bi-send me-1"></i>Enviar orcamento ao atendente
                        </button>
                    </form>
                @endif
                @if($ordemServico->diagnostico)
                    <div class="mb-3">
                        <div class="text-muted small">Diagnostico</div>
                        <div>{{ $ordemServico->diagnostico }}</div>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Descricao</th><th>Qtd.</th><th>Unitario</th><th>Total</th></tr></thead>
                        <tbody>
                            @forelse($ordemServico->itens as $item)
                                <tr>
                                    <td>{{ $item->descricao }}</td>
                                    <td class="font-mono">{{ $item->quantidade_formatada }}</td>
                                    <td class="font-mono">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                    <td class="font-mono">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center py-3">Nenhum item no orcamento.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($ordemServico->temDescontoPrimeiraOs() && (float) $ordemServico->valor_desconto > 0)
                    @php
                        $subtotalOrcamento = (float) $ordemServico->valor_servicos + (float) $ordemServico->valor_pecas;
                    @endphp
                    <div class="alert alert-success mt-3 mb-0 d-flex align-items-start justify-content-between gap-3 flex-wrap">
                        <div>
                            <div class="fw-semibold">
                                <i class="bi bi-stars me-1"></i>Desconto de cliente novo aplicado
                            </div>
                            <div class="small">
                                Primeira OS com 30% de desconto no orcamento.
                            </div>
                        </div>
                        <div class="text-end font-mono">
                            <div>Subtotal: R$ {{ number_format($subtotalOrcamento, 2, ',', '.') }}</div>
                            <div class="text-success">- R$ {{ number_format($ordemServico->valor_desconto, 2, ',', '.') }}</div>
                            <strong>Total: R$ {{ number_format($ordemServico->valor_total, 2, ',', '.') }}</strong>
                        </div>
                    </div>
                @endif
                @if((auth()->user()->isAtendente() || auth()->user()->isGerente()) && $ordemServico->status === 'orcamento_enviado_atendente')
                    <form method="POST" action="{{ route('os.orcamento.cliente', $ordemServico->id) }}" class="text-end mt-3">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-warning"><i class="bi bi-send me-1"></i>Enviar orcamento ao cliente</button>
                    </form>
                @endif
                @if(auth()->user()->isCliente() && $ordemServico->status === 'aguardando_aprovacao')
                    @php
                        $pixCopiaColaOs = \App\Support\PixBrCode::gerar(
                            '090.507.633-83',
                            (float) $ordemServico->valor_total,
                            'AUTOTECH PRO',
                            'FORTALEZA',
                            'OS' . $ordemServico->id,
                            'OS ' . $ordemServico->numero
                        );
                        $pixQrOsUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($pixCopiaColaOs);
                    @endphp
                    <div class="alert alert-info mt-3 d-none" id="pagamento-pix-os">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-auto text-center">
                                <div style="display:inline-block;padding:10px;border:1px solid var(--border);border-radius:8px;background:#fff;">
                                    <img src="{{ $pixQrOsUrl }}" alt="QR Code Pix da OS" width="180" height="180" style="display:block;max-width:100%;height:auto;">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="fw-semibold mb-1">Aprovar orçamento e confirmar pagamento</div>
                                <div class="small mb-2">
                                    Total da OS: <span class="font-mono fw-bold">R$ {{ number_format($ordemServico->valor_total, 2, ',', '.') }}</span>.
                                    Escaneie o QR Code ou copie o Pix copia e cola.
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="navigator.clipboard?.writeText(@js($pixCopiaColaOs))">
                                        <i class="bi bi-clipboard me-1"></i>Copiar Pix
                                    </button>
                                </div>
                                <textarea class="form-control font-mono small mt-2" rows="3" readonly>{{ $pixCopiaColaOs }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="payment-panel mt-3">
                        <form method="POST" action="{{ route('os.cliente.aprovar', $ordemServico->id) }}" class="payment-form">
                            @csrf
                            @method('PATCH')
                            <div class="payment-method-field">
                                <label class="form-label">Forma de pagamento</label>
                                <select name="metodo_pagamento" id="metodo-pagamento-os" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <option value="pix">Pix</option>
                                    <option value="cartao">Cartao</option>
                                    <option value="dinheiro">Dinheiro/presencial</option>
                                </select>
                            </div>
                            <div class="col-12 d-none" id="pagamento-cartao-os">
                                <div class="alert alert-info mb-0">
                                    <div class="fw-semibold mb-2">Dados do cartao</div>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label">Tipo</label>
                                            <select name="tipo_cartao" id="tipo-cartao-os" class="form-select payment-card-field">
                                                <option value="debito">Debito</option>
                                                <option value="credito">Credito</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Numero do cartao</label>
                                            <input type="text" name="cartao_numero" class="form-control payment-card-field" inputmode="numeric" maxlength="24" placeholder="0000 0000 0000 0000">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Nome impresso</label>
                                            <input type="text" name="cartao_nome" class="form-control payment-card-field" placeholder="Nome no cartao">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Validade</label>
                                            <input type="text" name="cartao_validade" class="form-control payment-card-field" maxlength="5" placeholder="MM/AA">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">CVV</label>
                                            <input type="text" name="cartao_cvv" class="form-control payment-card-field" inputmode="numeric" maxlength="4" placeholder="123">
                                        </div>
                                        <div class="col-md-4 d-none" id="parcelas-cartao-os">
                                            <label class="form-label">Parcelas</label>
                                            <select name="parcelas" class="form-select" id="parcelas-input-os">
                                                @for($parcela = 1; $parcela <= 6; $parcela++)
                                                    <option value="{{ $parcela }}">{{ $parcela }}x de R$ {{ number_format($ordemServico->valor_total / $parcela, 2, ',', '.') }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-12 small">
                                            Para cartao de credito, o parcelamento esta disponivel em ate 6x.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-actions">
                                <button class="btn btn-success" onclick="return confirm('Confirmar pagamento e aprovar esta OS?')">
                                    <i class="bi bi-credit-card me-1"></i>Aprovar e pagar
                                </button>
                                <button type="submit" form="recusar-orcamento-form" class="btn btn-outline-danger">
                                    <i class="bi bi-x-circle me-1"></i>Recusar
                                </button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('os.cliente.recusar', $ordemServico->id) }}" id="recusar-orcamento-form" class="d-none">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="motivo_recusa" value="Cliente recusou o orcamento">
                        </form>
                    </div>
                @endif
            </div>
	        </div>
	    </div>
    @endif

	    @php
	        $garantiaPendente = $ordemServico->garantias->firstWhere('status', 'pendente');
	        $garantiaPagamento = $ordemServico->garantias->firstWhere('status', 'aguardando_pagamento');
	        $garantiaAceita = $ordemServico->garantias->firstWhere('status', 'aceita');
	        $garantiaRecusada = $ordemServico->garantias->firstWhere('status', 'recusada');
	    @endphp

	    @if($garantiaPendente || $garantiaPagamento || $garantiaAceita || $garantiaRecusada)
	        <div class="col-12">
	            <div class="card">
	                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
	                    <span><i class="bi bi-shield-check me-2 text-warning"></i>Garantia adicional</span>
	                    @if($garantiaPendente)
	                        <span class="badge bg-warning text-dark">Aguardando decisão do cliente</span>
	                    @elseif($garantiaPagamento)
	                        <span class="badge bg-warning text-dark">Aguardando pagamento</span>
	                    @elseif($garantiaAceita)
	                        <span class="badge bg-success">Garantia ativa</span>
	                    @else
	                        <span class="badge bg-secondary">Garantia recusada</span>
	                    @endif
	                </div>
	                <div class="card-body">
	                    @if($garantiaPendente)
	                        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
	                            <div>
	                                <div class="fw-semibold">60 dias de garantia para esta OS</div>
	                                <div class="small" style="color: var(--text2);">
	                                    Valor calculado para manter o serviço equilibrado com peças e mão de obra.
	                                </div>
	                            </div>
	                            <div class="text-end">
	                                <div class="font-mono fs-5">R$ {{ number_format($garantiaPendente->valor, 2, ',', '.') }}</div>
	                                <div class="small" style="color: var(--text2);">válida até {{ $garantiaPendente->data_fim->format('d/m/Y') }}</div>
	                            </div>
	                        </div>

	                        @if(auth()->user()->isCliente() && $ordemServico->cliente?->user_id === auth()->id())
	                            <div class="d-flex justify-content-end gap-2 mt-3 flex-wrap">
	                                <form method="POST" action="{{ route('garantias.recusar-oferta', $garantiaPendente) }}">
	                                    @csrf
	                                    @method('PATCH')
	                                    <button class="btn btn-outline-danger" onclick="return confirm('Recusar a garantia adicional?')">
	                                        <i class="bi bi-x-circle me-1"></i>Recusar garantia
	                                    </button>
	                                </form>
	                                <form method="POST" action="{{ route('garantias.aceitar-oferta', $garantiaPendente) }}">
	                                    @csrf
	                                    @method('PATCH')
	                                    <button class="btn btn-success" onclick="return confirm('Adicionar esta garantia de 60 dias?')">
	                                        <i class="bi bi-check2-circle me-1"></i>Adicionar garantia
	                                    </button>
	                                </form>
		                            </div>
		                        @endif
		                    @elseif($garantiaPagamento)
                                @php
                                    $pixGarantiaCopiaCola = \App\Support\PixBrCode::gerar(
                                        '090.507.633-83',
                                        (float) $garantiaPagamento->valor,
                                        'AUTOTECH PRO',
                                        'FORTALEZA',
                                        'GAR' . $garantiaPagamento->id,
                                        'GARANTIA OS ' . $ordemServico->numero
                                    );
                                    $pixQrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . urlencode($pixGarantiaCopiaCola);
                                @endphp
		                        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-3">
		                            <div>
		                                <div class="fw-semibold">Pagamento da garantia de 60 dias</div>
		                                <div class="small" style="color: var(--text2);">
		                                    Confirme uma forma de pagamento para ativar a garantia nesta OS.
		                                </div>
		                            </div>
		                            <div class="text-end">
		                                <div class="font-mono fs-5">R$ {{ number_format($garantiaPagamento->valor, 2, ',', '.') }}</div>
		                                <div class="small" style="color: var(--text2);">60 dias após confirmação</div>
		                            </div>
		                        </div>

		                        @if(auth()->user()->isCliente() && $ordemServico->cliente?->user_id === auth()->id())
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-md-auto text-center">
                                            <div style="display:inline-block;padding:10px;border:1px solid var(--border);border-radius:8px;background:#fff;">
                                                <img src="{{ $pixQrUrl }}" alt="QR Code Pix Nubank" width="220" height="220" style="display:block;max-width:100%;height:auto;">
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="fw-semibold mb-1">Pague pelo Pix</div>
                                            <div class="small mb-2" style="color: var(--text2);">
                                                Escaneie o QR Code ou abra o link de cobranca. Depois confirme o pagamento abaixo para ativar a garantia.
                                            </div>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="navigator.clipboard?.writeText(@js($pixGarantiaCopiaCola))">
                                                    <i class="bi bi-clipboard me-1"></i>Copiar Pix
                                                </button>
                                            </div>
                                            <textarea class="form-control font-mono small mt-2" rows="3" readonly>{{ $pixGarantiaCopiaCola }}</textarea>
                                        </div>
                                    </div>
		                            <form method="POST" action="{{ route('garantias.pagar-oferta', $garantiaPagamento) }}" class="row g-3 align-items-end">
		                                @csrf
		                                @method('PATCH')
		                                <div class="col-md-8">
		                                    <label class="form-label">Forma de pagamento</label>
		                                    <select name="metodo_pagamento" class="form-select" required>
		                                        <option value="pix">Pix</option>
		                                        <option value="cartao">Cartão</option>
		                                        <option value="dinheiro">Dinheiro/presencial</option>
		                                    </select>
		                                </div>
		                                <div class="col-md-4">
		                                    <button class="btn btn-success w-100" onclick="return confirm('Confirmar pagamento da garantia?')">
		                                        <i class="bi bi-credit-card me-1"></i>Confirmar pagamento
		                                    </button>
		                                </div>
		                            </form>
                                    <form method="POST" action="{{ route('garantias.recusar-oferta', $garantiaPagamento) }}" class="text-end mt-2">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-outline-secondary" onclick="return confirm('Deseja cancelar a garantia e seguir pagando somente a OS?')">
                                            <i class="bi bi-x-circle me-1"></i>Cancelar garantia e pagar somente a OS
                                        </button>
                                    </form>
		                        @else
		                            <p class="text-muted mb-0">Aguardando o cliente confirmar o pagamento da garantia.</p>
		                        @endif
		                    @elseif($garantiaAceita)
		                        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
		                            <div>
	                                <div class="fw-semibold">{{ $garantiaAceita->descricao }}</div>
	                                <div class="text-muted small">Garantia válida até {{ $garantiaAceita->data_fim->format('d/m/Y') }}</div>
	                            </div>
	                            <div class="font-mono">R$ {{ number_format($garantiaAceita->valor, 2, ',', '.') }}</div>
	                        </div>
	                    @else
	                        <p class="text-muted mb-0">O cliente recusou a garantia adicional para esta OS.</p>
	                    @endif
	                </div>
	            </div>
	        </div>
	    @endif
	
	</div>
</div>
	@endsection

@push('scripts')
<script>
function atualizarItemOrcamento() {
    const tipo = document.getElementById('orcamento-tipo');
    const servicoWrap = document.getElementById('orcamento-servico-wrap');
    const pecaWrap = document.getElementById('orcamento-peca-wrap');
    const servico = document.getElementById('orcamento-servico');
    const peca = document.getElementById('orcamento-peca');
    const descricao = document.getElementById('orcamento-descricao');
    const valor = document.getElementById('orcamento-valor');

    if (!tipo || !servicoWrap || !pecaWrap || !servico || !peca || !descricao || !valor) return;

    const usandoPeca = tipo.value === 'peca';
    servicoWrap.classList.toggle('d-none', usandoPeca);
    pecaWrap.classList.toggle('d-none', !usandoPeca);
    servico.disabled = usandoPeca;
    peca.disabled = !usandoPeca;

    const select = usandoPeca ? peca : servico;
    const option = select.options[select.selectedIndex];
    descricao.value = option?.dataset.descricao || '';
    valor.value = option?.dataset.valor || '';
}

function sincronizarDiagnosticoNoItem() {
    const diagnostico = document.getElementById('diagnostico-input');
    const observacoes = document.getElementById('observacoes-input');
    const diagnosticoHidden = document.getElementById('orcamento-diagnostico');
    const observacoesHidden = document.getElementById('orcamento-observacoes');

    if (diagnostico && diagnosticoHidden) diagnosticoHidden.value = diagnostico.value;
    if (observacoes && observacoesHidden) observacoesHidden.value = observacoes.value;
}

document.addEventListener('DOMContentLoaded', () => {
    ['orcamento-tipo', 'orcamento-servico', 'orcamento-peca'].forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', atualizarItemOrcamento);
    });
    atualizarItemOrcamento();

    ['diagnostico-input', 'observacoes-input'].forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', sincronizarDiagnosticoNoItem);
    });

    const itemForm = document.getElementById('orcamento-item-form');
    if (itemForm) itemForm.addEventListener('submit', sincronizarDiagnosticoNoItem);
    sincronizarDiagnosticoNoItem();

    const metodoPagamento = document.getElementById('metodo-pagamento-os');
    const tipoCartao = document.getElementById('tipo-cartao-os');
    if (metodoPagamento) {
        metodoPagamento.addEventListener('change', atualizarPagamentoOs);
        tipoCartao?.addEventListener('change', atualizarPagamentoOs);
        atualizarPagamentoOs();
    }
});

function atualizarPagamentoOs() {
    const metodo = document.getElementById('metodo-pagamento-os');
    const pix = document.getElementById('pagamento-pix-os');
    const cartao = document.getElementById('pagamento-cartao-os');
    const tipoCartao = document.getElementById('tipo-cartao-os');
    const parcelasWrap = document.getElementById('parcelas-cartao-os');
    const parcelas = document.getElementById('parcelas-input-os');
    const cardFields = document.querySelectorAll('.payment-card-field');

    if (!metodo) return;

    const usandoPix = metodo.value === 'pix';
    const usandoCartao = metodo.value === 'cartao';
    const usandoCredito = usandoCartao && tipoCartao?.value === 'credito';

    pix?.classList.toggle('d-none', !usandoPix);
    cartao?.classList.toggle('d-none', !usandoCartao);
    parcelasWrap?.classList.toggle('d-none', !usandoCredito);

    cardFields.forEach((field) => {
        field.disabled = !usandoCartao;
        field.required = usandoCartao;
    });

    if (parcelas) {
        parcelas.disabled = !usandoCredito;
        parcelas.required = usandoCredito;
    }
}

function editarSintomas() {
    const texto = document.getElementById('sintomas-texto');
    const form = document.getElementById('sintomas-form');
    if (!texto || !form) return;

    texto.style.display = 'none';
    form.style.display = 'block';
}

function cancelarEdicaoSintomas() {
    const texto = document.getElementById('sintomas-texto');
    const form = document.getElementById('sintomas-form');
    if (!texto || !form) return;

    form.style.display = 'none';
    texto.style.display = '';
    // opcional: resetar textarea para o valor atual
    const ta = form.querySelector('textarea[name="sintomas"]');
    if (ta) {
        ta.value = @json($ordemServico->sintomas);
    }
}

// Scripts da OS removidos (seções de Serviços e Peças foram removidas para o cliente)
</script>
@endpush
