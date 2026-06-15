@extends('layouts.app')
@section('title', 'Layout')
@section('breadcrumb', 'Layout')

@push('styles')
<style>
    .layout-showcase {
        display: grid;
        gap: 1rem;
    }

    .layout-hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 1rem;
        padding: 1.2rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background:
            linear-gradient(135deg, rgba(196,0,0,.18), transparent 36%),
            linear-gradient(90deg, rgba(255,255,255,.045), transparent),
            var(--surface);
    }

    .layout-hero h1 {
        margin: 0;
        font-size: clamp(1.35rem, 2.3vw, 2rem);
        font-weight: 900;
        letter-spacing: 0;
    }

    .layout-hero p {
        margin: .35rem 0 0;
        max-width: 760px;
        color: var(--text2);
    }

    .layout-swatch-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: .8rem;
    }

    .layout-swatch {
        min-height: 92px;
        padding: .85rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        overflow: hidden;
        position: relative;
    }

    .layout-swatch::before {
        content: '';
        position: absolute;
        inset: 0;
        opacity: .18;
        background: linear-gradient(135deg, #fff, transparent 44%);
    }

    .layout-swatch strong,
    .layout-swatch span {
        position: relative;
        z-index: 1;
    }

    .layout-swatch strong {
        color: #fff;
        font-weight: 900;
    }

    .layout-swatch span {
        color: rgba(255,255,255,.78);
        font-size: .82rem;
    }

    .layout-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(320px, .72fr);
        gap: 1rem;
        align-items: start;
    }

    .layout-card-preview {
        display: grid;
        gap: .75rem;
    }

    .layout-button-row {
        display: flex;
        gap: .65rem;
        flex-wrap: wrap;
    }

    .layout-mini-dashboard {
        display: grid;
        gap: .75rem;
    }

    .layout-os-row {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        align-items: center;
        gap: .8rem;
        padding: .85rem;
        border: 1px solid var(--border2);
        border-radius: 8px;
        background: rgba(255,255,255,.025);
    }

    .layout-os-row strong {
        display: block;
        color: var(--text);
    }

    .layout-os-row span {
        color: var(--text2);
        font-size: .88rem;
    }

    .layout-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--red-dim);
        color: var(--red-h);
        font-size: 1.2rem;
    }

    .layout-wire {
        min-height: 420px;
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background:
            linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.015)),
            var(--surface);
    }

    .layout-wire-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .8rem;
        margin-bottom: 1rem;
    }

    .layout-wire-title {
        width: 42%;
        height: 18px;
        border-radius: 999px;
        background: var(--surface3);
    }

    .layout-wire-actions {
        display: flex;
        gap: .45rem;
    }

    .layout-wire-actions span {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--red-dim);
        border: 1px solid var(--red-border);
    }

    .layout-wire-content {
        display: grid;
        grid-template-columns: .9fr 1.1fr;
        gap: .85rem;
    }

    .layout-wire-panel {
        min-height: 150px;
        border: 1px solid var(--border2);
        border-radius: 8px;
        padding: .8rem;
        background: rgba(255,255,255,.025);
    }

    .layout-wire-line {
        height: 11px;
        border-radius: 999px;
        background: rgba(255,255,255,.10);
        margin-bottom: .6rem;
    }

    .layout-wire-line.short { width: 44%; }
    .layout-wire-line.mid { width: 68%; }

    @media (max-width: 900px) {
        .layout-hero,
        .layout-grid,
        .layout-wire-content {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="layout-showcase">
    <section class="layout-hero">
        <div>
            <h1>Layout AutoTech</h1>
            <p>Guia visual para manter telas, blocos de informacao, acoes e formularios consistentes em todo o sistema.</p>
        </div>
        <div class="layout-button-row">
            <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Acao principal</button>
            <button class="btn btn-outline-secondary"><i class="bi bi-eye me-1"></i>Visualizar</button>
        </div>
    </section>

    <div class="metric-grid">
        <div class="metric-tile">
            <span class="metric-icon red"><i class="bi bi-clipboard2-check"></i></span>
            <div>
                <div class="metric-label">Ordens abertas</div>
                <div class="metric-value">18</div>
                <div class="metric-note">exemplo de indicador</div>
            </div>
        </div>
        <div class="metric-tile">
            <span class="metric-icon green"><i class="bi bi-cash-stack"></i></span>
            <div>
                <div class="metric-label">Faturamento</div>
                <div class="metric-value">R$ 12.480</div>
                <div class="metric-note">card de gestao</div>
            </div>
        </div>
        <div class="metric-tile">
            <span class="metric-icon blue"><i class="bi bi-people"></i></span>
            <div>
                <div class="metric-label">Clientes</div>
                <div class="metric-value">42</div>
                <div class="metric-note">texto auxiliar curto</div>
            </div>
        </div>
    </div>

    <div class="layout-grid">
        <div class="card">
            <div class="card-header"><i class="bi bi-ui-checks-grid text-warning"></i>Componentes</div>
            <div class="card-body layout-card-preview">
                <div class="info-block">
                    <span class="info-block-icon"><i class="bi bi-info-circle"></i></span>
                    <div>
                        <span class="info-block-kicker">Bloco de informacao</span>
                        <div class="info-block-title">Use para mensagens importantes</div>
                        <div class="info-block-text">Ideal para status de OS, perfil incompleto, alertas de atendimento e orientacoes curtas.</div>
                    </div>
                    <div class="info-block-actions">
                        <button class="btn btn-outline-danger"><i class="bi bi-pencil-square me-1"></i>Editar</button>
                    </div>
                </div>

                <div class="layout-button-row">
                    <button class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Novo</button>
                    <button class="btn btn-success"><i class="bi bi-send me-1"></i>Enviar</button>
                    <button class="btn btn-warning"><i class="bi bi-clock me-1"></i>Aguardar</button>
                    <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Remover</button>
                </div>

                <form class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Campo de texto</label>
                        <input class="form-control" value="Exemplo preenchido">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Selecao</label>
                        <select class="form-select">
                            <option>Opcao principal</option>
                            <option>Opcao secundaria</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observacao</label>
                        <textarea class="form-control" rows="3">Texto de apoio para diagnostico, orcamento ou comunicacao.</textarea>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-palette text-danger"></i>Paleta</div>
            <div class="card-body">
                <div class="layout-swatch-grid">
                    <div class="layout-swatch" style="background:#C40000"><strong>Vermelho</strong><span>Acao e alerta</span></div>
                    <div class="layout-swatch" style="background:#151515"><strong>Surface</strong><span>Fundos escuros</span></div>
                    <div class="layout-swatch" style="background:#198754"><strong>Sucesso</strong><span>Confirmacoes</span></div>
                    <div class="layout-swatch" style="background:#ffc107"><strong>Aviso</strong><span>Pendencias</span></div>
                    <div class="layout-swatch" style="background:#0d6efd"><strong>Info</strong><span>Dados de apoio</span></div>
                    <div class="layout-swatch" style="background:#6c757d"><strong>Neutro</strong><span>Metadados</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="layout-grid">
        <div class="layout-wire">
            <div class="layout-wire-top">
                <div class="layout-wire-title"></div>
                <div class="layout-wire-actions"><span></span><span></span><span></span></div>
            </div>
            <div class="layout-wire-content">
                <div class="layout-wire-panel">
                    <div class="layout-wire-line short"></div>
                    <div class="layout-wire-line mid"></div>
                    <div class="layout-wire-line"></div>
                    <div class="layout-wire-line mid"></div>
                </div>
                <div class="layout-wire-panel">
                    <div class="layout-wire-line mid"></div>
                    <div class="layout-wire-line"></div>
                    <div class="layout-wire-line"></div>
                    <div class="layout-wire-line short"></div>
                </div>
                <div class="layout-wire-panel">
                    <div class="layout-wire-line"></div>
                    <div class="layout-wire-line mid"></div>
                    <div class="layout-wire-line short"></div>
                </div>
                <div class="layout-wire-panel">
                    <div class="layout-wire-line short"></div>
                    <div class="layout-wire-line"></div>
                    <div class="layout-wire-line mid"></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-kanban text-warning"></i>Lista de OS</div>
            <div class="card-body layout-mini-dashboard">
                <div class="layout-os-row">
                    <span class="layout-icon-box"><i class="bi bi-car-front"></i></span>
                    <div><strong>OS-20260614-0001</strong><span>Cliente aguardando aprovacao</span></div>
                    <span class="badge bg-warning text-dark">Pendente</span>
                </div>
                <div class="layout-os-row">
                    <span class="layout-icon-box"><i class="bi bi-tools"></i></span>
                    <div><strong>OS-20260614-0002</strong><span>Servico em execucao</span></div>
                    <span class="badge bg-primary">Execucao</span>
                </div>
                <div class="layout-os-row">
                    <span class="layout-icon-box"><i class="bi bi-check2"></i></span>
                    <div><strong>OS-20260614-0003</strong><span>Entrega concluida</span></div>
                    <span class="badge bg-success">Finalizada</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
